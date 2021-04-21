<?php
use backend\assets\StyleAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
Yii::$app->log->targets['debug'] = null;
StyleAsset::register($this);
Yii::$app->i18nJs;
$urll=Yii::$app->getUrlManager()->getBaseUrl();
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>


       
       <div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
	<?=$content?>                     
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
