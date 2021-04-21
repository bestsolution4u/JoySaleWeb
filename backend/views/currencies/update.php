<?php
use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
        'model'=>$model,'selected'=>$selected,'merchant_sc_id'=>$merchant_sc_id
]) ?>
