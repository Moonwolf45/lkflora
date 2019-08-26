<?php

namespace app\models\tickets;

use app\models\db\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tickets}}".
 *
 * @property int $id
 * @property int $user_id ID Бренда
 * @property string $subject Тема
 * @property int $status Открыто_закрыто обращение
 * @property int $new_text Есть новое сообщение
 *
 * @property User $user
 * @property TicketsFiles[] $ticketFiles
 * @property TicketsText[] $ticketsText
 */
class Tickets extends ActiveRecord {

    const STATUS_OPEN_TICKET = 1;
    const STATUS_CLOSE_TICKET = 0;

    public $ticketFiles;
    public $manyFile;
    public $tickets_text;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%tickets}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['user_id', 'subject', 'status', 'tickets_text', 'new_text'], 'required'],
            [['user_id', 'status'], 'integer'],
            ['new_text', 'boolean', 'trueValue' => true, 'falseValue' => false, 'strict' => false],
            [['subject'], 'string', 'max' => 255],
            [['tickets_text'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],

            [['ticketFiles', 'manyFile'], 'file', 'extensions' => 'jpg, png, pdf, doc, docx, xls, xlsx, jpeg',
                'maxFiles' => 4, 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'user_id' => 'ID Бренда',
            'subject' => 'Тема обращения',
            'tickets_text' => 'Текст сообщения',
            'status' => 'Открыто_закрыто обращение',
            'new_text' => 'Есть новое сообщение',

            'ticketFiles' => 'Доп. файл (до 5шт.)',
            'manyFile' => 'Доп. файл (до 5шт.)',
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
    public function getTicketsText() {
        return $this->hasMany(TicketsText::class, ['ticket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastTicket() {
        return $this->hasOne(TicketsText::class, ['ticket_id' => 'id'])->orderBy(['id' => SORT_DESC])->limit(1);
    }
}
