<?php
namespace frontend\controllers;
namespace common\components;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Sitesettings;
use yii\web\Cookie;

class Language extends Widget{
    public $message;

 

    public function init() {
        parent::init();
        $app = Yii::$app;
        $siteSetting = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
        $sitename = $siteSetting->sitename;
        $metaData = json_decode($siteSetting->metaData, true);
        if(!empty($metaData)){
            $metaTitle = $metaData['metaTitle'];
            $metaDescription = $metaData['metaDescription'];
        }
        $app->name = $siteSetting->sitename;

    
        if (isset($_POST['_lang']))
        {
            $app->language = $_POST['_lang'];
           Yii::$app->session->set('language', $app->language);

        }
        else if (isset($_SESSION['language']))
        {
            $app->language = $_SESSION['language'];
           
        }

        return 


    }

    public function run(){
       $currentLang = Yii::$app->language;
        return $this->render('language', ['currentLang' => $currentLang]);
    }
}
?>