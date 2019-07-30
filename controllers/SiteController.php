<?php

namespace app\controllers;

use app\models\AuthForm;
use app\models\db\User;
use app\models\form\ResetPasswordConfirmForm;
use app\models\form\ResetPasswordForm;
use app\models\RegistrationForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller {
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Стартовая форма авторизации
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new AuthForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/user/index']);
        } else {
            $model->password = '';

            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Регистрация
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionRegistration()
    {
        exit; //todo: временно, отключенный контроллер регистрации
        $model = new RegistrationForm;

        $model->load(Yii::$app->request->post());
        if ($user = $model->save()) {
            if (Yii::$app->getUser()->login($user)) {
                $this->redirect(['/user/index']);
            }
        } else {
            $errors = $model->errors;
        }


        return $this->render('registration', ['model' => $model]);
    }

    /**
     * Логин - заглушка
     *
     * @return bool
     */
    public function actionLogin()
    {
        $this->redirect('/site/index');

        return true;
    }

    /**
     * Восстановление пароля
     */
    public function actionResetPassword() {
        $this->layout = 'main-reset-password';

        $model = new ResetPasswordForm;

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            return $this->render('reset-password--success');
        }

        return $this->render('reset-password', ['model' => $model]);
    }

    /**
     * Ввод нового пароля
     *
     * @param string $token
     *
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionResetPasswordConfirm($token = '') {
        $this->layout = 'main-reset-password';

        $user = User::findByPasswordResetToken($token);

        if (!$user) {
            return $this->render('reset-password-confirm--error');
        }

        $model = new ResetPasswordConfirmForm;

        if ($model->load(Yii::$app->request->post()) && $model->update($user)) {
            return $this->render('reset-password-confirm--success');
        }

        return $this->render('reset-password-confirm', [
            'model' => $model,
        ]);
    }

    /**
     * Выход
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
