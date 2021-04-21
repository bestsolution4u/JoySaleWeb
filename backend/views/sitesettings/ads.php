<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use dosamigos\ckeditor\CKEditor;
use kartik\alert\Alert;
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/';
$currency = yii::$app->Myclass->getDbCurrencyList(); 
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Advertisement').' '.Yii::t('app','Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','Advertisement Title'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'ad_title')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter your ad title'),'id'=>'ad_title','required'=>'required'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_ad_title_em_"></p>
      </div>
		<div class="form-group">

      	   <select name="Sitesettings[ad_lang]" id="ad_lang" class="form-control select-box-down-arrow " onchange="dropDownLang(this.val)">
 			<option selected value="en">English</option>
 			<option  value="ar">Arabic</option>
 			<option  value="fr">French</option>
 			</select>
       </div>
      

			
   <input type="hidden" name="initlang" id="initlang" value="<?php echo $model->adlang;?>">

		
      	
		<div class="form-group">
            <label><?php echo Yii::t('app','Advertisement Content'); ?> </label><span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'adcontent')->widget(CKEditor::className(), [
                'options' => ['rows' => 3],
                'preset' => 'basic'
            ])->label(false); ?>
        </div>

			<div class="form-group">
				<label><?php echo Yii::t('app','Advertisement Image') ?> </label><span class="required" style="color: red;"> * </span>
				<input id="ytSitesettings_ad_image" type="hidden" value="" name="Sitesettings[ad_image]" />
				<?= $form->field($model, 'ad_image')->fileInput(['class' => 'm-b15 p-2 borderGrey w-100','id' => 'ad_image'])->label(false); ?>
				<?=Html::img($path.$model->ad_image.'', array('class'=>'img-responsive','style' => 'width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;margin-right:20px;'))?>
				<img src="" class="borderCurve borderGradient picture-src dnone" id="adimagePreview" style="width:100px;height:100px;object-fit: scale-down;border-color: gray;border: double;padding: 5px;">
				
				<p class="text-danger m-t20" id="Sitesettings_ad_image_em_"></p>
			</div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Advertisement Price'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'ad_price')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter Price Value'),'maxlength' => '13','required'=>'required'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_ad_price_em_"></p>
			</div>

			<div class="form-group">
        <label><?php echo Yii::t('app','Advertisement Limit (per day)'); ?> </label>	<span class="required" style="color: red;"> * </span>
				<?= $form->field($model, 'ad_limit')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter Limit Value'),'maxlength' => '13','required'=>'required','onkeypress'=>'return isNumber(event)'])->label(false); ?>
				<p class="text-danger" id="Sitesettings_ad_limit_em_"></p>
			</div>



	<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['id'=>'ad-setting-submit', 'class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>

<script>
var readAjax = 1;
function dropDownLang(value) {

	var selectedlanguage = $('#ad_lang :selected').val();	
	// if(readAjax == 1){

		readAjax = 0;
		$.ajax({
			url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/sitesettings/getadcontent',
			type: "post",
			data : {'selectedlanguage': $.trim(selectedlanguage)},
			success: function(response){
				CKEDITOR.instances['sitesettings-adcontent'].setData(response);
			},
		});
	// }
}

var initlang = $('#initlang').val();	
$("select option[value="+initlang+"]").attr("selected","selected");
</script>

