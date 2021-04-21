<?php 
use yii\helpers\Url;

 if($paypalSettings['paypalType'] == 2){
 	echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='get' id='mobile-paypal-form'>";
 }elseif($paypalSettings['paypalType'] == 1){
 	echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='get' id='mobile-paypal-form'>";
 } 	
 ?>
	<input type="hidden" name="business" value="<?php echo $productModel['sellerpaypalId']; ?>"/>
	<input type="hidden" name="cmd" value="_xclick" /> 
	<input type="hidden" name="upload" value="1">
	<input type="hidden" name="no_note" value="1" />
	<input type="hidden" name="lc" value="UK" />
	<input type="hidden" name="currency_code" value="<?php echo $productModel['currency']; ?>" />
	<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
	<input type="hidden" name="item_name" value="<?php echo $productModel['name']; ?>"/>
	<input type="hidden" name="item_number" value="<?php echo $productModel['productId']; ?>"/>
	<input type="hidden" name="amount" value="<?php echo $price; ?>">
	<input type='hidden' name='custom' value='<?php echo $userModel['email']."-_-".$orders['orderId']."-_-".$orders['shippingAddress']."-_-".$orderitem['itemSize']."-_-".$orders['discountSource']; ?>'>
	<?php if(!empty($shippingAddresses)) { ?>
<!-- 	<input type="hidden" name="address_override" value="1">
    <input type="hidden" name="first_name" value="<?php echo $shippingAddresses->name; ?>">
    <input type="hidden" name="last_name" value="<?php echo $shippingAddresses->nickname; ?>">
    <input type="hidden" name="address1" value="<?php echo $shippingAddresses->address1; ?>">
    <input type="hidden" name="city" value="<?php echo $shippingAddresses->city; ?>">
    <input type="hidden" name="state" value="<?php echo $shippingAddresses->state; ?>">
    <input type="hidden" name="zip" value="<?php echo $shippingAddresses->zipcode; ?>">
    <input type="hidden" name="country" value="<?php echo $shippingAddresses->country; ?>"> -->
    <?php } ?>
    <input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="cancel_return" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl('orders/scroworders').'?status=delivered'; ?>">
	<input type="hidden" name="return" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl('orders/scroworders').'?status=approved'; ?>">	
 	<input type="hidden" name="notify_url" value="<?php echo Yii::$app->urlManager->createAbsoluteUrl('orders/ipnprocess'); ?>"/>
 	
</form> 

