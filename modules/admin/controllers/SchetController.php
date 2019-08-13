<?php

namespace app\modules\admin\controllers;


use app\models\db\User;
use app\models\payments\Payments;
use app\models\payments\PaymentsSchetSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class SchetController extends Controller {

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
        $searchModel = new PaymentsSchetSearch();
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
        $model = Payments::find()->where(['payments.id' => $id])->joinWith('user')->limit(1)->one();

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Updates an existing Payments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @param $status
     *
     * @return mixed
     */
    public function actionUpdate($id, $status) {
        $model = Payments::findOne($id);
        $model->status = $status;
        $model->date = date('Y-m-d');
        $model->save();

        $user_update = User::findOne($model->user_id);
        $user_update->balance += $model->amount;
        $user_update->save();

        return $this->redirect(['view', 'id' => $id]);
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
