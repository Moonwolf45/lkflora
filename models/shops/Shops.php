<?php

namespace app\models\shops;

use app\models\db\User;
use app\models\tariff\Tariff;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shops}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $address Адрес магазина
 * @property string $version Версия продукта
 * @property int $tariff_id Привязка к тарифу
 * @property int $user_id Привязка к пользователю
 *
 * @property User $user
 * @property Tariff $tariff
 */
class Shops extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%shops}}';
    }

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['address', 'version', 'tariff_id', 'user_id'], 'required'],
            [['version', 'tariff_id', 'user_id'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::class,
                'targetAttribute' => ['tariff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(){
        return [
            'id' => 'ID',
            'address' => 'Адрес магазина',
            'version' => 'Версия продукта',
            'tariff_id' => 'Привязка к тарифу',
            'user_id' => 'Привязка к бренду',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff() {
        return $this->hasOne(Tariff::class, ['id' => 'tariff_id']);
    }

    const VERSION_LIGHT = 1;
    const VERSION_BASIC = 2;
    const VERSION_EXTRA = 3;

    public static function getVersion($i = null) {
        $array = [
            self::VERSION_LIGHT => 'Light',
            self::VERSION_BASIC => 'Basic',
            self::VERSION_EXTRA => 'Extra',
        ];
        return $i === null ? $array : (isset($array[$i]) ? $array[$i] : false);
    }
}
