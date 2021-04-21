<?php

use yii\helpers\Html;
use conquer\toastr\ToastrWidget;

/* @var $this yii\web\View */
/* @var $model common\models\Categories */

$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->categoryId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

	<div class="container" class="users-create">
				<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Update Banner</h1>
		</div>
	</div>
	<div class="row">
	<?php if(Yii::$app->session->hasFlash('success')): ?>
	<?=ToastrWidget::widget(['type' => 'success', 'message'=>Yii::$app->session->getFlash('success'),
"closeButton" => true,
"debug" => false,
"newestOnTop" => false,
"progressBar" => false,
"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
"preventDuplicates" => false,
"onclick" => null,
"showDuration" => "300",
"hideDuration" => "1000",
"timeOut" => "5000",
"extendedTimeOut" => "1000",
"showEasing" => "swing",
"hideEasing" => "linear",
"showMethod" => "fadeIn",
"hideMethod" => "fadeOut"
]);?>
    <?php endif; ?>
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
                Update Banner	</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
<div class="categories-update">
<?php
//echo 'test';

?>

    <?= $this->render('_form', [
        'model' => $model, 
        'parentCategory'=>$parentCategory,
        'parentAttribute'=>$parentAttribute
    ]) ?>

</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
