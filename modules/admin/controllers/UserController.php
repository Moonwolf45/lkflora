<?php

namespace app\modules\admin\controllers;

use app\models\db\UserSettings;
use Yii;
use app\models\db\User;
use app\models\db\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Контроллер пользователя
 */
class UserController extends Controller
{
    /** @var string */
    public $layout = 'admin';

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

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $userSettingsData = UserSettings::findOne(['user_id' => $id]);

        return $this->render('view', [
            'model'            => $this->findModel($id),
            'userSettingsData' => $userSettingsData,
        ]);
    }

    /**
     * Ф-ция генерации пароля. Стоковое кол-во цифр: 10 + можно задать при вызове ф-ции
     * (пы.сы.: нет смысла переносить в модель)
     *
     * @param int $number
     *
     * @return string
     */
    public function generate_password($number = 10)
    {
        $arr = ['a', 'b', 'c', 'd', 'e', 'f',
                'g', 'h', 'i', 'j', 'k', 'l',
                'm', 'n', 'o', 'p', 'r', 's',
                't', 'u', 'v', 'x', 'y', 'z',
                'A', 'B', 'C', 'D', 'E', 'F',
                'G', 'H', 'I', 'J', 'K', 'L',
                'M', 'N', 'O', 'P', 'R', 'S',
                'T', 'U', 'V', 'X', 'Y', 'Z',
                '1', '2', '3', '4', '5', '6',
                '7', '8', '9', '0'];

        $pass = "";
        for ($i = 0; $i < $number; $i++) {

            $index = rand(0, count($arr) - 1);
            $pass .= $arr[$index];
        }

        return $pass;
    }

    /**
     * Создание пользователя через админку
     *
     * @return string|\yii\web\Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $model = new User;

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 10;
            $model->balance = 0;
            $model->email = Yii::$app->request->post()['User']['email'];
            $model->doc_num = Yii::$app->request->post()['User']['doc_num'];
            $pass = $this->generate_password();
            $model->password_hash = $pass;
            $model->sendMailForNewUser();
            $model->password_hash = Yii::$app->security->generatePasswordHash($model->password_hash);

            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
