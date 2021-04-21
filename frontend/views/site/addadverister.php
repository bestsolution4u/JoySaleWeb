<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

	<?php if (Yii::$app->session->hasFlash('error')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'error', 'message' => Yii::$app->session->getFlash('error'),
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
					]); ?>
					    <?php endif; ?>
<div class="advrtz-section">
	<div class="advrtz-banner-top">
		<img class="img-responsive" src="<?=Yii::$app->urlManager->createAbsoluteUrl("/images/advtz.png");?>">
	</div>
	<div class="container-small container">	
		<div class="row">
	 		<div class="col-xs-12 col-sm-12 col-md-12">
				<h2 class="top-heading-text">Banner Advertise</h2>
				<div class="advrtz-form">
				<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return validatebanner()']]); ?>
						<div class="advrtz-row">
							<div class="add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="add-photos-heading padding-left-10">
									<span>Add banner of your stuff</span>
								</div>
								 <?= $form->field($model, 'webbanner')->fileInput(['id' => 'webbanner','required'])->label(false);?>
								 <?= $form->field($model, 'appbanner')->fileInput(['id' => 'appbanner','required'])->label(false);?>

							 <!--	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<div class="form-group margin-bottom30">
										<div class="file-section rounded text-center padding-top30 padding-bottom30">


										    <input type="file" id="files" name="image_file_arr[]" multiple class="upload-file form-control-file">

										    <label for="files" class="">
										    	<img src="<?=Yii::$app->urlManager->createAbsoluteUrl("/images/upload.png");?>" alt="upload-img">
										    </label> -->
										    <!--<output id="list"></output>
										</div>
								  	</div>
									<p>Web banner (size 1920px x 400px)</p>
								</div>

								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<div class="form-group margin-bottom30">
										<div class="file-section rounded text-center padding-top30 padding-bottom30">

										    <input type="file" id="files" name="image_file_arr[]" multiple class="upload-file form-control-file">

										    <label for="files" class="">
										    	<img src="Version%20YII%20%20%20Online%20Classifieds%20Platform%20to%20Buy%20Sell%20Locally-insight_files/upload.png" alt="upload-img">
										    </label>
									
										</div>
								  	</div>
									<p>App banner (size 1024px x 500px)</p>
								</div>-->

							</div>

							<div class="baner-link margin-top-10 add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="add-photos-heading padding-left-10 clearfix">
									<span class="advrt-header">Banner link</span>
								</div>
								<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12">
								    <div class="form-group">
									<?= $form->field($model, 'bannerlink')->textInput(['maxlength' => true,'id' => 'bannerlink'])->label(false); ?>		
								      
								    </div>
								</div>
							</div>

							<div class="margin-top-10 add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="add-photos-heading padding-left-10 clearfix">
									<span class="advrt-header">When your banner display in live?</span>

									<span class="pull-right live-amount">$200.00 Per day</span>

								</div>
								<div class="Category-input-box-row col-xs-12 col-sm-6 col-md-6 col-lg-6">
								    <div class="form-group">
								        <label for="startDate">Start Date <span class="required">*</span></label>
								        <input id="startDate" name="startDate" type="date" class="form-control date-select" />
								    </div>
								</div>
								<div class="Category-input-box-row col-xs-12 col-sm-6 col-md-6 col-lg-6">
								    <div class="form-group">
								        <label for="endDate">End Date <span class="required">*</span></label>
								        <input id="endDate" name="endDate" type="date" class="form-control date-select" />
								    </div>
								</div>
								<p class="padding-left-10 margin-bottom-0">Your ads will run for <b>41 days</b>. You'll spend no more than <b>$8,200.00</b></p>
							</div>



						</div>

						<div class="margin-top-20">
						<?= Html::submitButton(Yii::t('app','Banner advertise'), ['class' => 'post-btn btnUpdate btn']) ?>
							<!-- <button type="submit" class="post-btn btnUpdate btn">Advertise with us</button> -->
						</div>
						<?php ActiveForm::end(); ?>
				</div>
			</div>
		</div>
	</div>
</div>


