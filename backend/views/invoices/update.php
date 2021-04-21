<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Invoices */

$this->title = 'Update Invoices: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Invoices', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->invoiceId, 'url' => ['view', 'invoiceId' => $model->invoiceId, 'orderId' => $model->orderId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="invoices-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
