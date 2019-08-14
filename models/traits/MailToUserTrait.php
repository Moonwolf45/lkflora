<?php

namespace app\models\traits;

use Yii;
use yii\mail\MessageInterface;

trait MailToUserTrait {

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @param $subject
     * @param $view
     *
     * @param array $params
     *
     * @return MessageInterface
     */
    public function sendMailToUser($email, $view, $subject, $params = []) {
        Yii::$app->mailer->getView()->params['email'] = $params['email'];
        Yii::$app->mailer->getView()->params['password_hash'] = $params['password_hash'];
        Yii::$app->mailer->getView()->params['link'] = $params['link'];

        $result = Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params);

        $result->setTo([$email]);
        $result->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']]);
        $result->setSubject($subject);
        $result->send();

        Yii::$app->mailer->getView()->params['email'] = null;
        Yii::$app->mailer->getView()->params['password_hash'] = null;
        Yii::$app->mailer->getView()->params['link'] = null;

        return $result;
    }

}
