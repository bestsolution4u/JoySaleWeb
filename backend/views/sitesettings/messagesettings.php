<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>


<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]); ?>
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
			<h4 class="m-b15  blueTxtClr p-t10 p-b10"><?= Yii::t('app','Mobile SMS').' '.Yii::t('app','Settings'); ?></h4>
			<h6 class="m-b15"><b>Firebase AccountKit <?=Yii::t('app','Settings')?></b></h6>
			
			<div class="form-group">
        <label><?php  echo Yii::t('app','Firebase').' '.Yii::t('app','App key'); ?> </label>
				<?= $form->field($model, 'fb_appid')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter firebase api key')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_fb_appid_em_"></p>
      </div>

      <!-- <div class="form-group">
        <label><?php  //echo Yii::t('app','Facebook').' '.Yii::t('app','Secret id'); ?> </label>
				<?//= $form->field($model, 'fb_secret')->textInput(['maxlength' => true,'class' => 'form-control','placeholder'=>Yii::t('app','Enter facebook Secret id')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_fb_secret_em_"></p>
      </div> -->
			
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
