<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Проверка работы системы
 *
 * 359 v8-CV
 */
class SystemTestController extends Controller {
    public $status = 'Система работает стабильно';

    public $error = null;

    /**
     * Запуск системы сканирования
     *
     * @return int Exit code
     */
    public function actionIndex() {
        echo "Версия системы: " . $this->getVersion() . "\n";

        $this->checkStatusBase();

        if (!$this->checkSettingsBD()) {
            $this->status .= "\n Найдены ошибки в настройке работы с БД \n";
        }

        if (!$this->error) {
            echo $this->status . "\n";
        }

        return ExitCode::OK;
    }

    /**
     * Проверка статуса базовых систем
     *
     * @return bool
     */
    private function checkStatusBase() {
        if (Yii::$app->request && Yii::$app->response && Yii::$app->formatter && Yii::$app->security && Yii::$app->basePath) {
            return true;
        } else {
            $this->error = 1;
            $this->status = 'Найдены ошибки в работе системы (запрос/ответ/обработка)';
        }

        return false;
    }

    /**
     * Проверка версии систеы
     *
     * @return string
     */
    public function getVersion() {
        return Yii::getVersion();
    }

    /**
     * Отображение карты классов
     * пы.сы.: Скрывать с паблика!
     *
     * @return bool
     */
    private function getClassMap() {
        var_dump(Yii::$classMap);

        return true;
    }

    /**
     * Проверка корректности настройки БД
     *
     * @return bool
     */
    private function checkSettingsBD() {
        if (Yii::$app->db->enableLogging && Yii::$app->db->shuffleMasters && Yii::$app->db->enableSlaves) {
            return true;
        }

        return false;
    }
}
