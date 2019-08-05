<?php

namespace app\models;

use app\models\addition\Addition;
use app\models\shops\Shops;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%shops_addition}}".
 *
 * @property int $shop_id
 * @property int $addition_id
 *
 * @property Addition $addition
 * @property Shops $shop
 */
class ShopsAddition extends ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%shops_addition}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shop_id', 'addition_id'], 'required'],
            [['shop_id', 'addition_id'], 'integer'],
            [['shop_id', 'addition_id'], 'unique', 'targetAttribute' => ['shop_id', 'addition_id']],
            [['addition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Addition::class,
                'targetAttribute' => ['addition_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shops::class,
                'targetAttribute' => ['shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'shop_id' => 'Shop ID',
            'addition_id' => 'Addition ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddition() {
        return $this->hasOne(Addition::class, ['id' => 'addition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShop() {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
    }
}
