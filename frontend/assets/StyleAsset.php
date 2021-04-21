<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class StyleAsset extends AssetBundle
{
  public function init()
  {
    
  }

  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    'css/bootstrap-slider.min.css',
    'css/bootstrap.min.css',
    'css/jquery-ui.css',
    'css/addSlider.css',
    'css/style.css',
     'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
     'css/header-async.css',
     'css/pushbar.css',
       'css/braintreeformresp.css',

  ];
  public $js = [
    'js/front.js',
    'js/jquery-ui.min.js',
    'js/salvattore.min.js',
    'js/jquery.easing.1.3.js',
    'js/jquery.magnific-popup.min.js',
    'js/jquery.waypoints.min.js',
    'js/modernizr-2.6.2.min.js',
    'js/modernizr.js',
    'js/respond.min.js',
    'js/bootstrap.min.js',
    'js/dstnc_filter/jshashtable-2.1_src.js',
    'js/dstnc_filter/addSlider.js',
    'js/dstnc_filter/Obj.min.js',
    'js/dstnc_filter/jquery.numberformatter-1.2.3.js',
    'js/dstnc_filter/tmpl.js',
    'js/dstnc_filter/draggable-0.1.js',
    'js/dstnc_filter/jquery.slider.js',
    'js/mousewheel.js',
         'js/navload.js',
    'js/header.js',
    'js/header_002.js',
    'js/pushbar.js',
  ];

  public $depends = [];
}
