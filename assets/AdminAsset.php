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
        'css/jquery.fancybox.min.css',
    ];

    public $js = [
        'js/jquery.fancybox.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
