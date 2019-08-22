<?php

namespace app\models\db;

use app\models\shops\Shops;
use app\models\traits\MailToUserTrait;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string  $company_name
 * @property string  $password_hash
 * @property string  $password_reset_token
 * @property string  $verification_token
 * @property string  $email
 * @property string  $phone
 * @property string  $balance
 * @property string  $avatar
 * @property string  $auth_key
 * @property integer $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $password write-only password
 *
 * @property UserSettings $userSetting
 *
 * @property string $doc_num     Номер договора
 * @property string $type_org    Тип организации
 * @property string $name_org    Название организации
 * @property string $ur_addr_org Юр адрес организации
 * @property string $ogrn        ОГРН
 * @property string $inn         ИНН
 * @property string $kpp         КПП
 * @property string $bik_banka   БИК Банка
 * @property string $name_bank   Название банка
 * @property string $kor_schet   Кор счет
 * @property string $rass_schet  Рассчетный счет
 */
class User extends ActiveRecord implements IdentityInterface {
    use MailToUserTrait;

    public $passForMail;
    public $shops;

    public $doc_num;
    public $type_org;
    public $name_org;
    public $ur_addr_org;
    public $ogrn;
    public $inn;
    public $kpp;
    public $bik_banka;
    public $name_bank;
    public $kor_schet;
    public $rass_schet;

    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 0;
    const ROLE_ADMIN = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * Поиск юзера по e-mail
     *
     * @param $email
     *
     * @return null|static
     */
    public static function findByEmail($email) {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMIN]],
            [['phone', 'doc_num', 'type_org', 'name_org', 'ur_addr_org', 'ogrn', 'inn', 'kpp', 'bik_banka', 'name_bank',
                'kor_schet', 'rass_schet'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @param mixed $token
     * @param null  $type
     *
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Поиск пользователя по company_name
     *
     * @param string $company_name
     *
     * @return static|null
     */
    public static function findByCompany_name($company_name){
        return static::findOne(['company_name' => $company_name, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Поиск юзера по токену восстановления пароля
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        return static::findOne(['password_reset_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne(['verification_token' => $token, 'status' => self::STATUS_INACTIVE]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Валидация пароля
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генератор хеша пароля
     *
     * @param $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Генератор нового токена восстановления пароля
     *
     * @throws \yii\base\Exception
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws \yii\base\Exception
     */
    public function generateEmailVerificationToken() {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Очистка токена восстановления пароля
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'email' => 'E-mail пользователя',
            'phone' => 'Номер телефона',
            'role' => 'Роль',
        ];
    }

    /**
     * Отправка письма пользователю, созданному из админки
     *
     * @return string
     */
    public function sendMailForNewUser() {
        $this->sendMailToUser($this->email, 'newUser', 'Для вас создана учетная запись на сайте Florapoint',
            ['email' => $this->email, 'password_hash' => $this->password_hash]);

        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShops() {
        return $this->hasMany(Shops::class, ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSetting() {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }
}
