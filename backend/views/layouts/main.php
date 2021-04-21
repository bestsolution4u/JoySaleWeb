
<?php

use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
//use common\widgets\Alert;
use conquer\toastr\ToastrWidget;

$footerSettings = yii::$app->Myclass->getFooterSettings();
DashboardAsset::register($this);
Yii::$app->i18nJs;
$siteSettings = yii::$app->Myclass->getSitesettings();
$urll=Yii::$app->getUrlManager()->getBaseUrl();
Yii::$app->log->targets['debug'] = null;
?>

<?php $this->beginPage() ?>
<?php
$app_language = Yii::$app->language;
if(Yii::$app->language=="")
$app_language = 'en';
?>
<!DOCTYPE html>
<html lang="<?= $app_language; ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
     <link rel="icon" href="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/media/logo/<?=$siteSettings['favicon']?>">
     <script src="<?php echo Yii::$app->request->baseUrl; ?>/admin/js/jquery.min.js"></script>
     <script src="<?php echo Yii::$app->request->baseUrl; ?>/admin/js/jquery-ui.min.js"></script>
    <?php $this->head() ?>

</head>
<body class="rtl" style="overflow:hidden">
<div class="wrapper">
<?php $this->beginBody() ?>


     <?=$this->render('//admin/sidebar')?> 
     <div class="content-page">
              
              <?=$content?>
  </div>
</div>

<script>
//This baseUrl is used in front.js and sb_admin.js
var baseUrl = "<?php echo $urll;?>";
</script>
<style>
.lang-menu {
    float: left;
    margin-top: 9px;
}
.page-header{
	margin:0 !important;
}

#language label {
    display: none;
}

td .date
{
  width: 200px !important;
}
</style>

<?php $this->endBody();
if(Yii::$app->controller->action->id == 'showtopcategory')
{
  ?>
<script src="<?= str_replace('admin', '', Url::base(true)); ?>/assets/7a03e1bc/jquery.js"></script>
<script src="<?= str_replace('admin', '', Url::base(true)); ?>/js/jquery-ui.min.js"></script>
<script src="<?= Url::base(true); ?>/admin/js/tagInputter.js"></script>
  <?php
}  
 ?>
</body>
</html>
<?php $this->endPage() ?>
