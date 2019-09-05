<?php

namespace app\commands;

use app\models\db\User;
use app\models\MessageToPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\Transaction;
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;

class CronController extends Controller {

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate() {
        Yii::beginProfile('UpdateBalanceUser');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка услуг, для спиcаyия средств\r\n 
            Дата: " . Yii::$app->formatter->asDate($today, 'long') . "\r\n 
            Время: " . $time, 'cron_work');

        $services = Service::find()->where(['writeoff_date' => $today, 'agree' => Service::AGREE_TRUE,
            'deleted' => Service::DELETED_FALSE])->all();

        if (!empty($services)) {
            Yii::info("Начинаем обновлять пользователей" , 'cron_work');

            foreach ($services as $service) {
                $user = User::findOne($service->user_id);
                Yii::info("Пользователь: " . $user->company_name, 'cron_work');

                $oldDebtor = MessageToPaid::find()->where(['user_id' => $service->user_id,
                    'service_type' => $service->type_service, 'service_id' => $service->id,
                    'amount' => $service->writeoff_amount])->limit(1)->one();

                if ($user->balance < $service->writeoff_amount) {
                    $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
                    $service->save(false);

                    $oldDebtor->debtor = MessageToPaid::DEBTOR_YES;
                    $oldDebtor->save(false);
                } else {
                    Yii::info("Записываем движение по счету в БД", 'cron_work');
                    $payment = new Payments();
                    $payment->user_id = $service->user_id;
                    $payment->shop_id = $service->shop_id;
                    if ($service->type_service = Service::TYPE_TARIFF) {
                        $payment->type_service = Payments::TYPE_SERVICE_TARIFF;
                    } else {
                        $payment->type_service = Payments::TYPE_SERVICE_ADDITION;
                    }
                    $payment->service_id = $service->type_serviceId;
                    $payment->type = Payments::TYPE_WRITEOFF;
                    $payment->way = Payments::WAY_BALANCE;
                    $payment->date = $today;
                    $payment->invoice_date = $today;
                    $payment->amount = $service->writeoff_amount;
                    if ($service->type_service = Service::TYPE_TARIFF) {
                        $payment->description = 'Списание с баланса оплаты за тариф';
                    } else {
                        if ($payment->amount == 0) {
                            $payment->description = 'Стоимость услуги входит в стоимость тарифа<br>Списание с баланса оплаты за доп. услугу';
                        } else {
                            $payment->description = 'Списание с баланса оплаты за доп. услугу';
                        }
                    }
                    $payment->status = Payments::STATUS_PAID;
                    $payment->save(false);

                    $new_balance = $user->balance - $service->writeoff_amount;
                    $user->balance = $new_balance;
                    $user->save(false);

                    if ($service->repeat_service == Service::REPEAT_TRUE) {
                        $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                        $service->save(false);

                        $oldDebtor->date_to_paid = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                        $oldDebtor->debtor = MessageToPaid::DEBTOR_NO;
                        $oldDebtor->save(false);
                    } else {
                        $oldDebtor->debtor = MessageToPaid::DEBTOR_NO;
                        $oldDebtor->save(false);
                    }
                }
            }
            Yii::info("Закончили обновлять пользователей", 'cron_work');
        } else {
            Yii::info("Сегодня списывать за уcлуги нечего", 'cron_work');
        }

        Yii::endProfile('UpdateBalanceUser', 'cron_work');
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRepeatTransaction() {
        Yii::beginProfile('RepeatTransaction', 'cron_work');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка таблицы транзакций, для поиска незваершенных\r\n 
            Дата: " . Yii::$app->formatter->asDate($today, 'long') . "\r\n 
            Время: " . $time, 'cron_work');

        $transactions = Transaction::find()->where(['status' => Transaction::STATUS_REPEAT])->all();
        if (!empty($transactions)) {
            foreach ($transactions as $transaction) {
                $payments = new Payments();
                $time_now = time();
                $salt = Yii::$app->security->generateRandomString(32);

                $signature = $payments->getSignature(['transaction_id' => $transaction->transaction_id, 'unix_timestamp' => $time_now,
                    'merchant' => Yii::$app->params['idSite'], 'salt' => $salt]);

                $client = new Client(['requestConfig' => ['format' => Client::FORMAT_URLENCODED],
                    'responseConfig' => ['format' => Client::FORMAT_JSON]]);

                $response = $client->createRequest()->setMethod('GET')
                    ->setUrl('https://pay.modulbank.ru/api/v1/transaction/')
                    ->setData(['transaction_id' => $transaction->transaction_id, 'merchant' => Yii::$app->params['idSite'],
                        'unix_timestamp' => $time_now, 'salt' => $salt,
                        'signature' => $signature])->send();

                if ($response->isOk) {
                    $resp_array = json_decode($response->content);
                    if ($resp_array->status == 'ok') {
                        $user = User::findByEmail($resp_array->transaction->client_email);
                        $payment = Payments::findOne($transaction->payment_id);

                        if ($resp_array->transaction->state == 'COMPLETE') {
                            $user->balance += $resp_array->transaction->amount;
                            $user->save(false);

                            $payment->status = Payments::STATUS_PAID;

                            $transaction->status = Transaction::STATUS_OK;
                            $transaction->save(false);
                        } elseif ($resp_array->transaction->state == 'PROCESSING' || $resp_array->transaction->state == 'WAITING_FOR_3DS') {
                            $payment->status = Payments::STATUS_WAITING;
                        }  elseif ($resp_array->transaction->state == 'FAILED') {
                            $payment->status = Payments::STATUS_CANCEL;

                            $transaction->status = Transaction::STATUS_OK;
                            $transaction->save(false);
                        }
                        $payment->save(false);
                    }
                }
            }
        } else {
            Yii::info("Незавершенных транзакций нет", 'cron_work');
        }

        Yii::endProfile('RepeatTransaction', 'cron_work');
    }

    public function actionMessageAboutPaymentForServices() {
        Yii::beginProfile('MessageAboutPayment', 'cron_work');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка таблицы сообщений, для поиска скорых списаний\r\n 
            Дата: " . Yii::$app->formatter->asDate($today, 'long') . "\r\n 
            Время: " . $time, 'cron_work');

        $messages = MessageToPaid::find()->where(['>=', 'date_to_paid', date("Y-m-d",
            mktime(0, 0, 0, date("m"), date("d") + 3, date("Y")))])
            ->limit(1)->groupBy('user_id')->all();

        if (!empty($messages)) {
            Yii::info("Начинаем перебирать отбирать пользователей для сообщений", 'cron_work');


        } else {
            Yii::info("Сообщений нет", 'cron_work');
        }

        Yii::endProfile('MessageAboutPayment', 'cron_work');
    }
}
