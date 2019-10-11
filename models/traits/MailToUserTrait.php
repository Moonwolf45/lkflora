<?php

namespace app\models\traits;

use Yii;
use yii\mail\MessageInterface;

trait MailToUserTrait {

    /**
     * Отправка письма на email с файлом
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
        Yii::$app->mailer->getView()->params['text'] = $params['text'];

        $result = Yii::$app->mailer->compose([
            'html' => 'views/' . $view . '-html',
            'text' => 'views/' . $view . '-text',
        ], $params);

		if (array_key_exists('at_file', $params)) {
			foreach ($params['at_file'] as $file) {
				$content_file = file_get_contents($file->tempName);
				$result->attachContent($content_file, [
					'fileName' => preg_replace("/[^ \w]+/", "_", $file->baseName) . '.' . $file->extension,
					'contentType' => $file->type]);
			}
		}

        $result->setTo($email);
        $result->setSubject($subject);
        $result->send();

        Yii::$app->mailer->getView()->params['email'] = null;
        Yii::$app->mailer->getView()->params['password_hash'] = null;
        Yii::$app->mailer->getView()->params['link'] = null;
        Yii::$app->mailer->getView()->params['text'] = null;

        return $result;
    }

}
