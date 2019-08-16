<?php

namespace app\models\service;

use app\models\addition\Addition;
use app\models\db\User;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%service}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property int $shop_id ID Магазина
 * @property int $type_service Тип услуги
 * @property int $type_serviceId ID Услуги на которую планируется списание
 * @property string $writeoff_date Дата списания
 * @property string $writeoff_amount Цена списания
 * @property int $quantity Количество
 * @property int $repeat Повторяющийся
 * @property int $completed Выполнен
 *
 * @property User $user
 */
class Service extends ActiveRecord {

    const TYPE_TARIFF = 1;
    const TYPE_ADDITION = 2;

    const REPEAT_FALSE = 0;
    const REPEAT_TRUE = 1;

    const COMPLETED_FALSE = 0;
    const COMPLETED_TRUE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%service}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'shop_id', 'type_service', 'type_serviceId', 'writeoff_date', 'writeoff_amount'], 'required'],
            [['user_id', 'shop_id', 'type_service', 'type_serviceId', 'quantity', 'repeat', 'completed'], 'integer'],
            [['writeoff_date'], 'date'],
            [['writeoff_amount'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'ID Бренда',
            'shop_id' => 'ID Магазина',
            'type_service' => 'Тип услуги',
            'type_serviceId' => 'ID Услуги на которую планируется списание',
            'writeoff_date' => 'Дата списания',
            'writeoff_amount' => 'Цена списания',
            'quantity' => 'Количество',
            'repeat' => 'Повторяющийся',
            'completed' => 'Выполнен',
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
    public function getShop() {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
    }

    /**
     * @param int $tariff_id
     * @param int $shop_id
     * @param int $user_id
     * @param int $old_id
     * @param bool $updateTariff
     *
     * @return bool
     */
    public static function saveTariff($tariff_id = 0, $shop_id = 0, $user_id = 0, $old_id = 0, $updateTariff = false) {
        if ($tariff_id != 0 && $shop_id != 0 && $user_id != 0) {
            if ($updateTariff && $old_id != 0) {
                $oldServiceTariff = Service::findOne(['user_id' => $user_id, 'shop_id' => $shop_id,
                    'type_service' => self::TYPE_TARIFF, 'type_serviceId' => $old_id, 'completed' => self::COMPLETED_FALSE]);

                if (!empty($oldServiceTariff)) {
                    $oldServiceTariff->repeat = self::REPEAT_FALSE;
                    $oldServiceTariff->save(false);
                }
            }

            $tariff = Tariff::find()->where(['id' => $tariff_id])->asArray()->limit(1)->one();

            if (!empty($tariff)) {
                $saveService = new Service();
                $saveService->user_id = $user_id;
                $saveService->shop_id = $shop_id;
                $saveService->type_service = self::TYPE_TARIFF;
                $saveService->type_serviceId = $tariff_id;
                $saveService->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                $saveService->writeoff_amount = $tariff['cost'];
                $saveService->quantity = 1;
                $saveService->repeat = self::REPEAT_TRUE;
                $saveService->completed = self::COMPLETED_FALSE;
                $saveService->save(false);

                return true;
            }
        }

        return false;
    }

    /**
     * @param int $addition_id
     * @param int $shop_id
     * @param int $quantity
     * @param int $user_id
     *
     * @return bool
     */
    public static function saveAddition($addition_id = 0, $shop_id = 0, $quantity = 1, $user_id = 0) {
        if ($addition_id != 0 && $shop_id != 0 && $user_id != 0) {
            $addition = Addition::find()->where(['id' => $addition_id])->asArray()->limit(1)->one();

            if (!empty($addition)) {
                $saveService = new Service();
                $saveService->user_id = $user_id;
                $saveService->shop_id = $shop_id;
                $saveService->type_service = self::TYPE_ADDITION;
                $saveService->type_serviceId = $addition_id;
                $saveService->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                $saveService->writeoff_amount = $addition['cost'];
                $saveService->quantity = $quantity;
                if ($addition['type']) {
                    $saveService->repeat = self::REPEAT_TRUE;
                } else {
                    $saveService->repeat = self::REPEAT_FALSE;
                }
                $saveService->completed = self::COMPLETED_FALSE;
                $saveService->save(false);

                return true;
            }
        }

        return false;
    }

    /**
     * @param int $user_id
     * @param int $shop_id
     * @param array $id
     *
     * @return bool
     */
    public static function updateAdditionFalse($user_id = 0, $shop_id = 0, $id = []) {
        if ($user_id != 0 && $shop_id != 0 && !empty($id)) {
            $oldServiceAdditions = Service::find()->where(['user_id' => $user_id, 'shop_id' => $shop_id,
                'type_service' => self::TYPE_ADDITION, 'type_serviceId' => $id, 'completed' => self::COMPLETED_FALSE])->all();

            if (!empty($oldServiceAdditions)) {
                foreach ($oldServiceAdditions as $addition) {
                    $addition->repeat = self::REPEAT_FALSE;
                    $addition->save(false);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * @param int $id
     * @param int $shop_id
     * @param int $quantity
     * @param int $user_id
     *
     * @return bool
     */
    public static function updateAddition($id = 0, $shop_id = 0, $quantity = 1, $user_id = 0) {
        if ($user_id != 0 && $shop_id != 0 && $id != 0) {
            $oldServiceAddition = Service::findOne(['user_id' => $user_id, 'shop_id' => $shop_id,
                'type_service' => self::TYPE_ADDITION, 'type_serviceId' => $id, 'completed' => self::COMPLETED_FALSE]);

            if (!empty($oldServiceAddition)) {
                $addition = Addition::find()->where(['id' => $id])->asArray()->limit(1)->one();

                if ($quantity > $oldServiceAddition->quantity) {
                    $oldServiceAddition->quantity = $quantity;
                    if ($addition['type']) {
                        $oldServiceAddition->repeat = self::REPEAT_TRUE;
                    } else {
                        $oldServiceAddition->repeat = self::REPEAT_FALSE;
                    }
                    $oldServiceAddition->save(false);
                } elseif ($quantity < $oldServiceAddition->quantity) {
                    $next_payment = new Service();
                    $next_payment->user_id = $user_id;
                    $next_payment->shop_id = $shop_id;
                    $next_payment->type_service = self::TYPE_ADDITION;
                    $next_payment->type_serviceId = $id;
                    $next_payment->writeoff_date = date('Y-m-d', strtotime($oldServiceAddition->writeoff_date . '+30 day'));
                    $next_payment->writeoff_amount = $addition['cost'];
                    $next_payment->quantity = $quantity;
                    if ($addition['type']) {
                        $next_payment->repeat = self::REPEAT_TRUE;
                    } else {
                        $next_payment->repeat = self::REPEAT_FALSE;
                    }
                    $next_payment->completed = self::COMPLETED_FALSE;
                    $next_payment->save(false);
                } else {
                    if ($addition['type']) {
                        $oldServiceAddition->repeat = self::REPEAT_TRUE;
                    } else {
                        $oldServiceAddition->repeat = self::REPEAT_FALSE;
                    }
                    $oldServiceAddition->save(false);
                }

                return true;
            } else {
                self::saveAddition($id, $shop_id, $quantity, $user_id);

                return true;
            }
        }

        return false;
    }
}
