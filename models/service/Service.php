<?php

namespace app\models\service;

use app\models\addition\Addition;
use app\models\db\User;
use app\models\shops\Shops;
use app\models\tariff\Tariff;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "{{%service}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property int $shop_id ID Магазина
 * @property int $type_service Тип услуги
 * @property int $type_serviceId ID Услуги на которую планируется списание
 * @property string $connection_date Дата подключения
 * @property string $writeoff_date Дата списания
 * @property string $writeoff_amount Цена списания
 * @property int $agree Подтвержден
 * @property int $repeat_service Повторяющийся
 * @property int $deleted Удалён
 *
 * @property User $user
 */
class Service extends ActiveRecord {

    const TYPE_TARIFF = 1;
    const TYPE_ADDITION = 2;

    const REPEAT_FALSE = 0;
    const REPEAT_TRUE = 1;

    const AGREE_FALSE = 0;
    const AGREE_TRUE = 1;

    const DELETED_FALSE = 0;
    const DELETED_TRUE = 1;

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
            [['user_id', 'shop_id', 'type_service', 'type_serviceId', 'connection_date', 'writeoff_date', 'writeoff_amount', 'agree'], 'required'],
            [['user_id', 'shop_id', 'type_service', 'type_serviceId'], 'integer'],
            [['repeat_service', 'agree', 'deleted'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['writeoff_date', 'connection_date'], 'date'],
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
            'connection_date' => 'Дата подключения',
            'writeoff_date' => 'Дата списания',
            'writeoff_amount' => 'Цена списания',
            'agree' => 'Подтвержден',
            'repeat_service' => 'Повторяющийся',
            'deleted' => 'Удалён',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getShop() {
        return $this->hasOne(Shops::class, ['id' => 'shop_id']);
    }

    public function getTariff() {
        return $this->hasOne(Tariff::class, ['id' => 'type_serviceId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditions() {
        return $this->hasMany(Addition::class, ['id' => 'type_serviceId']);
    }

    /**
     * Сохранение и изменение тарифа
     *
     * @param int $tariff_id
     * @param int $shop_id
     * @param int $user_id
     * @param int $old_id
     *
     * @return bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public static function saveTariff($tariff_id = 0, $shop_id = 0, $user_id = 0, $old_id = 0) {
        if ($tariff_id != 0 && $shop_id != 0 && $user_id != 0) {
            if ($old_id != 0) {
                $oldServiceTariff = Service::findOne(['user_id' => $user_id, 'shop_id' => $shop_id,
                    'type_service' => self::TYPE_TARIFF, 'type_serviceId' => $old_id, 'agree' => self::AGREE_TRUE,
                    'deleted' => self::DELETED_FALSE]);

                if (!empty($oldServiceTariff)) {
                    $oldServiceTariff->delete();
                }
            }

            $tariff = Tariff::find()->where(['id' => $tariff_id])->asArray()->limit(1)->one();

            if (!empty($tariff)) {
                $saveService = new Service();
                $saveService->user_id = $user_id;
                $saveService->shop_id = $shop_id;
                $saveService->type_service = self::TYPE_TARIFF;
                $saveService->type_serviceId = $tariff_id;
                $saveService->connection_date = date("Y-m-d");
                $saveService->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                $saveService->writeoff_amount = $tariff['cost'];
                $saveService->agree = self::AGREE_FALSE;
                $saveService->repeat_service = self::REPEAT_TRUE;
                $saveService->deleted = self::DELETED_FALSE;
                $saveService->save(false);

                return true;
            }
        }

        return false;
    }

    /**
     * Сохранение доп. услуги
     *
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
                for ($i = 0; $i < $quantity; $i++) {
                    $saveService = new Service();
                    $saveService->user_id = $user_id;
                    $saveService->shop_id = $shop_id;
                    $saveService->type_service = self::TYPE_ADDITION;
                    $saveService->type_serviceId = $addition_id;
                    $saveService->connection_date = date("Y-m-d");
                    $saveService->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                    $saveService->writeoff_amount = $addition['cost'];
                    $saveService->agree = self::AGREE_FALSE;
                    if ($addition['type']) {
                        $saveService->repeat_service = self::REPEAT_TRUE;
                    } else {
                        $saveService->repeat_service = self::REPEAT_FALSE;
                    }
                    $saveService->deleted = self::DELETED_FALSE;
                    $saveService->save(false);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Обновление доп. услуги
     *
     * @param int $id
     * @param int $shop_id
     * @param int $quantity
     * @param int $user_id
     *
     * @return bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public static function updateAddition($id = 0, $shop_id = 0, $quantity = 1, $user_id = 0) {
        if ($user_id != 0 && $shop_id != 0 && $id != 0) {
            $oldServiceAdditions = Service::find()->where(['user_id' => $user_id, 'shop_id' => $shop_id,
                'agree' => self::AGREE_TRUE, 'type_service' => self::TYPE_ADDITION, 'type_serviceId' => $id])->all();

            if (!empty($oldServiceAdditions)) {
                $addition = Addition::find()->where(['id' => $id])->asArray()->limit(1)->one();
                $countOldService = count($oldServiceAdditions);

                if ($quantity > $countOldService) {
                    $new_quantity = $quantity - $countOldService;
                    for ($i = 0; $i < $new_quantity; $i++) {
                        $next_payment = new Service();
                        $next_payment->user_id = $user_id;
                        $next_payment->shop_id = $shop_id;
                        $next_payment->type_service = self::TYPE_ADDITION;
                        $next_payment->type_serviceId = $id;
                        $next_payment->connection_date = date("Y-m-d");
                        $next_payment->writeoff_date = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 30, date("Y")));
                        $next_payment->writeoff_amount = $addition['cost'];
                        $next_payment->agree = self::AGREE_FALSE;
                        if ($addition['type']) {
                            $next_payment->repeat_service = self::REPEAT_TRUE;
                        } else {
                            $next_payment->repeat_service = self::REPEAT_FALSE;
                        }
                        $next_payment->deleted = self::DELETED_FALSE;
                        $next_payment->save(false);
                    }
                } elseif ($quantity < $countOldService) {
                    $new_quantity = $countOldService - $quantity;
                    $i = 0;
                    foreach ($oldServiceAdditions as $oldServiceAddition) {
                        if ($i < $new_quantity) {
                            $oldServiceAddition->delete();
                            $i++;
                        }
                    }
                }

                if ($quantity != $countOldService) {
                    $shops = Shops::findOne(['id' => $shop_id]);
                    $shops->on_check = Shops::ON_CHECK_TRUE;
                    $shops->save(false);
                }

                return true;
            } else {
                self::saveAddition($id, $shop_id, $quantity, $user_id);
                return true;
            }
        }

        return false;
    }

    /**
     * Удаление доп. услуги и тарифа
     *
     * @param int $user_id
     * @param int $shop_id
     *
     * @return bool
     */
    public static function deleteShopService($user_id = 0, $shop_id = 0) {
        if ($user_id != 0 && $shop_id != 0) {
            $oldServiceAdditions = Service::find()->where(['user_id' => $user_id, 'shop_id' => $shop_id,
                'agree' => self::AGREE_TRUE, 'deleted' => self::DELETED_FALSE])->all();

            if (!empty($oldServiceAdditions)) {
                foreach ($oldServiceAdditions as $oldServiceAddition) {
                    $oldServiceAddition->deleted = self::DELETED_TRUE;
                    $oldServiceAddition->save();
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Удаление доп. услуги
     *
     * @param int $addition_id
     * @param int $shop_id
     * @param int $user_id
     *
     * @return bool
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public static function deleteAddition($addition_id = 0, $shop_id = 0, $user_id = 0) {
        if ($addition_id != 0 && $shop_id != 0 && $user_id != 0) {
            $oldServiceAddition = Service::find()->where(['id' => $addition_id, 'user_id' => $user_id,
                'shop_id' => $shop_id, 'agree' => self::AGREE_TRUE, 'deleted' => self::DELETED_FALSE])->limit(1)
                ->one();

            if (!empty($oldServiceAddition)) {
                $oldServiceAddition->delete();
                return true;
            }
        }

        return false;
    }
}
