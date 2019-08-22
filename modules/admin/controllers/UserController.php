<?php

namespace app\modules\admin\controllers;

use app\models\db\UserSettings;
use app\models\shops\Shops;
use Yii;
use app\models\db\User;
use app\models\db\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Контроллер пользователя
 */
class UserController extends Controller {

    /**
     * Настройка доступа
     * todo: в данный момент доступ открыт всем авторизированным пользователям!
     *
     * @return array
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
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
    public function actionIndex() {
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
    public function actionView($id) {
        $model = User::find()->joinWith('userSetting')->where(['user.id' => $id])->limit(1)->one();
        $shops = Shops::find()->joinWith('additions')->joinWith('tariff')->where(['user_id' => $id])
            ->asArray()->all();

        $model->shops = $shops;

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Ф-ция генерации пароля. Стоковое кол-во цифр: 10 + можно задать при вызове ф-ции
     * (пы.сы.: нет смысла переносить в модель)
     *
     * @param int $number
     *
     * @return string
     */
    public function generate_password($number = 10) {
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
    public function actionCreate() {
        $model = new User;

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 10;
            $model->balance = 0;
            $model->email = Yii::$app->request->post()['User']['email'];
            $model->phone = Yii::$app->request->post()['User']['phone'];
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
    public function actionUpdate($id) {
        $model = User::find()->joinWith('userSetting')->where(['user.id' => $id])->limit(1)->one();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $user_set = UserSettings::find()->where(['user_id' => $id])->limit(1)->one();
            $user_set->doc_num = $model->doc_num;
            $user_set->type_org = $model->type_org;
            $user_set->name_org = $model->name_org;
            $user_set->ur_addr_org = $model->ur_addr_org;
            $user_set->ogrn = $model->ogrn;
            $user_set->inn = $model->inn;
            $user_set->kpp = $model->kpp;
            $user_set->bik_banka = $model->bik_banka;
            $user_set->name_bank = $model->name_bank;
            $user_set->kor_schet = $model->kor_schet;
            $user_set->rass_schet = $model->rass_schet;
            $user_set->save();

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
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return User|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id) {
        if (($model = User::find()->where(['id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
