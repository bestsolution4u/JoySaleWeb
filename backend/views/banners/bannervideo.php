<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use dosamigos\ckeditor\CKEditor;
use kartik\alert\Alert;
$path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/videos/';
?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data','onsubmit' => 'return validatebannervideo()']]); ?>
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
		<div class="d-flex justify-content-between  flex-column flex-sm-row">
           <div>
				<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Add').' '.Yii::t('app','Video').' '.Yii::t('app','Banner'); ?></h4>
			</div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
					<?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['banners/index']); ?> 
                </button> 
            </div>
        </div>

		<?php 
			$bannervideo=$model->bannervideo;
			$bannervideoposter=$model->bannervideoposter;
			$bannerText=$model->bannerText;
			$extensionArray=explode(".",$bannervideo);
		?>

		<div class="d-flex flex-column flex-sm-row m-b40">
            <div class="currencypromotion">
                <div class=" align-self-center m-r50">
                    <h6 class="p-t10 "><?php echo Yii::t('app','Enable Video Banner');?></h6>
                      <div class="text-danger p-b10" id="bannervideomsg"><?=yii::t('app','Note : If you enable video banner, web banner & paid banner get disable')?></div>
                    <?php if($model->bannervideoStatus=="1"){$checked='checked';$value=1;}else{$checked='';$value=0;} ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input videobannerapprove" id="Sitesettings_bannervideoStatus" name="bannervideoStatus"  value="<?php echo $sitesettings->bannervideoStatus;?>" <?php echo $checked;?>>
                        <label class="custom-control-label" for="Sitesettings_bannervideoStatus"></label>
                      
                    </div>

                </div>
                  
            </div>

        </div>

				
		<div class="form-group" id="bannerVideo">
			<label><?php  echo Yii::t('app','Banner').' '.Yii::t('app','Video'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?=$form->field($model, 'bannervideo')->hiddenInput(['id' => 'hiddenBannerVideo','value'=> $model->bannervideo])->label(false)?>
			<div class="MultiFile-wrap" id="Sitesettings_bannerimage_wrap">
				<?=$form->field($model, 'bannervideo')->fileInput(['id' => 'file','name' => 'file','required','class' => 'MultiFile-applied'])->label(false)?>
				<div class="MultiFile-list" id="Sitesettings_bannerimage_wrap_list"></div>
			</div>
			<div class="text-danger" id="bannervideomsg"><?php echo Yii::t('app','Only allow mp4 type, video size maximum 50 MB');?></div>	
			<p class="text-danger m-t10" id="bannervideoError"></p>
      	</div>
        

		<?php if($bannervideo!="") {?>
			<video width="220" height="140" controls loop="loop" muted="muted" autoplay="autoplay">
				<source src="<?php echo $path.$model->bannervideo; ?>" type="video/<?php echo $extensionArray[1];?>">
				Your browser does not support the video tag
			</video>
			<i class="fa fa-trash m-r10"></i><?=Html::a(Yii::t('app','Remove video'),array('banners/deletevideo', 'details'=>base64_encode($bannervideo)));?>

		<?php } ?>	

		<div class="form-group">
			<label><?php echo Yii::t('app','Banner').' '.Yii::t('app','Text');?></label>
			<?= $form->field($model, 'bannerText')->textarea([
                'options' => ['rows' => 5, 'id' => 'bannertxt'],
                'preset' => 'basic'
            ])->label(false); ?>
			<p class="text-danger" id="bannerText"></p>
      </div>

	 	<div class="m-t20" id="beforeupload">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10','id'=>'bannercreatebtn','name' => 'submit']) ?>
      	</div>
       
							
<?php ActiveForm::end(); ?>