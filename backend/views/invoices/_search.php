<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\InvoicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoices-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'invoiceId') ?>

    <?= $form->field($model, 'orderId') ?>

    <?= $form->field($model, 'invoiceNo') ?>

    <?= $form->field($model, 'invoiceDate') ?>

    <?= $form->field($model, 'invoiceStatus') ?>

    <?php // echo $form->field($model, 'paymentMethod') ?>

    <?php // echo $form->field($model, 'paymentTranxid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
