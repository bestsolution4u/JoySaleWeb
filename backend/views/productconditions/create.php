<?php

use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => 'Productconditions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>

