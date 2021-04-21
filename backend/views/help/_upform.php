<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
		<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Edit').' '.Yii::t('app','Help Pages'); ?></h4>
				
		<div class="form-group">
			<label><?php echo Yii::t('app','Page'); ?> </label>	<span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'page')->textInput(['class' => 'form-control','maxlength' => 60,'placeholder'=>Yii::t('app','Enter help query')])->label(false); ?>
      	</div>

      	<div class="form-group">

      	   <select name="Help[help_lang]" id="help_lang" class="form-control select-box-down-arrow " onchange="dropDownLang(this.val)">
 			<option selected value="en">English</option>
 			<option  value="ar">Arabic</option>
 			<option  value="fr">French</option>
 			</select>
       </div>
       <input type="hidden" name="initlang" id="initlang" value="<?php echo $model->helppagelang;?>">
       <input type="hidden" name="helpid" id="helpid" value="<?php echo $model->id; ?>">

		<div class="form-group">
            <label><?php echo Yii::t('app','Description'); ?> </label><span class="required" style="color: red;"> * </span>
			<?= $form->field($model, 'helppageContent')->widget(CKEditor::className(), [
                'options' => ['rows' => 3],
                'preset' => 'basic'
            ])->label(false); ?>
        </div>

	  	<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      	</div>
       
							
<?php ActiveForm::end(); ?>


<script>
var readAjax = 1;
function dropDownLang(value) {

	var selectedlanguage = $('#help_lang :selected').val();	
	var helpid = $('#helpid').val();	
	// if(readAjax == 1){

		readAjax = 0;
		$.ajax({
			url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/help/gethelpcontent',
			type: "post",
			data : {'selectedlanguage': $.trim(selectedlanguage),'id':helpid},
			success: function(response){
				
				CKEDITOR.instances['help-helppagecontent'].setData(response);
			},
		});
	// }
}
var initlang = $('#initlang').val();	
$("select option[value="+initlang+"]").attr("selected","selected");
</script>
