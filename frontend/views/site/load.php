<?php
 use yii\web\Request;
 use yii\helpers\Html;
 use yii\helpers\Url;
 use yii\helpers\Json;
 use yii\helpers\FileHelper;
 $baseUrl = Yii::$app->request->baseUrl;

if (!isset($locationReset)) {
	$locationReset=0;
}
if (!isset($catrest)) {
	$catrest=0;
}


$urgentPrdCount=1;
$i=0;


$sitesetting = yii::$app->Myclass->getSitesettings();
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
	'5e82de'); 
?>



	<?php 
	if (!isset($_POST['loadMore']) && !isset($_POST['loadData'])){ 


		?>
		<input type="hidden" id="catrest" value="<?php echo $catrest; ?>" />

		
		<?php if ((isset($locationReset) && $locationReset == 0) && ($catrest == 0)){ ?>
			<div class="row show-world-wide" style="display: none;">
				<?php }else{ ?>
					<div class="row show-world-wide">
						<?php  } ?>
						<div class="no-item col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div id="new-search">
						

					
					<div class="back-botton col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>" class="back-btn"  onclick="return gotogetLocationDataHome();">
							<?php echo Yii::t('app','Home'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>





		<?php 
		if((!empty($products) && count($products) > 0) || (!empty($adsProducts) && count($adsProducts) > 0)){ 

			?>
			<div class="slider container-fluid section_container">


				<!-- responsive search filter only -->


	<!-- End of the responsive search filter only -->

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<!-- Bottom to top-->
			<div class="row product_align_cnt">
				<div id="fh5co-main">

					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="" id="fh5co-board" data-columns>
							<?php } ?>


							<?php } ?>

							<?php
						
							if($urgentPrdCount==0 && $_POST['urgent']==1)
								{?>
								<input type="hidden" id="urpro_count" readonly value="<?php echo $urgentPrdCount;?>"/>
								<input type="hidden" id="urpro_status" readonly value="<?php echo $_POST['urgent']; ?>"/>

									<?php }?>
									<?php 
								  
									if((!empty($products) && count($products) > 0) || (!empty($adsProducts) && count($adsProducts) > 0)){

										$productDetails = array();
										$adsIndex = 0;$adsPosition = rand(1,3);
										$currentRow = 0;
											if($productcount==0)
										{

									$products = yii::$app->Myclass->allproducts();

										} 
										elseif(count($products) < count($adsProducts)){
											$productSwap = $products;
											$products = $adsProducts;
											$adsProducts = $productSwap;
										} 

						

										foreach($products as $productKey => $product):
											$productContent = "";

										$currentRow++;
										if($currentRow == 5)
											$currentRow = 1;
	
										if($currentRow == $adsPosition && !empty($adsProducts[$adsIndex])){
											$adsproduct = $adsProducts[$adsIndex];
	
											$soldData = '';
											$randKey = array_rand($colorArray);
											$colorvalue = "#".$colorArray[$randKey];
											$image = yii::$app->Myclass->getProductImage($adsproduct->productId);

											if(!empty($image)) {
											
												$img = $image;
												$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$adsproduct->productId."/".$img);						
												$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$adsproduct->productId."/".$img);
									        	$imageWidth = 250;
												$imageHeigth = 250;
												if ($imageWidth > 300 && $imageHeigth > 300){
												}

												if(file_exists($mediapath))
												{
													$img = $img;
												}
												else
												{
													$img = 'default.jpg';
													$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
												}
											} else {
												$img = 'default.jpg';
												$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
											}

			$now = time(); // or your date as well
			$your_date = $adsproduct->createdDate; //strtotime("2010-01-01");
			$days = yii::$app->Myclass->getElapsedTime($your_date);
			$productContent .= '<div class="columncls">'; ?>
			<?php
			$productContent .= '<div class="item product">';
			$productContent .= '<div class="grid cs-style-3 no-hor-padding">';
			$productContent .= '<div class="image-grid col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
			$productContent .= '<a href="'.Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($adsproduct->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($adsproduct->name).'" class="fh5co-board-img">';
			if($filterclass == 1) 
		{ 
				$productContent .= '<div class="item-img productimage filter-img" style="/*background-image: url(\''.$img.'\');*/ background-color:'.$colorvalue.';">';
				$filterclass = 1;
		}else
		{
				$productContent .= '<div class="item-img productimage" style="/*background-image: url(\''.$img.'\');*/ background-color:'.$colorvalue.';">';
				$filterclass = 0;
		}
		
			$productContent .= '<img src="'.$img.'" alt="img" class="imgcls"><span class="day-count">'.$days.' '.Yii::t('app','ago').'</span></div></a>';
			if ($adsproduct->quantity == 0 || $adsproduct->soldItem == 1){
				$productContent .= '<span class="sold-out">'.Yii::t('app','Sold Out').'</span>';
			}elseif(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1" && $adsproduct->promotionType == "2"){
				$productContent .= '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
			}elseif (isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == '1' && $adsproduct->promotionType == '1'){
				$productContent .= '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
			}
				$productContent .= '<div class="rate_section pro_details">
			<div class="price bold col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pro_price 11" >';
				if($adsproduct->instantBuy == 0 && $adsproduct->price == 0){
					$productContent .= '<span class="label_ga">'.Yii::t('app','Giving Away').'</span>';
				}else {
						if(isset($_SESSION['_lang']) && $_SESSION['_lang'] == 'ar')
						{
							if($adsproduct->price == 0){
									$productContent .= '<span></span> <span class="label_ga">'.Yii::t('app','Giving Away').'</span>';
								} 
							else{

						$productContent .= '<span>'.$adsproduct->price.'</span> <span>'.yii::$app->Myclass->getCurrency($adsproduct->currency).'</span>';
						}
					} else {
						if(isset($adsproduct->price) && $adsproduct->price == 0){
							$productContent .= '<span></span> <span class="label_ga">'.Yii::t('app','Giving Away').'</span>';
								} 
							else{
							$productContent .= '<span>'.yii::$app->Myclass->getCurrency($adsproduct->currency).'</span> <span>'.$adsproduct->price.'</span>';
							}
					}
				}
				$productContent .= '</div>
				<div class="item-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pro_title">
					<a href="'.Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($adsproduct->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($adsproduct->name).'">'.$adsproduct->name.'</a>';
					$productContent .= '</div>';
					$productuser_det = yii::$app->Myclass->getUserDetails($adsproduct->userId);
					$productContent .= '<span class="item-location secondary-txt-color col-xs-12 col-sm-12 col-md-12 col-lg-12 ">'.$adsproduct->location.'</span>
				</div>
			</div>
		</div>
	</div>
</div>	';
if($adsIndex < count($adsProducts)){
	$adsIndex += 1;
	$adsPosition = rand(1,3);
}
if(!isset($_POST['loadData'])){

	echo $productContent;
}else{

	$productDetails[] = $productContent;
}
$productContent = "";
} 
$soldData = '';
$randKey = array_rand($colorArray);
$colorvalue = "#".$colorArray[$randKey];

$image = yii::$app->Myclass->getProductImage($product->productId);

if(!empty($image)) {
	
	$img = $product->productId.'/'.$image;
	$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$img);
	$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
	$imageWidth = 250;
	$imageHeigth = 250;
	if ($imageWidth > 300 && $imageHeigth > 300){
	}

	if(file_exists($mediapath))
	{
		$img = $img;
	}
	else
	{
		$img = 'default.jpg';
		$img =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
	}
} else {
	$img = 'default.jpg';
	$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
}
if ($product->quantity == 0 || $product->soldItem == 1){
	$soldData = '<span class="sold-out">'.Yii::t('app','Sold Out').'</span>';
}

		$now = time(); // or your date as well
		$your_date = $product->createdDate; //strtotime("2010-01-01");
		$days = yii::$app->Myclass->getElapsedTime($your_date);
		$productContent .= '<div class="columncls">'; ?>



		<?php
		$productContent .= '<div class="item product">';
		$productContent .= '<div class="grid cs-style-3 no-hor-padding">';
		$productContent .= '<div class="image-grid col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
	    $productContent .= '<a href="'.Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name).'">';

		  if($filterclass == 1) 
		{ 
				$productContent .= '<div class="item-img productimage filter-img" style="background-color:'.$colorvalue.';">';
				$filterclass = 1;
		}else
		{
				$productContent .= '<div class="item-img productimage" style="background-color:'.$colorvalue.';">';
				$filterclass = 0;
		}
			$productContent .= '<img src="'.$img.'" alt="img" class="imgcls"><span class="day-count">'.$days.' '.Yii::t('app','ago').'</span></div></a>';
			if ($product->quantity == 0 || $product->soldItem == 1){
				$productContent .= '<span class="sold-out">'.Yii::t('app','Sold Out').'</span>';
			}elseif(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1" && $product->promotionType == "2"){
				$productContent .= '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
			}elseif (isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == '1' && $product->promotionType == '1'){
				$productContent .= '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
			}
		$productContent .= '<div class="rate_section pro_details">
		<div class="price bold col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pro_price 22" >';


			if($product->instantBuy == 0 && $product->price == 0){
				$productContent .= '<span class="label_ga">'.Yii::t('app','Giving Away').'</span>';
			}
			else{ 
						if (isset($_SESSION['_lang']) == 'ar')
													$productContent .= yii::$app->Myclass->getArabicFormattingCurrency($product->currency,$product->price);
					 else
													$productContent .= yii::$app->Myclass->getFormattingCurrency($product->currency,$product->price);

				}
		   $productContent .= '</div>
			<div class="item-name col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pro_title">
				<a href="'.Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name).'">'.$product->name.'</a>';
				$productContent .= '</div>';
				$productuser_det = yii::$app->Myclass->getUserDetails($product->userId);
				$productContent .= '<span class="item-location secondary-txt-color col-xs-12 col-sm-12 col-md-12 col-lg-12 ">'.$product->location.'</span>
			</div>
		</div>
	</div>
</div>
</div>	';
$i++;
if(!isset($_POST['loadData'])){
	echo $productContent;

}else{
	$productDetails[] = $productContent;
	
}

endforeach;?>
<?php if (!isset($_POST['loadMore']) && !isset($_POST['loadData'])){

 ?>
</div>
</div>
</div>
</div>

</div>
</div>
</div>
<input type="hidden" value="<?php echo $category; ?>" class="category-filter"/>
<input type="hidden" value="<?php echo $subcategory; ?>" class="subcategory-filter"/>
<input type="hidden" value="<?php echo $sub_subcategory; ?>" class="sub_subcategory-filter"/>
<input type="hidden" value="<?php echo $search; ?>" class="search-filter"/>
<input type="hidden" value="<?php  // $urgent; ?>" class="urgent-filter"/>
<input type="hidden" value="<?php // $ads; ?>" class="ads-filter"/>
<!-- new keywords -->
<input type="hidden" value="<?php echo $search;?>" id="lResult_search" />
<input type="hidden" value="<?php echo $category;?>" id="lResult_category" />
<input type="hidden" value="<?php echo $subcategory;?>" id="lResult_subcategory" />
<input type="hidden" value="<?php // $lat;?>" id="lResult_lat" />
<input type="hidden" value="<?php // $lon;?>" id="lResult_lon" />
<!-- end new keywords -->

<?php }elseif (isset($_POST['loadData'])){
	echo Json::encode($productDetails);
} ?>
<?php
$total = count($products) + count($adsProducts); exit;
?>
<?php if($total >= 32 && !isset($_POST['loadData']) && !isset($_POST['loadMore'])) { ?>
	<div class="more-listing col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="UrgentFilterLoadmore">
		<div class="classified-loader" style="width: 60px;"><div class="cssload-loader"></div></div>
									<a class="loadmorenow load">
									<div class="load-more-icon" onclick="load_more()"></div>
									<div class="load-more-txt"><?=Yii::t('app','More Listing')?></div></a>

	</div>
	<div class="classified-loader">
		<div class="cssload-loader"></div>
	</div>
	<?php  }
}elseif(!isset($loadMore) && isset($_POST['loadMore']) && !isset($_POST['loadData'])){ ?>
	<?php echo $_POST['loadMore'].$_POST['loadData'];?>
	
	
<?php } 

?>
