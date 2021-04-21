<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Reportproducts */

$this->title = 'Update Reportproducts: {nameAttribute}';
$this->params['breadcrumbs'][] = ['label' => 'Reportproducts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->productId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="reportproducts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
