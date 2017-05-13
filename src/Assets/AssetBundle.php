<?php


namespace App\Assets;


use yii\web\View;

class AssetBundle extends \yii\web\AssetBundle
{
    public $sourcePath = '@resources/templates/yiiguide/assets/css';
    public $css = [
        'style.css',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'App\Assets\HighlightBundle',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}
