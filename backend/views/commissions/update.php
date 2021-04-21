<?php
use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => 'Commissions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>


