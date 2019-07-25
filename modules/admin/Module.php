<?php

namespace app\modules\admin;

/**
 * Модель обертки админки
 * @package app\modules\admin
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
