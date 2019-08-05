<?php

namespace app\models\addition;

use app\models\services\Services;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use app\models\TariffAddition;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%addition}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name Название
 * @property string $cost Стоимость
 * @property string $about Описание
 * @property int $type Тип
 *
 * @property Services[] $services
 * @property TariffAddition[] $tariffAdditions
 * @property Tariff[] $tariffs
 */
class Addition extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%addition}}';
    }

    /**
     * @return array
     */
    public function behaviors(){
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
            [['name', 'cost', 'type'], 'required'],
            [['type'], 'integer'],
            [['cost'], 'number'],
            [['name', 'about'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'cost' => 'Стоимость',
            'about' => 'Описание',
            'type' => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServices() {
        return $this->hasMany(Services::class, ['addition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTariffs() {
        return $this->hasMany(Tariff::class, ['id' => 'tariff_id'])->viaTable('{{%tariff_addition}}', ['addition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getShops() {
        return $this->hasMany(Shops::class, ['id' => 'shop_id'])->viaTable('{{%shops_addition}}', ['addition_id' => 'id']);
    }
}
