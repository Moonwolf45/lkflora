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
        'css/dadata.css',
        'css/style.css',
        'css/miniBootstrap.css',
    ];

    public $js = [
        'js/jquery.cookie.js',
        'js/jquery.suggestions.min.js',
        'js/359.js',
        'js/jsx-select.js',
        'js/jsx-modal.js',
        'js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
