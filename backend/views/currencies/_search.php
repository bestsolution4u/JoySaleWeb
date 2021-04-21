<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CurrenciesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currencies-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'currency_name') ?>

    <?= $form->field($model, 'currency_shortcode') ?>

    <?= $form->field($model, 'currency_image') ?>

    <?= $form->field($model, 'currency_symbol') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
