<?php

namespace app\modules\admin\controllers;


use app\models\payments\Payments;
use app\models\payments\PaymentsFinanceSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FinanceController extends Controller {

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
     * Lists all Payments models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PaymentsFinanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Payments model.
     *
     * @param $id
     *
     * @return string
     */
    public function actionView($id) {
        $model = Payments::find()->where(['payments.id' => $id])->joinWith('user')->joinWith('shop')
            ->joinWith('tariff')->joinWith('addition')->limit(1)->one();

        return $this->render('view', ['model' => $model]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id) {
        Payments::findOne($id)->delete();

        return $this->redirect(['index']);
    }
}
