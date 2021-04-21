<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\alert\Alert;
error_reporting(0);
$path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/';
?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validatebanner()']]); ?>

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

		<?php if(Yii::$app->controller->action->id== 'create') { ?>
            <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Banner'); ?></h4>
        <?php } else { ?>
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Update').' '.Yii::t('app','Banner'); ?></h4>
        <?php } ?>
		
		

		<div class="form-group">
			<label><?php echo Yii::t('app','Banner Image For Web (1920 X 400)') ?> </label><span class="required" style="color: red;"> * </span>
			   <input id="hiddenwebImage" type="hidden" value="<?php echo $model->bannerimage;?>" name="<?php echo $model->bannerimage;?>" /> 
			
			<?= $form->field($model, 'bannerimage')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'Banners_bannerimage'])->label(false); ?>
			<?php if(!empty($model->bannerimage)): echo Html::img($path.$model->bannerimage.'', array('class'=>'img-responsive','style' => 'height:100px;width:300px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;')); endif;?>
			<img src="" class="borderCurve borderGradient picture-src dnone" id="bannerPreview" style="height:100px;width:300px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
			<p class="text-danger" id="bannerimageerr"></p>
		</div>

		<div class="form-group">
			<label><?php echo Yii::t('app','Banner Image For App (1024 X 500)') ?> </label><span class="required" style="color: red;"> * </span>
			   <input id="hiddenappImage" type="hidden" value="<?php echo $model->appbannerimage;?>" name="<?php echo $model->appbannerimage;?>" /> 
		
			<?= $form->field($model, 'appbannerimage')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'Banners_appbannerimage'])->label(false); ?>
			<?php if(!empty($model->appbannerimage)): echo Html::img($path.$model->appbannerimage.'', array('class'=>'img-responsive','style' => 'height:100px;width:150px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;')); endif;?>
			<img src="" class="borderCurve borderGradient picture-src dnone" id="appbannerPreview" style="height:100px;width:150px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
			<p class="text-danger" id="appbannerimageerr"></p>
		</div>

		<div class="form-group">
			<label><?php echo Yii::t('app','Banner URL'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'bannerurl')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter banner url'),'id'=>'Banners_bannerurl'])->label(false); ?>
			<p class="text-danger" id="bannerurlerr"></p>
		</div>

		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>