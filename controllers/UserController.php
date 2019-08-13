<?php

namespace app\controllers;

use app\models\addition\Addition;
use app\models\payments\NewPaid;
use app\models\payments\Payments;
use app\models\service\Service;
use app\models\shops\Shops;
use app\models\ShopsAddition;
use app\models\tariff\Tariff;
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

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->identity->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->andWhere(['!=', 'invoice_number', ''])->orderBy('id DESC')
            ->limit(3)->asArray()->all();

        $modelShop = new Shops();
        $service = new Service();

        if ($modelShop->load(Yii::$app->request->post()) && $modelShop->save()) {
            $service->saveTariff($modelShop->tariff_id, $modelShop['id'], Yii::$app->user->identity->id);
            foreach ($modelShop->addition as $addition_one) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $modelShop['id'];
                $shopAddition->addition_id = $addition_one;
                $shopAddition->quantity = 1;
                $shopAddition->save();

                $service->saveAddition($addition_one, $modelShop['id'], 1, Yii::$app->user->identity->id);
            }

            return $this->refresh();
        }

        return $this->render('index', compact('shops', 'modelShop', 'tariffs',
            'additions', 'invoice'));
    }

    /**
     * Просто action для обновления тарифа магазина
     *
     * @return \yii\web\Response
     */
    public function actionUpdateShop() {
        $updateShop = Yii::$app->request->post();
        $shop = Shops::findOne($updateShop['Shops']['id']);
        $oldTariff_id = $shop->tariff_id;
        $shop->tariff_id = $updateShop['Shops']['tariff_id'];
        $shop->save();

        $service = new Service();
        $service->saveTariff($updateShop['Shops']['tariff_id'], $updateShop['Shops']['id'],
            Yii::$app->user->identity->id, $oldTariff_id, true);

        return $this->redirect(['/user/index']);
    }

    /**
     * Просто action для изменения услуг магазина
     *
     * @return \yii\web\Response
     */
    public function actionShopEditService() {
        $shopEditService = Yii::$app->request->post();
        $service = new Service();

        $delete_id = [];
        $additions = ShopsAddition::find()->where(['shop_id' => $shopEditService['Shops']['id']])->asArray()->all();
        foreach ($additions as $addition) {
            $delete_id[] = $addition['addition_id'];
        }
        $service->updateAdditionFalse(Yii::$app->user->identity->id, $shopEditService['Shops']['id'], $delete_id);
        ShopsAddition::deleteAll(['shop_id' => $shopEditService['Shops']['id']]);

        foreach ($shopEditService['Shops']['addition'] as $key => $service) {
            if ($service != 0) {
                $shopAddition = new ShopsAddition();
                $shopAddition->shop_id = $shopEditService['Shops']['id'];
                $shopAddition->addition_id = $key;
                $shopAddition->quantity = $shopEditService['Shops']['quantityArr'][$key];
                $shopAddition->save();

                $service->updateAddition($key, $shopEditService['Shops']['id'],
                    $shopEditService['Shops']['quantityArr'][$key], Yii::$app->user->identity->id);
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
        $payments = Payments::find()->where(['user_id' => Yii::$app->user->identity->id, 'type' => Payments::TYPE_WRITEOFF,
            'status' => Payments::STATUS_PAID])->with('shop')->with('tariff')->with('addition')
            ->orderBy(['id' => SORT_DESC])->asArray()->all();

        $deposit = Payments::find()->where(['user_id' => Yii::$app->user->identity->id, 'type' => Payments::TYPE_REFILL,
            'status' => Payments::STATUS_PAID])->orderBy(['id' => SORT_DESC])->limit(3 * $d)->asArray()->all();

        $invoice = Payments::find()->where(['user_id' => Yii::$app->user->identity->id, 'type' => Payments::TYPE_REFILL])
            ->andWhere(['!=', 'invoice_number', ''])->orderBy(['id' => SORT_DESC])->limit(3 * $i)->asArray()->all();

        return $this->render('payment', compact('d', 'i', 'modelPaid', 'payments', 'deposit', 'invoice'));
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

        $content = $this->renderPartial('_reportPDF');

        $date = date('d.m.Y');
        $number = 111;
        $pdf = Yii::$app->pdf;
        $mpdf = $pdf->api;
        $mpdf->SetHeader('Счёт №' . $number . ' от ' . $date);
        $mpdf->SetTitle('Счёт №' . $number . ' от ' . $date);
        $mpdf->WriteHtml($content);
        $filename = 'Счёт №' . $number . ' от ' . $date . '.pdf';
        return $mpdf->Output($filename, 'D');
    }

    /**
     * Оплата с карты
     *
     * @return string
     */
    public function actionPaidCard() {
        echo "<pre>";
        print_r($_REQUEST);
        echo "</pre>";
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
