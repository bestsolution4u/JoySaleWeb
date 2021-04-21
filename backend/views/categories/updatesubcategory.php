<?php

use yii\helpers\Html;
use conquer\toastr\ToastrWidget;
use yii\widgets\ActiveForm;

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
	<?php if(Yii::$app->session->hasFlash('warning')): ?>
	<?=ToastrWidget::widget(['type' => 'warning', 'message'=>Yii::$app->session->getFlash('warning'),
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


    <?= $this->render('_subcategory', [
        'model' => $model, 'parentCategory'=>$parentCategory
    ]) ?>

</div>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
