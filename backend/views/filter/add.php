<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;

$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;


?>
<?php  if(Yii::$app->session->hasFlash('info')): ?>
<?=ToastrWidget::widget(['type' => 'info', 'message'=>Yii::$app->session->getFlash('info'),
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
<?php  endif; ?>

<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

	<div class="container" class="filter-create">
				<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
		<?php echo Yii::t('app','Select').' '.Yii::t('app','Filters'); ?>
			   
			   	</div>
                   <div class="form">

                    <?php $form = ActiveForm::begin(); ?>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
<div class="filter-add">
<div class="form-group">
<select class="form-control col-sm-4 margin_left10 " id="selectedoption" style="width:auto;" name="filter"  onchange="selectfilter();">
				<?php 
							echo ' <option value="0">Select Filter</option>';?>
				<?php foreach($model as $models)
					{
						echo '<option value="'.$models['id'].'">'.$models['name'].'</option>';
					}
				 ?>
				</select>  
                
<br><br>
</div></div>

</div>
<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-fw dark', 'name' => 'save']) ?>

<?= Html::a(Yii::t('app','Cancel'), Yii::$app->request->referrer, ['class'=>'btn btn-fw btn-danger']) ?>
</div>
</div>
	

<?php ActiveForm::end(); ?>
</div></div>
</div>
</div>
</div>
