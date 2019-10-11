<?php

namespace app\commands;

use app\models\db\User;
use app\models\MessageToPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\traits\MailToUserTrait;
use app\models\Transaction;
use Yii;
use yii\console\Controller;
use yii\httpclient\Client;

class CronController extends Controller {
    use MailToUserTrait;

    /**
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate() {
        Yii::beginProfile('UpdateBalanceUser', 'cronWork');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка услуг, для спиcаyия средств; Дата: " . Yii::$app->formatter->asDate($today, 'long') . "; Время: " . $time, 'cronWork');

        $services = Service::find()->where(['writeoff_date' => $today, 'agree' => Service::AGREE_TRUE,
            'deleted' => Service::DELETED_FALSE])->all();

        if (!empty($services)) {
            Yii::info("Начинаем обновлять пользователей" , 'cronWork');

            foreach ($services as $service) {
                $user = User::findOne($service->user_id);
                Yii::info("Пользователь: " . $user->company_name, 'cronWork');

                $oldDebtor = MessageToPaid::find()->where(['user_id' => $service->user_id,
                    'service_type' => $service->type_service, 'service_id' => $service->id,
                    'amount' => $service->writeoff_amount])->limit(1)->one();

                if ($user->balance < $service->writeoff_amount) {
                    Yii::info("Записываем человека в должники", 'cronWork');
                    $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
                    $service->save(false);

                    $oldDebtor->debtor = MessageToPaid::DEBTOR_YES;
                    $oldDebtor->save(false);
                } else {
                    Yii::info("Записываем движение по счету в БД", 'cronWork');
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
            Yii::info("Закончили обновлять пользователей", 'cronWork');
        } else {
            Yii::info("Сегодня списывать за уcлуги нечего", 'cronWork');
        }

        Yii::endProfile('UpdateBalanceUser', 'cronWork');
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRepeatTransaction() {
        Yii::beginProfile('RepeatTransaction', 'cronWork');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка таблицы транзакций, для поиска незваершенных; Дата: " . Yii::$app->formatter->asDate($today, 'long') . "; Время: " . $time, 'cronWork');

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
            Yii::info("Незавершенных транзакций нет", 'cronWork');
        }

        Yii::endProfile('RepeatTransaction', 'cronWork');
    }

    public function actionMessageAboutPaymentForServices() {
        Yii::beginProfile('MessageAboutPayment', 'cronWork');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка таблицы сообщений, для поиска скорых списаний; Дата: " . Yii::$app->formatter->asDate($today, 'long') . "; Время: " . $time, 'cronWork');

        $all_user = User::find()->where(['status' => User::STATUS_ACTIVE])->asArray()->all();

        if (!empty($all_user)) {
            Yii::info("Начинаем перебирать пользователей для сообщений", 'cronWork');

            foreach ($all_user as $user) {
                $messages = MessageToPaid::find()->where(['user_id' => $user['id']])
                    ->andWhere(['>=', 'date_to_paid', date('Y-m-d')])
                    ->andWhere(['<=', 'date_to_paid', date("Y-m-d",
                        mktime(0, 0, 0, date("m"), date("d") + 3, date("Y")))])
                    ->asArray()->all();

                if (!empty($messages)) {
                    Yii::info("Пользователь: " . $user['company_name'] . ' - ' . $user['email'], 'cronWork');

                    $text = '';
                    $tomorrow = 0;
                    $after_the_day_after_tomorrow = 0;

                    foreach ($messages as $nP) {
                        if ($nP['date_to_paid'] == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")))) {
                            $tomorrow += $nP['amount'];
                        }

                        if ($nP['date_to_paid'] == date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 3, date("Y")))) {
                            $after_the_day_after_tomorrow += $nP['amount'];
                        }
                    }

                    if ($tomorrow != 0 || $after_the_day_after_tomorrow != 0) {
                        $text .= '<h4>Важно!</h4>';
                        $text .= '<hr>';

                        if ($tomorrow != 0) {
                            $text .= '<p>Завтра у вас будет списана оплата за тариф\доп. услуги в размере';
                            $text .= '<b>' . Yii::$app->formatter->asDecimal($tomorrow, 2) . ' руб.</b>';
                            $text .=  '</p>';
                        }

                        if ($after_the_day_after_tomorrow != 0) {
                            $text .= '<p>Завтра у вас будет списана оплата за тариф\доп. услуги в размере';
                            $text .= '<b>' . Yii::$app->formatter->asDecimal($after_the_day_after_tomorrow, 2) . ' руб.</b>';
                            $text .=  '</p>';
                        }

                        $text .= '<br>';
                        $text .= '<p>Пожулайста проследите, что бы у вас на балансе хватило денег на оплату услуг.</p>';
                        $text .= '<br><br><br><p>Это сообщение сгенерировано автоматически. Отвечать на него не нежуно.</p>';
                    }

                    $this->sendMailToUser($user['email'], 'messageToPaid', 'Оплата за услуги на сайте Florapoint.ru', ['text' => $text]);
                }
            }

            Yii::info("Закончили перебирать пользователей", 'cronWork');
        } else {
            Yii::info("Сообщений нет", 'cronWork');
        }

        Yii::endProfile('MessageAboutPayment', 'cronWork');
    }
}
