<?php

namespace app\models;

use app\models\addition\Addition;
use app\models\tariff\Tariff;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tariff_addition}}".
 *
 * @property int $tariff_id
 * @property int $addition_id
 * @property int $quantity Количество
 *
 * @property Addition $addition
 * @property Tariff $tariff
 */
class TariffAddition extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%tariff_addition}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['tariff_id', 'addition_id'], 'required'],
            [['tariff_id', 'addition_id', 'quantity'], 'integer'],
            [['tariff_id', 'addition_id'], 'unique', 'targetAttribute' => ['tariff_id', 'addition_id']],
            [['addition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addition::class,
                'targetAttribute' => ['addition_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::class,
                'targetAttribute' => ['tariff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'tariff_id' => 'Tariff ID',
            'addition_id' => 'Addition ID',
            'quantity' => 'Количество',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddition() {
        return $this->hasOne(Addition::class, ['id' => 'addition_id'])->indexBy('id');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTariff() {
        return $this->hasOne(Tariff::class, ['id' => 'tariff_id']);
    }
}
