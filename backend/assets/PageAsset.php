<?php

namespace backend\assets;

use yii\web\AssetBundle;

class PageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'admin/scss/bootstrap.min.css',
        'admin/scss/style.css',
        'admin/scss/font-awesome.min.css',
        'admin/scss/materialdesignicons.min.css',
        'admin/scss/gijgo.min.css',
        'admin/scss/datepicker.less',
        'admin/css/bootstrap-tagsinput.css',
         'admin/css/tagsinputstyle.css',
    ];



    public $js = [
        // 'admin/js/jquery.min.js',
        'admin/js/bootstrap.min.js',
        'admin/js/popper.min.js',
        'admin/js/countrySelect.js',
        'admin/js/custom.js',
        'admin/js/front.js',
        'admin/js/sb-admin-2.js',
        'admin/js/jquery.slimscroll.js',
        'admin/js/bootstrap-tagsinput.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
