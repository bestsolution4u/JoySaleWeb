<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 ?>
<script>
var offset = 8;
var limit = 8;
</script>
<?php

?>
<?php
$siteSettings = yii::$app->Myclass->getSitesettings();
	if(count($products) == 0)
		$empty_tap = " empty-tap ";
	else
		$empty_tap = "";
?>
<div id="promotions" class="profile-tab-content tab-pane fade col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding active in <?php echo $empty_tap; ?>">
							<div class="promotion-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="promotion-content">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<ul class="recent-activities-tab nav nav-tabs">
		 <li class="">
								  		<a href="javascript:void(0)" onclick="return geturgent()"> <?php echo Yii::t('app','Urgent'); ?>  </a>
								  </li>
								  <li class="">
								 		<a href="javascript:void(0)" onclick="return getad()"> <?php echo Yii::t('app','AD'); ?> </a>
								  		
								  </li>
								  <li class="active">
								  		<a href="javascript:void(0)" onclick="return getexpired()"> <?php echo Yii::t('app','Expired'); ?> </a>
								  </li>
		</ul>

								
								</div>
								<div class="recent-activities-tab-content tab-content">
									<div id="urgent" class="tab-pane fade in active">
									<?php if(count($products) != 0) { ?>
										<div id="products" class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<!--product-->
										 <!--- Start Load PRomotion -->



<?php
if(isset($products[0]['product'])) {
foreach($products as $key=>$product) {

	$image = yii::$app->Myclass->getProductImage($product['product']->productId);
	$product_id = $product['product']->productId;
	$pdtURL = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".'/'.$product['product']->productId."/".$image);

		$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$product_id . "/" . $image);

						if(file_exists($mediapath)) {
						
							$pdtURL = $pdtURL;
						} else {
						
							$pdtURL =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
						}
	?>
	<div class="promotion-product product-padding col-xs-12 col-sm-6 col-md-3 col-lg-3">
	<a href="javascript:void(0)" onclick="switchVisible_promotion(<?php echo $product['product']->productId; ?>);" class="promotionclick" id="promotiondiv<?php echo $product_id; ?>"><div class="image-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="prod-1" style="background: rgba(0, 0, 0, 0) url('<?php echo $pdtURL; ?>') no-repeat scroll center center / cover ;border: 1px solid #d0dbe5 !important; ">
	</div>
	<div class="promotion-product-info-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
	<div class="promotion-product-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo $product['product']->name; ?></div>
	</div></a>
	</div>
<?php 
}
} else {

	foreach($products as $key=>$product) {
		//if (!in_array($product->productId, $liveproducts)) {
		$getproductData = Yii::$app->Myclass->getProductDetails($product->productId);
		//if($getproductData->promotionType == '3' || $getproductData->promotionType == '2')
		//	continue;
		if($getproductData['productId']!=""){
		$image = Yii::$app->Myclass->getProductImage($product->productId);
		$product_id = $product->productId;
		$pdtURL = Yii::$app->urlManager->createAbsoluteUrl("/media/item".'/'.$product->productId."/".$image);
			$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$product_id . "/" . $image);

						if(file_exists($mediapath)) {
						
							$pdtURL = $pdtURL;
						} else {
						
							$pdtURL =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
						}
		?>
		<div class="promotion-product product-padding col-xs-12 col-sm-6 col-md-3 col-lg-3">
		<a href="javascript:void(0)" onclick="switchVisible_promotion(<?php echo $product->productId; ?>);" class="promotionclick" id="promotiondiv<?php echo $product_id; ?>"><div class="image-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="prod-1" style="background: rgba(0, 0, 0, 0) url('<?php echo $pdtURL; ?>') no-repeat scroll center center / cover ;border: 1px solid #d0dbe5 !important; ">
		</div>
		<div class="promotion-product-info-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<div class="promotion-product-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<?php

        $productDetails = yii::$app->Myclass->getProductDetails($product->productId);
      
		if(isset($productDetails->name) && !empty($productDetails->name))
			echo $productDetails->name;
		else if(isset($productDetails->name) && !empty($productDetails->name))
			echo $productDetails->name;
		else
			echo "";
		 ?></div>
		</div></a>
		</div>
	<?php }}
}?>                  <!--- end Load PRomotion -->
										</div>
									<?php } else { ?>
										<div>
													<div class="col-xs-8 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
														<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<div class="payment-decline-status-info-txt" style="margin: 8% auto 0;"><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry');?>... </span><?php echo Yii::t('app','Yet no product are here');?>.</div>
														</div>
													</div>
												</div>
									<?php }  ?>
									</div>


								</div>

								<?php if(count($products) >= 8) { ?>
							<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="expiredpromotion-loader classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
									<div class="load-more-txt"><?=Yii::t('app','Load More')?></div> 

								<?php if(count($products) >= 8) {
								if(Yii::$app->controller->action->id == 'expiredPromotions') { ?>
								 <?php } 
							} ?>
							</a>
							</div>


						<?php } ?>
							</div>

							<div class="promotion-details col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="promotion-details">
								<div class="profile-tab-content-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<span><?php echo Yii::t('app','Promotion Details'); ?></span>
									<div class="exchange-back-link pull-right col-xs-3 col-sm-3 col-md-3 col-lg-3 no-hor-padding"><a href="#" onclick="switchVisible_promotionback();" id="element1"><?php echo Yii::t('app','Back'); ?></a></div>
								</div>
								<div class="profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<!--row 1-->
									<div class="promotions-details-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									</div>
								</div>
							</div>
						</div>



	</div>
	</div>

<script type="text/javascript">

	
function load_more(id)
{

	$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/expiredpromotions/',
        type: "POST",
        dataType : "html",
        data: {
           "limit": limit, "offset": offset, "id" : id
        },
        beforeSend: function(data){
				$(".expiredpromotion-loader").show();
				$(".load").hide();
				},
        success: function (res) {
        	
            $(".expiredpromotion-loader").hide();
				$(".load").show();
		        var output = res.trim();
						if (output) {
							offset = offset + limit;
				 $("#products").append(output);
						} else {
				        $(".load").html(yii.t('app',"No More Products"));
				       	}
        },
    });
    
}
</script>

