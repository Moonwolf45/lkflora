<?php

namespace app\models\tickets;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tickets_text}}".
 *
 * @property int $id
 * @property int $ticket_id ID Обращения
 * @property string $text Текст
 * @property int $user_type Тип пользователя
 *
 * @property Tickets $ticket
 * @property TicketsFiles[] $ticketsFiles
 */
class TicketsText extends ActiveRecord {

    public $ticketsFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%tickets_text}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['ticket_id', 'text', 'user_type'], 'required'],
            [['ticket_id', 'user_type'], 'integer'],
            [['text'], 'string'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class,
                'targetAttribute' => ['ticket_id' => 'id']],

            [['ticketsFiles'], 'file', 'maxFiles' => 5, 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'ticket_id' => 'ID Обращения',
            'text' => 'Текст',
            'user_type' => 'Тип пользователя',

            'ticketsFiles' => 'Доп. файл (до 5шт.)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket() {
        return $this->hasOne(Tickets::class, ['id' => 'ticket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketsFiles() {
        return $this->hasMany(TicketsFiles::class, ['id' => 'ticket_text_id']);
    }
}
