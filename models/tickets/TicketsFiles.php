<?php

namespace app\models\tickets;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tickets_files}}".
 *
 * @property int $id
 * @property int $ticket_id ID Обращения
 * @property int $ticket_text_id ID Обращения текста
 * @property string $image Картинка
 *
 * @property Tickets $ticket
 * @property TicketsText $ticketText
 */
class TicketsFiles extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%tickets_files}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['ticket_id', 'ticket_text_id', 'image'], 'required'],
            [['ticket_id', 'ticket_text_id'], 'integer'],
            [['file'], 'string', 'max' => 255],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class,
                'targetAttribute' => ['ticket_id' => 'id']],
            [['ticket_text_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketsText::class,
                'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'ticket_id' => 'ID Обращения',
            'ticket_text_id' => 'ID Обращения текста',
            'file' => 'Картинка',
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
    public function getTicketText() {
        return $this->hasOne(TicketsText::class, ['id' => 'ticket_text_id']);
    }
}
