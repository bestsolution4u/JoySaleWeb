<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Invoices */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invoices-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'orderId')->textInput() ?>

    <?= $form->field($model, 'invoiceNo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'invoiceDate')->textInput() ?>

    <?= $form->field($model, 'invoiceStatus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paymentMethod')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'paymentTranxid')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
