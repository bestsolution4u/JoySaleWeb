<?php
 use yii\helpers\Html;
 use yii\helpers\Url;
 use yii\helpers\Json;
 ?>
<?php  $i=0;
$siteSettings = yii::$app->Myclass->getSitesettings();
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
				'5e82de');

foreach($products as $product):
$soldData = '';
$randKey = array_rand($colorArray);
$colorvalue = "#".$colorArray[$randKey];
$productId = $product->productId;
$image = yii::$app->Myclass->getProductImage($productId);
if(!empty($image)) {
	$img = $product->productId.'/'.$image;
	
	$img = Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$img);
// if (!file_exists($img)) {
// 	$img= Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$siteSettings->default_productimage);
// }
	$imageSize = getimagesize($img);
	$imageWidth = $imageSize[0];
	$imageHeigth = $imageSize[1];
	if ($imageWidth > 300 && $imageHeigth > 300){
		$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$product->productId.'/'.$image);
	}
	$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$product->productId.'/'.$image);
		if(file_exists($mediapath)) {
						$img = $img;
		} else {
					$img =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$siteSettings->default_productimage);
		}



} 
else {
	$img = $siteSettings->default_productimage;
	$img = Yii::$app->urlManager->createAbsoluteUrl('/media/item/'.$img);
}


if(isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '2'){
	$promotions='1';
	$soldData = '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '1') {
	$promotions='1';
	$soldData = '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
}elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1" && $product->promotionType == '3') {
	$promotions='0';
}
elseif (isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "0") {
	$promotions='0';
}

if ($product->quantity == 0 || $product->soldItem == 1){
	$soldData = '<div class="sold-out list abs-sold-out"> '.Yii::t('app','Sold Out').'</div>';
	$promotions='1';
}
?>

<div class="profile-listing-product product-padding col-xs-12 col-sm-6 col-md-4 col-lg-4">
<div onclick="" class="image-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="prod-1" dataid="<?php echo $productId; ?>" style="background:url('<?php echo $img; ?>') no-repeat center center;background-size: cover;background-color:<?php echo $colorvalue; ?>;border-radius: 6px;">
   <div class="imghoverproductlist"> <!--profile-listing-product-hover col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding-->
   	<div class="profile-listing-opacity-bg col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
	<div class="product_view">
	<a style="text-decoration: none;" href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name); ?>">
			<div class="productimage" style="background-image: url('<?php echo $img; ?>');background-color:<?php echo $colorvalue; ?>;">
			<?php echo $soldData; ?>
			</div>
		</a>
		<div class="listingProducts">
				<span><?=ucfirst($product->name)?></span>
		</div>
		<div class="listingDotsPosition"  data-toggle="dropdown" aria-expanded="false">
		<div class="listingDotsHolder">										
			<div>
				<div class="listingDots"></div>
				<div class="listingDots"></div>
				<div class="listingDots"></div>
			</div>
		</div>
		</div>
		<ul class="dropdown-menu msg_dropdown dropdownf-position resp-disp-popup ">			
		<?php if(Yii::$app->user->id == $product->userId) { ?>
		<?php  if($promotions == '1'){ ?>
           <li class="user_pactive">
				<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>><?php echo Yii::t('app','View Listing');  ?>	</a>
			</li>
			<li>
				<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/update').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Edit Listing');  ?></a>
			</li>
				<li>
				<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/insights?id=').yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Insights');  ?></a>
			</li>
        <?php }else{  ?> 
			<li class="user_pactive">
				<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>><?php echo Yii::t('app','View Listing');  ?>	</a>
			</li>
			<li>
				<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/update').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Edit Listing');  ?></a>
			</li>

				<li>
				<a   href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/insights?id=').yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)); ?>><?php echo Yii::t('app','Insights');  ?></a>
			</li>
        <?php
		if(isset($siteSettings->promotionStatus) && $siteSettings->promotionStatus == "1")
		{
		?>
        	<li><a href="javascript:void(0);" data-toggle="modal" data-target="#modal1" onclick = "showListingPromotion('<?php echo $product->productId; ?>')">
			<?php echo Yii::t('app','Promote');  ?>
			</a> </li>          		
		     <?php	} ?>
			<?php }  }else{ ?>


				<li> 
					
			<a href=<?php echo Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name)?>>
			<?php echo Yii::t('app','View Listing');  ?>
			</a></li>
			<?php } ?>
	
			</ul>

	   </div>
		</div>
	</div>
						
</div>
			<?php $i++;
			endforeach; ?>

			<style type="text/css">
				  .resp-disp-popup
     {
     	z-index: 998;
     }
			</style>





