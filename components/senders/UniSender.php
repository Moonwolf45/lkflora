<?php

namespace app\components\senders;

use Yii;
use yii\base\Exception;
use yii\helpers\Json;

/**
 * Class UniSender
 * @package app\components\senders
 */
class UniSender
{
    protected $url = 'https://one.unisender.com/ru/transactional/api/v1/email/send.json';

    protected $key = ' ';

    protected $username = ' ';

    protected $from_name = ' ';

    protected $from_email = ' ';

    private $_ch = null;

    /**
     * UniSender constructor.
     */
    public function __construct()
    {
        $this->_ch = curl_init();
    }

    /**
     * UniSender destructor.
     */
    public function __destruct()
    {
        if ($this->_ch) curl_close($this->_ch);
    }

    /**
     * @param string $email
     * @param string $subject
     * @param array  $body
     *
     * @return bool|string
     * @throws Exception
     */
    public function sendMail($email = '', $subject = '', $body = [])
    {
        $object = [
            'api_key'  => $this->key,
            'username' => $this->username,
            'message'  => [
                'body'       => $body,
                'subject'    => $subject,
                'from_email' => $this->from_email,
                'from_name'  => $this->from_email,
                'recipients' => [
                    'email' => $email,
                ],
            ],
        ];

        $data = Json::encode($object);

        return $this->curl($data);
    }

    /**
     * @param string $data
     *
     * @return bool|string
     */
    protected function curl(string $data)
    {
        curl_setopt($this->_ch, CURLOPT_URL, $this->url);
        curl_setopt($this->_ch, CURLOPT_HEADER, false);
        curl_setopt($this->_ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_ch, CURLOPT_POST, true);
        curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);

        $answer = curl_exec($this->_ch);
        if (empty($answer)) {
            Yii::error('Пустой ответ от сервера');
            throw new Exception(('Пустой ответ от сервера'), 500);
        }

        return $answer;
    }
}