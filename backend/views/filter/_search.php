<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\FilterSearch */
/* @var $form yii\widgets\ActiveForm */
$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="filter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'categoryID') ?>

    <?= $form->field($model, 'subcategoryID') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'isRequired') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>