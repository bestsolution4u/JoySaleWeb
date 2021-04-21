<?php
use backend\assets\StyleAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

StyleAsset::register($this);
Yii::$app->i18nJs;
$urll=Yii::$app->getUrlManager()->getBaseUrl();
Yii::$app->log->targets['debug'] = null;
$siteSettings = yii::$app->Myclass->getSitesettings();
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
     <title><?= Html::encode($siteSettings['sitename']) ?></title>
     <link rel="icon" href="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl; ?>/media/logo/<?=$siteSettings['favicon']?>">
     <script src="<?php echo Yii::$app->request->baseUrl; ?>/admin/js/jquery.min.js"></script>
     <script src="<?php echo Yii::$app->request->baseUrl; ?>/admin/js/jquery-ui.min.js"></script>
    <?= Html::csrfMetaTags() ?>
  
    <?php $this->head() ?>
</head>
<body class="rtl" style="overflow:hidden">
<div class="wrapper">
<?php $this->beginBody() ?>

<div class="wrapper-page">
	<?=$content?>                     
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
