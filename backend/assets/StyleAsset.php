<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class StyleAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'admin/scss/bootstrap.min.css',
        'admin/scss/style.css',
        'admin/css/bootstrap-tagsinput.css',
    ];
    public $js = [
        //'admin/js/jquery.min.js',
        //'admin/js/jquery-ui.min.js',
        'admin/js/bootstrap.min.js',
        'admin/js/popper.min.js',
        'admin/js/sb-admin-2.js',
        'admin/js/jquery.slimscroll.js',
        'admin/js/bootstrap-tagsinput.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}


