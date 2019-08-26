<?php

namespace app\models\tickets;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tickets_text}}".
 *
 * @property int $id
 * @property int $ticket_id ID Обращения
 * @property int $date_time Дата и время
 * @property string $text Текст
 * @property int $user_type Тип пользователя
 *
 * @property Tickets $ticket
 * @property TicketsFiles[] $ticketsFiles
 */
class TicketsText extends ActiveRecord {

    public $ticketsFiles;
    public $manyFiles;

    const TYPE_USER_NORMAL = 0;
    const TYPE_USER_TICKETS = 1;

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
            ['date_time', 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['text'], 'string'],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tickets::class,
                'targetAttribute' => ['ticket_id' => 'id']],

            [['ticketsFiles', 'manyFiles'], 'file', 'extensions' => 'jpg, png, pdf, doc, docx, xls, xlsx, jpeg',
                'maxFiles' => 4, 'maxSize' => 1024 * 1024 * 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'ticket_id' => 'ID Обращения',
            'date_time' => 'Дата и время текста',
            'text' => 'Текст',
            'user_type' => 'Тип пользователя',

            'ticketsFiles' => 'Доп. файл (до 5шт.)',
            'manyFiles' => 'Доп. файл (до 5шт.)',
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
        return $this->hasMany(TicketsFiles::class, ['ticket_text_id' => 'id']);
    }
}
