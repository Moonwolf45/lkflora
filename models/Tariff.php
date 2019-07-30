<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tariff".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $cost Стоимость обслуживания (ежемесячно)
 * @property string $about Описание
 * @property int $drop Параметр который запрещает подключать тариф ниже данного
 * @property int $status Статус
 * @property string $term Срок действия тарифа, после которого он не может быть повторно подключен
 */
class Tariff extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'tariff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'cost', 'term'], 'required'],
            [['drop', 'status'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['cost'], 'number'],
            [['about'], 'string'],
            [['term'], 'date'],
            [['name'], 'string', 'max' => 255],
        ];
    }

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
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'cost' => 'Стоимость обслуживания (ежемесячно)',
            'about' => 'Описание',
            'drop' => 'Подключение тарифа ниже данного',
            'status' => 'Статус',
            'term' => 'Срок действия тарифа',
        ];
    }
}
