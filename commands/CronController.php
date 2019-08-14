<?php

namespace app\commands;

use app\models\db\User;
use app\models\payments\Payments;
use app\models\service\Service;
use Yii;
use yii\console\Controller;

class CronController extends Controller {

    public function actionUpdate() {
        Yii::beginProfile('UpdateBalanceUser');

        $today = date('Y-m-d');
        $time = date('H:i:s');
        Yii::info("Проверка услуг, для спинаия средств\r\n Дата: " . Yii::$app->formatter
            ->asDate($today, 'long') . "\r\n Время: " . $time);

        $services = Service::find()->where(['writeoff_date' => $today, 'completed' => Service::COMPLETED_FALSE])
            ->asArray()->all();

        if (!empty($services)) {
            Yii::info("Начинаем обновлять пользователей");

            foreach ($services as $service) {
                $user = User::find()->where(['id' => $service['user_id']])->asArray()->one();
                Yii::info("Пользователь: " . $user['company_name']);

                $total = $service['writeoff_amount'] * $service['quantity'];
                $new_balance = $user['balance'] - $total;
                $user['balance'] = $new_balance;
                $user->save();

                $service['completed'] = Service::COMPLETED_TRUE;
                $service->save();

                if ($service['repeat'] == Service::REPEAT_TRUE) {
                    $new_service = new Service();

                    if ($service['type_service'] = Service::TYPE_TARIFF) {
                        $new_service->saveTariff($service['type_serviceId'], $service['shop_id'], $service['user_id']);
                    } else {
                        $new_service->saveAddition($service['type_serviceId'], $service['shop_id'], $service['quantity'], $service['user_id']);
                    }
                }

                Yii::info("Записываем движение по счету в БД");
                $payment = new Payments();
                $payment->user_id = $service['user_id'];
                $payment->shop_id = $service['shop_id'];
                $payment->service_id = $service['type_serviceId'];
                $payment->type = Payments::TYPE_WRITEOFF;
                $payment->way = Payments::WAY_BALANCE;
                $payment->date = $today;
                $payment->invoice_date = $today;
                $payment->amount = $total;
                if ($service['type_service'] = Service::TYPE_TARIFF) {
                    $payment->description = 'Списание с баланса оплаты за тариф';
                } else {
                    $payment->description = 'Списание с баланса оплаты за доп. услугу';
                }
                $payment->status = Payments::STATUS_PAID;
                $payment->save();
            }
            Yii::info("Закончили обновлять пользователей");
        } else {
            Yii::info("Обновлять нечего");
        }

        Yii::endProfile('UpdateBalanceUser');
    }

}
