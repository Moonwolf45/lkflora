<?php

namespace app\commands;

use app\models\db\User;
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
        Yii::info("Проверка услуг, для спиcаyия средств\r\n Дата: " . Yii::$app->formatter->asDate($today, 'long') . "\r\n Время: " . $time);

        $services = Service::find()->where(['writeoff_date' => $today, 'agree' => Service::AGREE_TRUE])->all();
        if (!empty($services)) {
            Yii::info("Начинаем обновлять пользователей");

            foreach ($services as $service) {
                $user = User::findOne($service->user_id);
                Yii::info("Пользователь: " . $user->company_name);

                if ($user->balance < $service->writeoff_amount) {
                    $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
                    $service->save();
                } else {
                    Yii::info("Записываем движение по счету в БД");
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
                        $payment->description = 'Списание с баланса оплаты за доп. услугу';
                    }
                    $payment->status = Payments::STATUS_PAID;
                    $payment->save();

                    $new_balance = $user->balance - $service->writeoff_amount;
                    $user->balance = $new_balance;

                    if ($service->repeat_service == Service::REPEAT_TRUE) {
                        $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                        $service->save();
                    } else {
                        $service->delete();
                    }
                }
                $user->save();
            }
            Yii::info("Закончили обновлять пользователей");
        } else {
            Yii::info("Сегодня списывать за уcлуги нечего");
        }

        Yii::endProfile('UpdateBalanceUser');
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRepeatTransaction() {
        Yii::beginProfile('RepeatTransaction');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка таблицы транзакций, для поиска незваершенных\r\n Дата: " . Yii::$app->formatter->asDate($today, 'long') . "\r\n Время: " . $time);

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
            Yii::info("Незавершенных транзакций нет");
        }

        Yii::endProfile('RepeatTransaction');
    }
}
