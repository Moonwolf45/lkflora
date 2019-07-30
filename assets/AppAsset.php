<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Основная библиотека
 *
 * @package app\assets
 */
class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/style.css',
        'css/dadata.css',
    ];

    public $js = [
        'js/jquery.cookie.js',
        'js/main.js',
        'js/jquery.suggestions.min.js',
        'js/359.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapPluginAsset',
    ];
}
