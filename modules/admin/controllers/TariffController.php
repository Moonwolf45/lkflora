<?php

namespace app\modules\admin\controllers;

use app\models\addition\Addition;
use app\models\MessageToPaid;
use app\models\service\Service;
use app\models\shops\Shops;
use app\models\ShopsAddition;
use app\models\TariffAddition;
use app\models\TariffAdditionQuantity;
use Yii;
use app\models\tariff\Tariff;
use app\models\tariff\TariffSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TariffController implements the CRUD actions for Tariff model.
 */
class TariffController extends Controller {
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
     * Lists all Tariff models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new TariffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tariff model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        $model = Tariff::find()->joinWith('addition ad')->joinWith('additionQty adq')
            ->where(['tariff.id' => $id])->one();

        return $this->render('view', compact('model'));
    }

    /**
     * Creates a new Tariff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Tariff();
        $additions = Addition::find()->indexBy('id')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!empty($model->resolutionService)) {
                foreach ($model->resolutionService as $key => $resolutionService) {
                    if ($resolutionService != '') {
                        $newResSer = new TariffAdditionQuantity();
                        $newResSer->tariff_id = $model->id;
                        $newResSer->addition_id = $resolutionService;
                        $newResSer->status_con = $model->resolutionServiceQuantity[$key];
                        $newResSer->save();
                    }
                }
            }

            if (!empty($model->connectedServices)) {
                foreach ($model->connectedServices as $connectedService) {
                    if ($connectedService != '') {
                        $newConSer = new TariffAddition();
                        $newConSer->tariff_id = $model->id;
                        $newConSer->addition_id = $connectedService;
                        $newConSer->quantity = $model->connectedServiceQuantity[$key];
                        $newConSer->save();
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', compact('model', 'additions'));
    }

    /**
     * Updates an existing Tariff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id) {
        $model = Tariff::find()->joinWith('tariffAddition')->joinWith('tariffAdditionQty')->where(['tariff.id' => $id])
            ->one();
        $additions = Addition::find()->indexBy('id')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $edit_resolution_addition = [];
            $edit_connected_addition = [];

            $add_resolution_addition = [];
            $add_connected_addition = [];

            $resolution_addition = [];
            $connected_addition = [];

            $delete_resolution_addition = [];
            $delete_connected_addition = [];

            $all_shopsAddition = [];
            $shopsAddition_keys = [];
            $all_service = [];
            $all_service_to_id = [];
            $all_shop = Shops::find()->select('id')->where(['tariff_id' => $id])->indexBy('id')->all();
            $shops_keys = array_keys($all_shop);
            if (!empty($shops_keys)) {
                $shopsAdditions = ShopsAddition::find()->where(['shop_id' => $shops_keys])->all();
                if (!empty($shopsAdditions)) {
                    foreach ($shopsAdditions as $shopsAddition) {
                        $all_shopsAddition[$shopsAddition->addition_id][] = $shopsAddition;
                    }
                }

                foreach ($all_shopsAddition as $keys => $shopsAddition) {
                    if (!in_array($keys, $shopsAddition_keys)) {
                        $shopsAddition_keys[] = $keys;
                    }
                }

                $all_service = Service::find()->where(['type_serviceId' => $shopsAddition_keys,
                    'type_service' => Service::TYPE_ADDITION])->indexBy('id')->all();
            }

            if (!empty($all_service)) {
                foreach ($all_service as $service) {
                    $all_service_to_id[$service->shop_id][$service->type_serviceId][] = $service;
                }
            }

            if (!empty($model->connectedService)) {
                $tAs = TariffAddition::find()->where(['tariff_id' => $id])->indexBy('addition_id')->all();
                $tA_keys = array_keys($tAs);

                foreach ($model->connectedService as $key => $connectedService_one) {
                    if (in_array($connectedService_one, $tA_keys)) {
                        if ($tAs[$connectedService_one]->quantity != $model->connectedServiceQuantity[$key]) {
                            $tAs[$connectedService_one]->quantity = $model->connectedServiceQuantity[$key];
                            $tAs[$connectedService_one]->save(false);

                            $edit_connected_addition[$connectedService_one]['id'] = $connectedService_one;
                            $edit_connected_addition[$connectedService_one]['qty'] = $model->connectedServiceQuantity[$key];
                        }
                    } else {
                        $newConSer = new TariffAddition();
                        $newConSer->tariff_id = $id;
                        $newConSer->addition_id = $connectedService_one;
                        $newConSer->quantity = $model->connectedServiceQuantity[$key];
                        $newConSer->save();

                        $add_connected_addition[$connectedService_one]['id'] = $connectedService_one;
                        $add_connected_addition[$connectedService_one]['qty'] = $model->connectedServiceQuantity[$key];
                    }

                    $connected_addition[$connectedService_one]['id'] = $connectedService_one;
                    $connected_addition[$connectedService_one]['qty'] = $model->connectedServiceQuantity[$key];
                }

                foreach ($tAs as $t_key => $tA) {
                    $keys_connected_addition = array_keys($connected_addition);
                    if (!in_array($t_key, $keys_connected_addition)) {
                        $delete_connected_addition[$t_key] = $tA->quantity;

                        $tA->delete();
                    }
                }
            }

            if (!empty($model->resolutionService)) {
                $tAQs = TariffAdditionQuantity::find()->where(['tariff_id' => $id])->indexBy('addition_id')->all();
                $tAQ_keys = array_keys($tAQs);

                foreach ($model->resolutionService as $key => $resolutionService) {
                    if (in_array($resolutionService, $tAQ_keys)) {
                        if ($tAQs[$resolutionService]->status_con != $model->resolutionServiceQuantity[$key]) {
                            $tAQs[$resolutionService]->status_con = $model->resolutionServiceQuantity[$key];
                            $tAQs[$resolutionService]->save(false);

                            $edit_resolution_addition[$resolutionService]['id'] = $resolutionService;
                            $edit_resolution_addition[$resolutionService]['qty'] = $model->resolutionServiceQuantity[$key];
                        }
                    } else {
                        $newResSer = new TariffAdditionQuantity();
                        $newResSer->tariff_id = $id;
                        $newResSer->addition_id = $resolutionService;
                        $newResSer->status_con = $model->resolutionServiceQuantity[$key];
                        $newResSer->save();

                        $add_resolution_addition[$resolutionService]['id'] = $resolutionService;
                        $add_resolution_addition[$resolutionService]['qty'] = $model->resolutionServiceQuantity[$key];
                    }

                    $resolution_addition[$resolutionService]['id'] = $resolutionService;
                    $resolution_addition[$resolutionService]['qty'] = $model->resolutionServiceQuantity[$key];
                }

                foreach ($tAQs as $t_key => $tAQ) {
                    $keys_resolution_addition = array_keys($resolution_addition);
                    if (!in_array($t_key, $keys_resolution_addition)) {
                        $delete_resolution_addition[$t_key] = $tAQ->status_con;

                        $tAQ->delete();
                    }
                }
            }

            $key_add_res_addit = array_keys($add_resolution_addition);
            $key_add_con_addit = array_keys($add_connected_addition);
            $key_del_res_addit = array_keys($delete_resolution_addition);
            $key_del_con_addit = array_keys($delete_connected_addition);

            $add_edit_resolution = array_intersect($key_del_con_addit, $key_add_res_addit);
            $add_edit_connection = array_intersect($key_del_res_addit, $key_add_con_addit);

            $edit_message = [];

            //TODO: Удаление бесплатных
            if (!empty($delete_connected_addition)) {
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        if (in_array($key, $key_del_con_addit)) {
                            if (array_key_exists($key, $resolution_addition)) {
                                // TODO: Если есть платные
                                if (in_array($key, $shopsAddition_keys)) {
                                    foreach ($all_shopsAddition[$key] as $sA) {
                                        if ($sA->quantity > $resolution_addition[$key]['qty']) {
                                            $sA->quantity = $resolution_addition[$key]['qty'];
                                            $sA->save();
                                        }
                                    }
                                }

                                $d = 0;
                                foreach ($services as $service) {
                                    if ($d < $resolution_addition[$key]['qty']) {
                                        $service->writeoff_amount = $service->old_writeoff_amount;
                                        $service->old_writeoff_amount = null;
                                        $service->edit_description = 'Добавление услуги';
                                        $service->save(false);

                                        $edit_message[$service->id] = $service->writeoff_amount;
                                        $d++;
                                    } else {
                                        $service->delete();
                                        $d++;
                                    }
                                }
                            } else {
                                // TODO: Если нет платных
                                if (in_array($key, $shopsAddition_keys)) {
                                    foreach ($all_shopsAddition[$key] as $sA) {
                                        $sA->delete();
                                    }
                                }

                                foreach ($services as $service) {
                                    $service->delete();
                                }
                            }
                        }
                    }
                }
            }

            //TODO: Удаление платных
            if (!empty($delete_resolution_addition)) {
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        if (in_array($key, $key_del_res_addit)) {
                            if (array_key_exists($key, $connected_addition)) {
                                // TODO: Если бесплатные
                                if (in_array($key, $shopsAddition_keys)) {
                                    foreach ($all_shopsAddition[$key] as $sA) {
                                        if ($sA->quantity > $connected_addition[$key]['qty']) {
                                            $sA->quantity = $connected_addition[$key]['qty'];
                                            $sA->save();
                                        }
                                    }
                                }

                                $d = 0;
                                foreach ($services as $service) {
                                    if ($d < $connected_addition[$key]['qty']) {
                                        $service->old_writeoff_amount = $service->writeoff_amount;
                                        $service->writeoff_amount = 0;
                                        $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                        $service->save(false);

                                        $edit_message[$service->id] = $service->writeoff_amount;
                                        $d++;
                                    } else {
                                        $service->delete();
                                        $d++;
                                    }
                                }
                            } else {
                                // TODO: Если нету бесплатных
                                if (in_array($key, $shopsAddition_keys)) {
                                    foreach ($all_shopsAddition[$key] as $sA) {
                                        $sA->delete();
                                    }
                                }

                                foreach ($services as $service) {
                                    $service->delete();
                                }
                            }
                        }
                    }
                }
            }

            if ((!empty($delete_connected_addition) && !empty($add_resolution_addition))
                || (!empty($delete_resolution_addition) && !empty($add_connected_addition))) {
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        // TODO: Переносим бесплатные в платные
                        if (array_key_exists($key, $delete_connected_addition)) {
                            if (in_array($key, $shopsAddition_keys)) {
                                foreach ($all_shopsAddition[$key] as $sA) {
                                    if ($sA->quantity > $add_resolution_addition[$key]['qty']) {
                                        $sA->quantity = $add_resolution_addition[$key]['qty'];
                                        $sA->save();
                                    }
                                }
                            }

                            $d = 0;
                            foreach ($services as $service) {
                                if ($d < $add_resolution_addition[$key]['qty']) {
                                    $service->writeoff_amount = $service->old_writeoff_amount;
                                    $service->old_writeoff_amount = null;
                                    $service->edit_description = 'Добавление услуги';
                                    $service->save(false);

                                    $edit_message[$service->id] = $service->writeoff_amount;
                                    $d++;
                                } else {
                                    $service->delete();
                                    $d++;
                                }
                            }
                        }

                        // TODO: Переносим платные в бесплатные
                        if (array_key_exists($key, $delete_resolution_addition)) {
                            if (in_array($key, $shopsAddition_keys)) {
                                foreach ($all_shopsAddition[$key] as $sA) {
                                    if ($sA->quantity > $add_connected_addition[$key]['qty']) {
                                        $sA->quantity = $add_connected_addition[$key]['qty'];
                                        $sA->save();
                                    }
                                }
                            }

                            $d = 0;
                            foreach ($services as $service) {
                                if ($d < $add_connected_addition[$key]['qty']) {
                                    $service->old_writeoff_amount = $service->writeoff_amount;
                                    $service->writeoff_amount = 0;
                                    $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                    $service->save(false);

                                    $edit_message[$service->id] = $service->writeoff_amount;
                                    $d++;
                                } else {
                                    $service->delete();
                                    $d++;
                                }
                            }
                        }
                    }
                }
            }

            //TODO: Изменение кол-ва бесплатных
            if (!empty($edit_connected_addition) && !empty($all_shopsAddition)) {
                $keys_resolution = array_keys($resolution_addition);
                foreach ($edit_connected_addition as $result) {
                    if (in_array($result['id'], $shopsAddition_keys)) {
                        foreach ($all_shopsAddition[$result['id']] as $sA) {
                            if (in_array($result['id'], $keys_resolution)) {
                                if ($sA->quantity > $resolution_addition[$result['id']]['qty']) {
                                    $sA->quantity = $resolution_addition[$result['id']]['qty'];
                                    $sA->save();
                                }
                            } else {
                                if ($sA->quantity > $result['qty']) {
                                    $sA->quantity = $result['qty'];
                                    $sA->save();
                                }
                            }
                        }
                    }
                }

                $arr_keys_connected_addition = array_keys($edit_connected_addition);
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        if (in_array($key, $arr_keys_connected_addition)) {
                            if (in_array($key, $keys_resolution)) {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $resolution_addition[$key]['qty']) {
                                        if ($e < $edit_connected_addition[$key]['qty']) {
                                            $service->old_writeoff_amount = $service->writeoff_amount;
                                            $service->writeoff_amount = 0;
                                            $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        } else {
                                            $service->writeoff_amount = $service->old_writeoff_amount;
                                            $service->old_writeoff_amount = null;
                                            $service->edit_description = 'Добавление услуги';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        }
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            } else {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $edit_connected_addition[$key]['qty']) {
                                        $service->old_writeoff_amount = $service->writeoff_amount;
                                        $service->writeoff_amount = 0;
                                        $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                        $service->save(false);

                                        $edit_message[$service->id] = $service->writeoff_amount;
                                        $e++;
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //TODO: Изменение кол-ва платных
            if (!empty($edit_resolution_addition) && !empty($all_shopsAddition)) {
                $keys_connected = array_keys($connected_addition);
                foreach ($edit_resolution_addition as $result) {
                    if (in_array($result['id'], $shopsAddition_keys)) {
                        foreach ($all_shopsAddition[$result['id']] as $sA) {
                            if ($sA->quantity > $result['qty']) {
                                $sA->quantity = $result['qty'];
                                $sA->save();
                            }
                        }
                    }
                }

                $arr_keys_resolution_addition = array_keys($edit_resolution_addition);
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        if (in_array($key, $arr_keys_resolution_addition)) {
                            if (in_array($key, $keys_connected)) {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $edit_resolution_addition[$key]['qty']) {
                                        if ($e < $connected_addition[$key]['qty']) {
                                            $service->old_writeoff_amount = $service->writeoff_amount;
                                            $service->writeoff_amount = 0;
                                            $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        } else {
                                            $service->writeoff_amount = $service->old_writeoff_amount;
                                            $service->old_writeoff_amount = null;
                                            $service->edit_description = 'Добавление услуги';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        }
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            } else {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $edit_resolution_addition[$key]['qty']) {
                                        $service->writeoff_amount = $service->old_writeoff_amount;
                                        $service->old_writeoff_amount = null;
                                        $service->edit_description = 'Добавление услуги';
                                        $service->save(false);

                                        $edit_message[$service->id] = $service->writeoff_amount;
                                        $e++;
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            //TODO: Добавление бесплатных когда есть платные
            if (!empty($add_connected_addition) && !empty($all_shopsAddition)) {
                $keys_resolution = array_keys($resolution_addition);
                foreach ($add_connected_addition as $result) {
                    if (in_array($result['id'], $shopsAddition_keys)) {
                        foreach ($all_shopsAddition[$result['id']] as $sA) {
                            if (in_array($result['id'], $keys_resolution)) {
                                if ($sA->quantity > $resolution_addition[$result['id']]['qty']) {
                                    $sA->quantity = $resolution_addition[$result['id']]['qty'];
                                    $sA->save();
                                }
                            } else {
                                if ($sA->quantity > $result['qty']) {
                                    $sA->quantity = $result['qty'];
                                    $sA->save();
                                }
                            }
                        }
                    }
                }

                $arr_keys_add_connected_addition = array_keys($add_connected_addition);
                foreach ($all_service_to_id as $services_shop) {
                    foreach ($services_shop as $key => $services) {
                        if (in_array($key, $arr_keys_add_connected_addition)) {
                            if (in_array($key, $keys_resolution)) {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $resolution_addition[$key]['qty']) {
                                        if ($e < $add_connected_addition[$key]['qty']) {
                                            $service->old_writeoff_amount = $service->writeoff_amount;
                                            $service->writeoff_amount = 0;
                                            $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        } else {
                                            $service->writeoff_amount = $service->old_writeoff_amount;
                                            $service->old_writeoff_amount = null;
                                            $service->edit_description = 'Добавление услуги';
                                            $service->save(false);

                                            $edit_message[$service->id] = $service->writeoff_amount;
                                            $e++;
                                        }
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            } else {
                                $e = 0;
                                foreach ($services as $service) {
                                    if ($e < $add_connected_addition[$key]['qty']) {
                                        $service->old_writeoff_amount = $service->writeoff_amount;
                                        $service->writeoff_amount = 0;
                                        $service->edit_description = 'Добавление услуги<br>Стоимость услуги входит в стоимость тарифа';
                                        $service->save(false);

                                        $edit_message[$service->id] = $service->writeoff_amount;
                                        $e++;
                                    } else {
                                        $service->delete();
                                        $e++;
                                    }
                                }
                            }
                        }
                    }
                }
            }


            if (!empty($edit_message)) {
                $keys_message_to_paid = array_keys($edit_message);

                $messages = MessageToPaid::find()->where(['service_id' => $keys_message_to_paid])->all();
                foreach ($messages as $message) {
                    $message->amount = $edit_message[$message->service_id];
                    $message->save(false);
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', compact('model', 'additions'));
    }

    /**
     * Deletes an existing Tariff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Tariff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tariff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Tariff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
