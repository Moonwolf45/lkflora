<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use Yii;

/**
 * Админ Контроллер
 *
 * @package app\modules\admin\controllers
 */
class DefaultController extends Controller
{
    /**
     * Настройка доступа
     * todo: в данный момент доступ открыт всем авторизированным пользователям!
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /** @var string */
    public $layout = 'admin';

    /**
     * Главная админки
     *
     * @return bool|string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
