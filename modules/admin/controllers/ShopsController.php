<?php

namespace app\modules\admin\controllers;

use app\models\MessageToPaid;
use app\models\service\Service;
use app\models\tariff\Tariff;
use Yii;
use app\models\shops\Shops;
use app\models\shops\ShopsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShopsController implements the CRUD actions for Shops model.
 */
class ShopsController extends Controller {
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
     * Lists all Shops models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ShopsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shops model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id) {
        $model = Shops::find()->joinWith('services.tariff')->joinWith('services.additions')
            ->where(['shops.id' => $id])->limit(1)->one();

        return $this->render('view', compact('model'));
    }

    /**
     * Creates a new Shops model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Shops();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shops model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $tariff = Tariff::findOne(['id' => $model->tariff_id]);

            $service = Service::find()->where(['shop_id' => $model->id, 'type_service' => Service::TYPE_TARIFF])
                ->limit(1)->one();
            $service->type_serviceId = $model->tariff_id;
            $service->connection_date = date('Y-m-d');
            $service->writeoff_amount = $tariff->cost;
            $service->save(false);

            $message_to_service = MessageToPaid::find()->where(['service_type' => Service::TYPE_TARIFF,
                'service_id' => $service->id])->limit(1)->one();
            $message_to_service->amount = $tariff->cost;
            $message_to_service->save(false);

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Shops model.
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
        $services = Service::find()->where(['shop_id' => $id])->all();
        foreach ($services as $service) {
            $service->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Shops model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shops the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Shops::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
