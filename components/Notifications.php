<?php

namespace app\components;

use Yii;
use app\components\senders\UniSender;

/**
 * Class with notification's "facades"
 *
 * @package app\components
 */
class Notifications
{
    /**
     * @param string      $subject
     * @param bool|string $viewFile
     * @param array       $params
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public static function sendMailToAuthUser($subject = '', $viewFile = false, $params = [])
    {
        $user = Yii::$app->user->identity;
        if (!$user) return false;

        if (!$viewFile) return false;
        $html = Yii::$app->view->renderFile($viewFile, $params);

        if (YII_DEBUG) {
            $sent = Yii::$app->mailer
                ->compose($viewFile, $params)
                ->setTo($user->email)
                ->setFrom(Yii::$app->params['mainEmail'])
                ->setSubject($subject)
                ->send();

            if (!$sent) {
                throw new \RuntimeException('Sending error.');
            }
        } else {
            $uniSender = new UniSender;
            $uniSender->sendMail($user->email, $subject, ['html' => $html]);
        }

        return true;
    }

    /**
     * Отправка мыла не зарегистрированному юзеру
     *
     * @param string $email
     * @param string $subject
     * @param bool   $viewFile
     * @param array  $params
     *
     * @return bool
     * @throws \yii\base\Exception
     */
    public static function sendMailToUser($email = 'admin@admin.ru', $subject = 'Тема', $viewFile = false, $params = [])
    {
        if (!$email) return false;

        if (!$viewFile) return false;
        $html = Yii::$app->view->renderFile($viewFile, $params);

        if (YII_DEBUG) {
            $sent = Yii::$app->mailer
                ->compose($viewFile, $params)
                ->setTo($email)
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setSubject($subject)
                ->send();

            if (!$sent) {
                throw new \RuntimeException('Sending error.');
            }
        } else {
            $uniSender = new UniSender;
            $uniSender->sendMail($email, $subject, ['html' => $html]);
        }

        return true;
    }
}