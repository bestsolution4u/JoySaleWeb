<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use conquer\toastr\ToastrWidget;
	use kartik\alert\Alert;
?>

<?php //$form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validate_logos()']]);

$form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]); ?>
				<?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 8000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 8000
                ]); 
            endif; 
        ?>
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Footer Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','Footer Facebook Link'); ?> </label>	
				<?= $form->field($model, 'facebookFooterLink')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter Facebook Link'),'id'=>'Sitesettings_facebookFooterLink'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_facebookFooterLink_em_"></p>
      </div>
      <div class="form-group">
        <label><?php echo Yii::t('app','Footer Google plus Link'); ?> </label>	
				<?= $form->field($model, 'googleFooterLink')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter Google plus Link'),'id'=>'Sitesettings_facebookFooterLink'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_googleFooterLink_em_"></p>
      </div>


			
			<div class="form-group">
        <label><?php echo Yii::t('app','Footer Twitter Link'); ?> </label>
				<?= $form->field($model, 'twitterFooterLink')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter Twitter Link'),'id'=>'Sitesettings_twitterFooterLink'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_twitterFooterLink_em_"></p>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Footer Android app Link'); ?> </label>
				<?= $form->field($model, 'androidFooterLink')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter Android app Link'),'id'=>'Sitesettings_androidFooterLink'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_androidFooterLink_em_"></p>
      </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Footer IOS app Link') ?> </label>
				<?= $form->field($model, 'iosFooterLink')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter IOS app Link')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_iosFooterLink_em_"></p>
      </div>
      
      <div class="form-group">
        <label><?php echo Yii::t('app','Footer Social Login Heading') ?> </label>
				<?= $form->field($model, 'socialloginheading')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter social login heading')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_socialloginheading_em_"></p>
			</div>
			
			<div class="form-group">
        <label><?php echo Yii::t('app','Footer App Link Heading') ?> </label>
				<?= $form->field($model, 'applinkheading')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter App Link Heading'),'id'=>'Sitesettings_applinkheading'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_applinkheading_em_"></p>
			</div>
			
			<div class="form-group">
        <label><?php echo Yii::t('app','Footer General Text Before Login') ?> </label>
				<?= $form->field($model, 'generaltextguest')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter General Text Before Login')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_generaltextguest_em_"></p>
			</div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Footer General Text After Login') ?> </label>
				<?= $form->field($model, 'generaltextuser')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter General Text After Login')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_generaltextuser_em_"></p>
			</div>
			
			<div class="form-group">
        <label><?php echo Yii::t('app','Footer Copy Rights Details'); ?> </label>
				<?= $form->field($model, 'footerCopyRightsDetails')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter Copy Rights Details')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_footerCopyRightsDetails_em_"></p>
			</div>

		
			
			<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
