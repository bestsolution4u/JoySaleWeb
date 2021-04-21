<?php

use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

DashboardAsset::register($this);

$footerSettings = yii::$app->Myclass->getFooterSettings();
Yii::$app->i18nJs;
$siteSettings = yii::$app->Myclass->getSitesettings();
$urll=Yii::$app->getUrlManager()->getBaseUrl();
Yii::$app->log->targets['debug'] = null;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
    <?= Html::csrfMetaTags() ?>
      <link rel="icon" href="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/media/logo/<?=$siteSettings['favicon']?>">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body class="rtl">
    <div class="wrapper">
<?php $this->beginBody() ?>
<?php //echo $this->render('//admin/topbar')?> 
     <?=$this->render('//admin/sidebar')?> 

       <div class="content-page">
              
               	<?=$content?>
       </div>
           
</div>        
</div>

<script>
//This baseUrl is used in front.js and sb_admin.js
var baseUrl = "<?php echo $urll;?>";
</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script>
    google.load('maps', '3', {
        other_params: 'key=AIzaSyBqN_3wtob65prYXsWPGtvaMNzntAzpnes',
        callback: function(){}
    });
</script>
