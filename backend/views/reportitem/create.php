<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Reportproducts */

$this->title = 'Create Reportproducts';
$this->params['breadcrumbs'][] = ['label' => 'Reportproducts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reportproducts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
