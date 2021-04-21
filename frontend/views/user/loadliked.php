<?php 
	if(count($products) == 0)
		$empty_tap = " empty-tap ";
	else
		$empty_tap = ""; ?>
<script>
var offset = 15;
var limit = 15;
</script>
<!--Recent activity-->
						<div id="recent-activity" class="profile-tab-content tab-pane fade in active col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding <?php echo $empty_tap; ?>">

							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<ul class="recent-activities-tab nav nav-tabs">
								  <li class="active" id="like_active">
								  		<a href="javascript:void(0)" onclick="return getliked('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Liked') ?>  </a>
								  </li>
								  <li class="" id="follow_active">
								 		<a id="followerclk" href="javascript:void(0)" onclick="return getfollower('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Followers') ?> </a>
								 		<input type="hidden" id="followersclk" name="followerclk" value="<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>">
								  		
								  </li>
								  <li class="" id="following_active">
								  		<a id="followingclk" href="javascript:void(0)" onclick="return getfollowing('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)); ?>')"> <?php echo Yii::t('app','Followings') ?> </a>
								  </li>
								</ul>

							</div>
							<div class="recent-activities-tab-content tab-content">
							<!--Liked-->
					

								<div id="liked" class="tab-pane fade in active">
									<div class="center profile-listing-product-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										
											<?php if (count($products) == 0){ ?>
											<div class="modal-dialog modal-dialog-width">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-bottom:100px;">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<div class="payment-decline-status-info-txt decline-center" ><img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl("/images/empty-tap.jpg");?>"></br><span class="payment-red"><?php echo Yii::t('app','Sorry...'); ?></span> <?php echo Yii::t('app','You have not liked any products.'); ?></div>
													<div class="text-align-center col-lg-12 no-hor-padding"><a class="center-btn payment-promote-btn login-btn" href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Go to home'); ?></a></div>
												</div>
											</div>
											</div>
											<?php }else{ ?>

												<div id="products" style="margin-top:0px !important;">
		
<?php $i=0;
$siteSettings = yii::$app->Myclass->getSitesettings();
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
				'5e82de'); 
foreach($products as $product):
$soldData = '';
//echo $action;
$randKey = array_rand($colorArray);
$colorvalue = "#".$colorArray[$randKey];
$productId = $product->productId;
$image = yii::$app->Myclass->getProductImage($productId);
//echo $image;die;

if(!empty($image)) {
	$img = $product->productId.'/'.$image;
	$img = Yii::$app->urlManager->createAbsoluteUrl('media/item/'.$img);
	$imageSize = getimagesize($img);
	$imageWidth = $imageSize[0];
	$imageHeigth = $imageSize[1];
	if ($imageWidth > 300 && $imageHeigth > 300){
		$img = Yii::$app->urlManager->createAbsoluteUrl("media/item/".$product->productId.'/'.$image);
	}
/*	$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$product->productId.'/'.$image);
		if(file_exists($mediapath)) {
						$img = $img;
		} else {
					$img =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
		}

*/

} 
else {
	$img = $siteSettings->default_productimage;
	$img = Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$img);
}


if(isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '2'){
	$soldData = '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '1') {
	$soldData = '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '3') { 
}

if ($product->quantity == 0 || $product->soldItem == 1){
	$soldData = '<div class="sold-out list abs-sold-out"> '.Yii::t('app','Sold Out').'</div>';
}
?>

<div class="profile-listing-product product-padding col-xs-12 col-sm-6 col-md-4 col-lg-4">
   <div class="image-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="prod-1" dataid="<?php echo $productId; ?>" style="background:url('<?php echo $img; ?>') no-repeat center center;background-size: cover;background-color:<?php echo $colorvalue; ?>;border-radius: 6px;">
   	<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name); ?>">
	<div class="product_view" style="height:100%">
	    <div class="productimage"> <?php echo $soldData; ?> </div>
				
			</div>
			</a>
	</div>

</div>
			<?php $i++;
			endforeach; ?>




												</div>


									<!-- <div class="load-more-txt">More Users</div> -->

								<?php if(count($products) >= 15) {
								 ?>
									<div class="load-more-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="liked-loader classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
								<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more_liked('<?php echo yii::$app->Myclass->safe_b64encode($user->userId.'-'.rand(0,999)) ?>')"></div>
									<div class="load-more-txt"><?php echo Yii::t('app','Load More'); ?></div>
							
								</a>
						</div>
								<?php  } } ?>

										<?php ?>

									</div>
								</div>

							
							</div>
						</div>
		


						</div>
<script type="text/javascript">
	function load_more_liked(id)
{

	$.ajax({
        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/user/moreloadliked/',
        type: "POST",
        dataType : "html",
        data: {
           "limit": limit, "offset": offset, "id" : id
        },
        beforeSend: function(data){
			$(".liked-loader").show();
			$(".load").hide();
				},
        success: function (response) {

       	$(".liked-loader").hide();
		$(".load").show();
			 var output = response.trim();

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