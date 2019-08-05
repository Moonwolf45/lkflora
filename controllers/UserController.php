<?php

namespace app\controllers;

use app\models\addition\Addition;
use app\models\shops\Shops;
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
        $shops = Shops::find()->with('tariff')->where(['user_id' => Yii::$app->user->id])->asArray()->all();
        $tariffs = Tariff::find()->asArray()->all();
        $additions = Addition::find()->asArray()->all();

        $modelShop = new Shops();
        if ($modelShop->load(Yii::$app->request->post()) && $modelShop->save()) {
            $additionArr = Addition::find()->where(['id' => $modelShop->addition])->asArray()->all();
            foreach ($modelShop->addition as $key => $addition) {
                $modelShop->link('addition', $additionArr[$key]);
            }

            return $this->refresh();
        }

        return $this->render('index', compact('shops', 'modelShop', 'modelUpdateShop', 'tariffs',
            'additions'));
    }

    /**
     * Просто action для обновления тарифа магазина
     *
     * @return \yii\web\Response
     */
    public function actionUpdateShop() {
        $updateShop = Yii::$app->request->post();
        $shop = Shops::findOne($updateShop['Shops']['id']);
        $shop->tariff_id = $updateShop['Shops']['tariff_id'];
        $shop->save();

        return $this->redirect(['/user/index']);
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
