<div class="paypal-success">
	<div class="paypal-success-icon fa fa-check-circle"></div>
	<div class="paypal-success-message"><?php echo 'Please wait while we redirect you'; ?></div>
	<div class="paypal-loading"><?php echo 'Loading....'; ?></div>
</div>
<div class="paypal-form-container" style="display: none;"></div>
<script type="text/javascript">
	$(document).ready(function(){
		var promotionType = '<?php echo $promotionType; ?>';
		var productId = <?php echo $productId; ?>;
		$.ajax({
			url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/products/promotionpaymentprocess/',
			type: "post",
			dataType: "html",
			data: {'promotionType': promotionType, 'productId': productId},
			success: function(responce){
				$('.paypal-form-container').html(responce);
				$('#paypal-form').submit();
			}
		});
	});
</script>