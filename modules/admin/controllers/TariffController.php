<?php

namespace app\modules\admin\controllers;

use app\models\addition\Addition;
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
        $model = Tariff::find()->joinWith('addition ad')->joinWith('additionQty adq')->where(['tariff.id' => $id])
            ->one();

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
     */
    public function actionUpdate($id) {
        $model = Tariff::find()->joinWith('tariffAddition')->joinWith('tariffAdditionQty')->where(['tariff.id' => $id])
            ->one();
        $additions = Addition::find()->indexBy('id')->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!empty($model->resolutionService)) {
                TariffAdditionQuantity::deleteAll(['tariff_id' => $id]);
                foreach ($model->resolutionService as $key => $resolutionService) {
                    if ($resolutionService != '') {
                        $newResSer = new TariffAdditionQuantity();
                        $newResSer->tariff_id = $id;
                        $newResSer->addition_id = $resolutionService;
                        $newResSer->status_con = $model->resolutionServiceQuantity[$key];
                        $newResSer->save();
                    }
                }
            }

            if (!empty($model->connectedServices)) {
                TariffAddition::deleteAll(['tariff_id' => $id]);
                foreach ($model->connectedServices as $connectedService) {
                    if ($connectedService != '') {
                        $newConSer = new TariffAddition();
                        $newConSer->tariff_id = $id;
                        $newConSer->addition_id = $connectedService;
                        $newConSer->save();
                    }
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
