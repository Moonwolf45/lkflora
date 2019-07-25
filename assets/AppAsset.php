<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Основная библиотека
 *
 * @package app\assets
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'style.css',
    ];

    public $js = [
        'js/jquery-3.3.1.min.js',
        'js/jquery.cookie.js',
        'js/main.js',
    ];

    public $depends = [
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
