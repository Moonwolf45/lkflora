<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Админ библиотека
 *
 * @package app\assets
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
        'assets/css/theme.css',
        'css/admin.css',
    ];

    public $js = [
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}