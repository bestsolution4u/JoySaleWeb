<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdveristerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adverister-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'userid') ?>

    <?= $form->field($model, 'webbanner') ?>

    <?= $form->field($model, 'appbanner') ?>

    <?= $form->field($model, 'bannerlink') ?>

    <?php // echo $form->field($model, 'startdate') ?>

    <?php // echo $form->field($model, 'enddate') ?>

    <?php // echo $form->field($model, 'totaldays') ?>

    <?php // echo $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'paidstatus') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'tranxId') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
