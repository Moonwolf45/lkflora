<?php

namespace app\controllers;

use app\models\addition\Addition;
use app\models\payments\NewPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\shops\Shops;
use app\models\ShopsAddition;
use app\models\tariff\Tariff;
use app\models\tickets\Tickets;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\AuthForm;

use app\models\db\User;
use app\models\db\UserSettings;
use app\models\form\UserProfileForm;
use app\models\form\UserSettingsForm;
use app\models\form\UploadAvatarForm;
use yii\web\UploadedFile;

class UserController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index', 'registration', 'auth'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                    [
                        'actions' => ['auth', 'registration'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Главная страница пользователя
     *
     * @return string
     */
    public function actionIndex() {
        $shops = Shops::find()->joinWith('tariff')->joinWith('additions')
            ->where(['user_id' => Yii::$app->user->id])->asArray()->all();

        $tariffs = Tariff::find()->asArray()->all();
        $additions = Addition::find()->asArray()->all();

        $date_month = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, 31, date("Y")));
        $monthly_payment = Service::find()->where(['user_id' => Yii::$app->user->id, 'completed' => Service::COMPLETED_FALSE])
            ->andWhere(['<=', 'writeoff_date', $date_month])->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])
            ->limit(3)->asArray()->all();

        $tickets = Tickets::find()->where(['user_id' => Yii::$app->user->id, 'status' => Tickets::STATUS_OPEN_TICKET])
            ->with('last-tickets-text')->limit(3)->asArray()->all();

        $modelShop = new Shops();
        $newTicket = new Tickets();

        if ($modelShop->load(Yii::$app->request->post()) && $modelShop->save()) {
            Service::saveTariff($modelShop->tariff_id, $modelShop->id, Yii::$app->user->id);
            foreach ($modelShop->addition as $addition_one) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $modelShop->id;
                $shopAddition->addition_id = $addition_one;
                $shopAddition->quantity = 1;
                $shopAddition->save();

                Service::saveAddition($addition_one, $modelShop->id, 1, Yii::$app->user->id);
            }

            return $this->refresh();
        }

        if ($newTicket->load(Yii::$app->request->post())) {
            echo "<pre>";
            print_r($newTicket);
            echo "</pre>";

//            $newTicket->status = Tickets::STATUS_OPEN_TICKET;
//            $newTicket->new_text = false;
//            $newTicket->save();

//            return $this->refresh();
        }

        return $this->render('index', compact('shops', 'modelShop', 'tariffs',
            'additions', 'invoice', 'newTicket', 'tickets', 'monthly_payment'));
    }

    /**
     *
     * Просто action для обновления тарифа магазина
     *
     * @return \yii\web\Response
     */
    public function actionUpdateShop() {
        $updateShop = Yii::$app->request->post();

        $shop = Shops::findOne($updateShop['Shops']['id']);
        $oldTariff_id = $shop->tariff_id;
        $shop->tariff_id = $updateShop['Shops']['tariff_id'];
        $shop->save(false);

        Service::saveTariff($updateShop['Shops']['tariff_id'], $updateShop['Shops']['id'], Yii::$app->user->id, $oldTariff_id, true);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для изменения услуг магазина
     *
     * @return \yii\web\Response
     */
    public function actionShopEditService() {
        $shopEditService = Yii::$app->request->post();

        $delete_id = [];
        $additions = ShopsAddition::find()->where(['shop_id' => $shopEditService['Shops']['id']])->asArray()->all();
        foreach ($additions as $addition) {
            $delete_id[] = $addition['addition_id'];
        }
        Service::updateAdditionFalse(Yii::$app->user->id, $shopEditService['Shops']['id'], $delete_id);
        ShopsAddition::deleteAll(['shop_id' => $shopEditService['Shops']['id']]);

        foreach ($shopEditService['Shops']['addition'] as $key => $service) {
            if ($service != 0) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $shopEditService['Shops']['id'];
                $shopAddition->addition_id = $key;
                $shopAddition->quantity = $shopEditService['Shops']['quantityArr'][$key];
                $shopAddition->save();

                Service::updateAddition($key, $shopEditService['Shops']['id'], $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->id);
            }
        }

        return $this->redirect(['/user/index']);
    }

    /**
     * Страница детализации баланса
     *
     * @param $d
     * @param $i
     *
     * @return string
     */
    public function actionPayment($d, $i) {
        if ($d == '') {
            $d = 1;
        }

        if ($i == '') {
            $i = 1;
        }

        $modelPaid = new NewPaid();
        $payments = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_WRITEOFF,
            'status' => Payments::STATUS_PAID])->with('shop')->with('tariff')->with('addition')
            ->orderBy(['id' => SORT_DESC])->asArray()->all();

        $deposit = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->limit(3 * $d)->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->id, 'type' => Payments::TYPE_REFILL])
            ->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])->limit(3 * $i)->asArray()->all();

        $maxPaymentId = Payments::getMaxId();

        return $this->render('payment', compact('d', 'i', 'modelPaid', 'payments', 'deposit', 'invoice',
            'maxPaymentId'));
    }

    /**
     * Выставление счета
     *
     * @return string
     */
    public function actionSavePdf() {
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";

        $maxPaymentNumber = Payments::getMaxNumberSchet();
        $date = date('d.m.Y');
        $number = $maxPaymentNumber['invoice_number'] + 1;
        $pdfFile = Yii::$app->pdf;
        $mpdf = $pdfFile->api;
        $mpdf->SetHeader('Счёт №' . $number . ' от ' . $date);
        $mpdf->SetTitle('Счёт №' . $number . ' от ' . $date);

        $content = $this->renderPartial('_schetPDF', ['number' => $number, 'date' => $date]);
        $mpdf->WriteHtml($content);
        $filename = 'Счёт №' . $number . ' от ' . $date . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    /**
     * Оплата с карты
     *
     * @param $id
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadAct($id) {
        if ($id != '') {
            $model = Payments::find()->where(['id' => $id])->limit(1)->one();

           if (!empty($model)) {
               $pdf = Yii::$app->pdf;
               $mpdf = $pdf->api;
               $content = $this->renderPartial('_actPDF', ['model' => $model]);
               $mpdf->WriteHtml($content);

               $number = $model['invoice_number'];
               $date = Yii::$app->formatter->asDate($model['date']);
               $mpdf->SetHeader('Акт №' . $number . ' от ' . $date);
               $mpdf->SetTitle('Акт №' . $number . ' от ' . $date);
               $filename = 'Акт №' . $number . ' от ' . $date . '.pdf';
               return $mpdf->Output($filename, 'D');
           }
        }
    }

    /**
     * Страница тех. потдержки
     *
     * @return string
     */
    public function actionTickets() {

        return $this->render('tickets');
    }

    /**
     * Анкета пользователя
     * Данные организации, банка
     *
     * @return string
     */
    public function actionAccount() {
        if (Yii::$app->user->isGuest) {
            $this->render('auth');
        }

        $model = new UserSettingsForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/user/index']);
        }

        $model->loadData();

        $userId = Yii::$app->user->id;
        $profileSettings = UserSettings::findOne(['user_id' => $userId]);

        return $this->render('account', ['model' => $model, 'profileSettings' => $profileSettings]);
    }

    /**
     * Авторизация
     *
     * @return string|\yii\web\Response
     */
    public function actionAuth() {
        $model = new AuthForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/user/index']);
        } else {
            $model->password = '';

            return $this->render('auth', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Настроки
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionSettings() {
        if (Yii::$app->user->isGuest) {
            $this->render('auth');
        }

        $userId = Yii::$app->user->id;
        $userSettingsData = UserSettings::findOne(['user_id' => $userId]);
        $profileSettings = User::findOne(['id' => $userId]);

        $model = new UserProfileForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->image) {
                $currentUserId = Yii::$app->user->id;
                $user = User::findOne(['id' => $currentUserId]);
                $image = $model->upload();
                if ($image != false) {
                    $user->avatar = $image;
                    $user->save();
                }
            }

            return $this->redirect(['/user/settings']);
        }

        $model->loadData();

        return $this->render('settings', ['userSettingsData' => $userSettingsData,
            'profileSettings' => $profileSettings, 'model' => $model]);
    }

    /**
     * Загрузка аватарки профиля
     *
     * @return string|\yii\web\Response
     */
    public function actionAvaUpload() {
        $model = new UploadAvatarForm;

        if (Yii::$app->request->isPost) {
            $currentUserId = Yii::$app->user->id;
            $user = User::findOne(['id' => $currentUserId]);

            $model->image = UploadedFile::getInstance($model, 'image');
            $image = $model->upload();
            if ($image != false) {
                $user->avatar = $image;
                $user->save();
            }

            return $this->redirect(['/user/ava-upload']);
        }

        return $this->render('avaUpload', ['model' => $model]);
    }

    /**
     * Выход
     *
     * @return \yii\web\Response
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
