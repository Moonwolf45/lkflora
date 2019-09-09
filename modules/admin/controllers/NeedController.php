<?php

namespace app\modules\admin\controllers;


use app\models\db\User;
use app\models\MessageToPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\service\ServiceNotAgreeSearch;
use app\models\shops\Shops;
use app\models\ShopsAddition;
use app\models\TariffAddition;
use app\models\TariffAdditionQuantity;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class NeedController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Service models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex() {
        $searchModel = new ServiceNotAgreeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Service model.
     *
     * @param $id
     *
     * @return string
     */
    public function actionView($id) {
        $model = Service::find()->joinWith('user')->joinWith('shop')->joinWith('tariff')
            ->joinWith('additions')->where(['service.id' => $id])->limit(1)->one();

        return $this->render('view', ['model' => $model]);
    }

    /**
     * @param $id
     * @param $shop_id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id, $shop_id) {
        $service = Service::findOne($id);
        $service->connection_date = date('Y-m-d');
        $service->agree = Service::AGREE_TRUE;

        $message = MessageToPaid::find()->where(['service_id' => $id, 'user_id' => $service->user_id,
            'service_type' => $service->type_service])->limit(1)->one();

        if ($service->deleted == Service::DELETED_FALSE) {
            $user_update = User::findOne($service->user_id);
            if ($user_update->balance > $service->writeoff_amount) {
                $user_update->balance -= $service->writeoff_amount;
                $user_update->save(false);

                $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                $service->save(false);

                $date_to_paid = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));

                $payment = new Payments();
                $payment->user_id = $service->user_id;
                $payment->shop_id = $service->shop_id;
                if ($service->type_service == Service::TYPE_TARIFF) {
                    $payment->type_service = Payments::TYPE_SERVICE_TARIFF;
                } else {
                    $payment->type_service = Payments::TYPE_SERVICE_ADDITION;
                }
                $payment->service_id = $service->type_serviceId;
                $payment->type = Payments::TYPE_WRITEOFF;
                $payment->way = Payments::WAY_BALANCE;
                $payment->date = date('Y-m-d');
                $payment->invoice_date = date('Y-m-d');
                $payment->amount = $service->writeoff_amount;
                if ($service->type_service == Service::TYPE_TARIFF) {
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

                if (!empty($message)) {
                    $message->date_to_paid = $date_to_paid;
                    $message->amount = $service->writeoff_amount;
                    $message->debtor = MessageToPaid::DEBTOR_NO;
                    $message->save(false);
                } else {
                    $message_to_paid = new MessageToPaid();
                    $message_to_paid->user_id = $service->user_id;
                    $message_to_paid->service_type = $service->type_service;
                    $message_to_paid->service_id = $id;
                    $message_to_paid->date_to_paid = $date_to_paid;
                    $message_to_paid->amount = $service->writeoff_amount;
                    $message_to_paid->debtor = MessageToPaid::DEBTOR_NO;
                    $message_to_paid->save(false);
                }

                if ($service->type_service == Service::TYPE_TARIFF) {
                    $tA = TariffAddition::find()->where(['tariff_id' => $service->type_serviceId])
                        ->indexBy('addition_id')->all();
                    $keys_tA = array_keys($tA);

                    $tAQ = TariffAdditionQuantity::find()->where(['tariff_id' => $service->type_serviceId])
                        ->indexBy('addition_id')->all();
                    $keys_tAQ = array_keys($tAQ);

                    $sA = ShopsAddition::find()->where(['shop_id' => $service->shop_id])
                        ->indexBy('addition_id')->all();
                    $keys_sA = array_keys($sA);

                    $services = Service::find()->where(['shop_id' => $service->shop_id, 'user_id' => $service->user_id,
                        'type_serviceId' => $keys_sA, 'agree' => Service::AGREE_TRUE,
                        'deleted' => Service::DELETED_FALSE, 'type_service' => Service::TYPE_ADDITION])->all();
                    $newServices = [];
                    foreach ($services as $service) {
                        $newServices[$service->type_serviceId][] = $service;
                    }

                    foreach ($sA as $sA_key => $sA_value) {
                        if (in_array($sA_key, $keys_tA)) {
                            if ($tA[$sA_key]['quantity'] != 0 && $sA_value['quantity'] > $tA[$sA_key]['quantity']) {
                                $sA_value['quantity'] = $tA[$sA_key]['quantity'];
                                $sA_value->save();

                                $count_delete_service = count($newServices[$sA_key]) - $tA[$sA_key]['quantity'];
                                for ($del = 0; $del < $count_delete_service; $del++) {
                                    $newServices[$sA_key][$del]->delete();
                                }
                            }
                        }
                        if (in_array($sA_key, $keys_tAQ)) {
                            $new_count = count($newServices[$sA_key]);
                            if ($tAQ[$sA_key]['status_con'] != 0) {
                                if ($new_count < $tAQ[$sA_key]['status_con']) {
                                    $max_count = $new_count;
                                } else {
                                    $max_count = $tAQ[$sA_key]['status_con'];
                                }
                                for ($edit = 0; $edit < $max_count; $edit++) {
                                    $newServices[$sA_key][$edit]->writeoff_amount = 0;
                                    $newServices[$sA_key][$edit]->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                    $newServices[$sA_key][$edit]->save(false);
                                }
                            } else {
                                for ($edit = 0; $edit < $new_count; $edit++) {
                                    $newServices[$sA_key][$edit]->writeoff_amount = 0;
                                    $newServices[$sA_key][$edit]->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                    $newServices[$sA_key][$edit]->save(false);
                                }
                            }
                        }

                        if (!in_array($sA_key, $keys_tA) && !in_array($sA_key, $keys_tAQ)) {
                            $sA_value->delete();

                            foreach ($newServices[$sA_key] as $nS) {
                                $nS->delete();
                            }
                        }
                    }
                }
            } else {
                if (!empty($message)) {
                    $message->date_to_paid = date('Y-m-d');
                    $message->amount = $service->writeoff_amount;
                    $message->debtor = MessageToPaid::DEBTOR_YES;
                    $message->save(false);
                } else {
                    $message_to_paid = new MessageToPaid();
                    $message_to_paid->user_id = $service->user_id;
                    $message_to_paid->service_type = $service->type_service;
                    $message_to_paid->service_id = $id;
                    $message_to_paid->date_to_paid = date('Y-m-d');
                    $message_to_paid->amount = $service->writeoff_amount;
                    $message_to_paid->debtor = MessageToPaid::DEBTOR_YES;
                    $message_to_paid->save(false);
                }
                $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
                $service->save(false);
            }
        } else {
            $service->delete();
        }

        $count_service = Service::find()->where(['shop_id' => $shop_id, 'agree' => Service::AGREE_FALSE])->count();
        if ($count_service == 0) {
            $shop = Shops::findOne($shop_id);
            $shop->on_check = Shops::ON_CHECK_FALSE;
            $shop->save(false);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @param $shop_id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCancel($id, $shop_id) {
        $service = Service::findOne($id);

        if ($service->old_service_id == null && $service->old_connection_date == null && $service->old_writeoff_date == null) {
            if ($service->type_service == Service::TYPE_ADDITION) {
                $sA = ShopsAddition::find()->where(['shop_id' => $service->shop_id,
                    'addition_id' => $service->type_serviceId])->limit(1)->one();
                if ($service->deleted == Service::DELETED_TRUE) {
                    if (!empty($sA)) {
                        $shop_Addition = new ShopsAddition();
                        $shop_Addition->shop_id = $service->shop_id;
                        $shop_Addition->addition_id = $service->type_serviceId;
                        $shop_Addition->quantity = 1;
                        $shop_Addition->save();
                    } else {
                        $sA->quantity += 1;
                        $sA->save();
                    }
                    $service->agree = Service::AGREE_TRUE;
                    $service->deleted = Service::DELETED_FALSE;
                    $service->save();
                } else {
                    if ($sA->quantity > 1) {
                        $sA->quantity -= 1;
                        $sA->save();
                    } else {
                        $sA->delete();
                    }

                    $service->delete();
                }
            } else {
                $service->delete();
            }
        } else {
            $service->type_serviceId = $service->old_service_id;
            $service->connection_date = $service->old_connection_date;
            $service->writeoff_date = $service->old_writeoff_date;
            $service->writeoff_amount = $service->old_writeoff_amount;
            $service->agree = Service::AGREE_TRUE;
            $service->save();
        }

        $count_service = Service::find()->where(['shop_id' => $shop_id])->asArray()->all();
        $shop = Shops::findOne($shop_id);
        if (!empty($count_service)) {
            $agree_service = 0;
            foreach ($count_service as $service) {
                if ($service['agree'] == Service::AGREE_FALSE) {
                    $agree_service += 1;
                }
            }

            if ($agree_service > 0) {
                $shop->on_check = Shops::ON_CHECK_TRUE;
                $shop->save(false);
            } else {
                $shop->on_check = Shops::ON_CHECK_FALSE;
                $shop->save(false);
            }
        } else {
            $shop->delete();
        }


        return $this->redirect(['index']);
    }
}
