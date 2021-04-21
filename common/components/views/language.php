<form id="myForm" method="post">
	<div id="language" class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	  <label class="language-select-box-heading"><?php echo Yii::t('app','Language');?>:</label>
	  <select id="language-selector" class="form-control select-box-down-arrow " onchange="callLang()">
	  	<option <?php echo $currentLang == 'en' ? 'selected' : ''; ?> value="en">English</option>
	  	<option <?php echo $currentLang == 'fr' ? 'selected' : ''; ?> value="fr">French</option>
	  	<option <?php echo $currentLang == 'ar' ? 'selected' : ''; ?> value="ar">Arabic</option>
	  </select>
	  <input type="hidden" id="lang" name="_lang" value="<?php echo $currentLang; ?>">
	</div>	
</form>

<script>
function callLang() {
	var language = $('#language-selector').val();
	$("#lang").val(language);
	document.getElementById("myForm").submit();
	return false;
}
</script>

