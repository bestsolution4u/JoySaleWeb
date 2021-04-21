<?php
use yii\helpers\Html;
$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="filter-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>


