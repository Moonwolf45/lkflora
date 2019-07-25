<?php

namespace app\controllers;

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

class UserController extends Controller
{
    public $layout = 'user';

    public function behaviors()
    {
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
     * Данные организации, банка
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->render('auth');
        }

        $model = new UserSettingsForm;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['user/index']);
        }

        $model->loadData();

        $userId = Yii::$app->user->id;
        $profileSettings = UserSettings::findOne(['user_id' => $userId]);

        return $this->render('index', [
            'model'           => $model,
            'profileSettings' => $profileSettings,
        ]);
    }

    /**
     * Авторизация todo
     *
     * @return string|\yii\web\Response
     */
    public function actionAuth()
    {
        $model = new AuthForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['user/index']);
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
    public function actionSettings()
    {
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
                $model->upload();
                $currentUserId = Yii::$app->user->id;
                $user = User::findOne(['id' => $currentUserId]);
                $user->avatar = 'upload/' . $model->image->name;
                $user->save();
            }

            return $this->redirect(['user/settings']);
        }

        $model->loadData();

        return $this->render('settings', [
            'userSettingsData' => $userSettingsData,
            'profileSettings'  => $profileSettings,
            'model'            => $model,
        ]);
    }

    /**
     * Загрузка аватарки профиля
     *
     * @return string|\yii\web\Response
     */
    public function actionAvaUpload()
    {
        $model = new UploadAvatarForm;

        if (Yii::$app->request->isPost) {
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();

            $currentUserId = Yii::$app->user->id;
            $user = User::findOne(['id' => $currentUserId]);
            $user->avatar = 'upload/' . $model->image->name;
            $user->save();

            return $this->redirect(['user/ava-upload']);
        }

        return $this->render('avaUpload', [
            'model' => $model,
        ]);
    }

    /**
     * Выход
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}