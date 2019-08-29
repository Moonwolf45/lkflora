<?php

namespace app\modules\admin\controllers;


use app\models\db\User;
use app\models\MessageToPaid;
use app\models\service\Service;
use app\models\service\ServiceNotAgreeSearch;
use app\models\shops\Shops;
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
     */
    public function actionUpdate($id, $shop_id) {
        $service = Service::findOne($id);
        $service->connection_date = date('Y-m-d');
        $service->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
        $service->agree = Service::AGREE_TRUE;
        $service->save(false);

        $message_to_paid = new MessageToPaid();
        $message_to_paid->user_id = $service->user_id;
        $message_to_paid->service_type = $service->type_service;
        $message_to_paid->service_id = $id;
        $message_to_paid->date_to_paid = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
        $message_to_paid->amount = $service->writeoff_amount;
        $message_to_paid->debtor = MessageToPaid::DEBTOR_NO;
        $message_to_paid->save(false);

        $shop = Shops::findOne($shop_id);
        $shop->on_check = Shops::ON_CHECK_FALSE;
        $shop->save(false);

        $user_update = User::findOne($service->user_id);
        if ($user_update->balance > $service->writeoff_amount) {
            $user_update->balance -= $service->writeoff_amount;
            $user_update->save(false);
        } else {
            $debtor = new MessageToPaid();
            $debtor->user_id = $service->user_id;
            $debtor->service_type = $service->type_service;
            $debtor->service_id = $id;
            $debtor->date_to_paid = date('Y-m-d');
            $debtor->amount = $service->writeoff_amount;
            $debtor->debtor = MessageToPaid::DEBTOR_YES;
            $debtor->save(false);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

}
