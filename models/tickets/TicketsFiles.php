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
 * @property int $type_file Тип файла
 * @property string $file Файл
 * @property string $name_file Имя файла
 *
 * @property Tickets $ticket
 * @property TicketsText $ticketText
 */
class TicketsFiles extends ActiveRecord {

    const TYPE_IMAGE = 0;
    const TYPE_FILE = 1;

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
            [['ticket_id', 'ticket_text_id', 'file', 'type_file', 'name_file'], 'required'],
            [['ticket_id', 'ticket_text_id', 'type_file'], 'integer'],
            [['name_file'], 'string'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class,
                'targetAttribute' => ['ticket_id' => 'id']],
            [['ticket_text_id'], 'exist', 'skipOnError' => true, 'targetClass' => TicketsText::class,
                'targetAttribute' => ['ticket_id' => 'id']],

            [['file'], 'file', 'extensions' => 'jpg, png, pdf, doc, docx, xls, xlsx, jpeg', 'maxFiles' => 4,
                'skipOnEmpty' => true, 'maxSize' => 1024 * 1024 * 5]
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
            'type_file' => 'Тип файла',
            'file' => 'Файл',
            'name_file' => 'Имя файла',
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
