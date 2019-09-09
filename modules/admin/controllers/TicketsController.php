<?php

namespace app\modules\admin\controllers;


use yii\filters\VerbFilter;
use yii\web\Controller;

class TicketsController extends Controller {

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

    public function actionIndex() {
//        $searchModel = new AdditionSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
        ]);
    }

}
