<?php

// Company: Hitasoft Tech.
// Author: AK ASATH.  
// Version: Joysale 4.0 
// Date: 26 MAY 2019.

use yii\web\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\FileHelper;
$baseUrl = Yii::$app->request->baseUrl; 

$sitesetting = yii::$app->Myclass->getSitesettings();
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82', '5e82de'); 

?>

<?php if($initialLoad == 0) { ?>
<div class="slider container-fluid section_container">
 <div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="row product_align_cnt">
		<!-- #fh5co-main Starts  -->
		<div id="fh5co-main">
       <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		  <!-- #fh5co-board Starts  -->
		  <div class="" id="fh5co-board" data-columns>
<?php } ?>
		  	<?php

	  	 	$productDetails = array();
	  	 	$adsIndex = 0; 
			$setTab = rand(0,1);
			$currentRow = 1; 

			// Total Products Count without ads. 
			$totalProductCount = count($products);
			//echo $totalProductCount;die;
			// Total Ads count with Limit 8.
			$totalAdsCount = count($adsProducts);

			$adsPosition = ($totalAdsCount > 0) ? rand(1,3) : 0;
			// Total Products Normal + Ads
			$productCountList = $totalProductCount + $totalAdsCount;

			//echo $productCountList;die;
			// Number of rows
			$productRows = intval($totalProductCount / 3);
			$loopFlag = 0;

			$adsExceedFlag = ($productRows < $totalAdsCount) ? 2 : 1; 
			$adsCountInc = 0; 

			do {				
				if ($loopFlag == 1) {  
					// Ads count exceeds normal product rows (3 + 1).					
					if($productRows < $adsCountInc) {
						++$adsIndex;  
					}
					$products = array_slice($adsProducts, $adsIndex); 
					$totalProductCount = count($products);
					$adsPosition = 0;
				}

				if($totalProductCount > 0) {
					foreach($products as $productKey => $productShuffle):
						$soldData = ""; 
						
						$randKey = array_rand($colorArray);
						$colorvalue = "#".$colorArray[$randKey];
						$repeatFlag = 0; 

						$innerLoop = $innerFlag = ($currentRow == $adsPosition) ? 2 : 1;  // 1 - normal  // 2 - normal + ad;

						while ($repeatFlag < $innerLoop) {	  
							if ($innerFlag == 2 && ($setTab == 0 || ($setTab == 1 && $repeatFlag == 1))) {    
								$product = $adsProducts[$adsIndex];
								$innerFlag = 0;
								$adsPosition = 0;
								++$adsCountInc;
							} else {
								$product = $productShuffle; 
							}

							/////// Product Show Process starts ///////
							if(!empty($product)) { 
								$productContent = "";
								$image = yii::$app->Myclass->getProductImage($product->productId); 
								if(!empty($image)) {
									$img = $product->productId.'/'.$image;
									$mediapath = realpath ( Yii::$app->basePath . "/web/media/item/".$img);
									$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
									
									if(file_exists($mediapath)) {
										$img = $img;
									} else {
										$img = $sitesetting->default_productimage;
										$img =Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
									}
								} else {
									$img = $sitesetting->default_productimage;
									$img = Yii::$app->urlManager->createAbsoluteUrl("/media/item/".$img);
								}

								$now = time(); 
								$your_date = $product->createdDate; 
								$days = yii::$app->Myclass->getElapsedTime($your_date);
								 
								//Start columncls  
								$productContent .= '<div class="columncls">'; ?>
								<?php
									$productContent .= '<div class="item product">';
										$productContent .= '<div class="grid cs-style-3 no-hor-padding">';
											$productContent .= '<div class="image-grid col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';

												///// Image Anchor Source Starts
						    					$productContent .= '<a href="'.Yii::$app->urlManager->createAbsoluteUrl('products/view').'/'.yii::$app->Myclass->safe_b64encode($product->productId.'-'.rand(0,999)).'/'.yii::$app->Myclass->productSlug($product->name).'">';

						    						//filter-img -> class
													$productContent .= '<div class="item-img productimage" style="/*background-image: url(\''.$img.'\');*/ background-color:'.$colorvalue.';">'; 												
														$productContent .= '<img src="'.$img.'" alt="img" class="imgcls"><span class="day-count">'.$days.' '.Yii::t('app','ago').'</span>';
													$productContent .= '</div>';
												$productContent .= '</a>';
												///// Image Anchor Source Ends

												if ($product->quantity == 0 || $product->soldItem == 1) {
													$productContent .= '<span class="sold-out">'.Yii::t('app','Sold Out').'</span>'; 
												} elseif (isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == '1' && $product->promotionType == '2') {
													$productContent .= '<span class="item-urgent">'.Yii::t('app','Urgent').'</span>';
												} elseif (isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == '1' && $product->promotionType == '1') {
													$productContent .= '<span class="item-ad">'.Yii::t('app','Ad').'</span>';
												} 

												$productContent .= '<div class="rate_section pro_details">
													<div class="price bold col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding pro_price 22" >';
														if($product->instantBuy == 0 && $product->price == 0) { 
															$productContent .= '<span class="label_ga">'.Yii::t('app','Giving Away').'</span>';
														} else { 

															if (isset($_SESSION['language'])  && $_SESSION['language']== 'ar'){

															$productContent .= yii::$app->Myclass->getArabicFormattingCurrency($product->currency,$product->price);
														}
														else
														{
															$productContent .= yii::$app->Myclass->getFormattingCurrency($product->currency,$product->price);
														}
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
								</div>';
								// Ends Columncls

								if($initialLoad == 0){
									echo $productContent; 
								} else {
									$productDetails[] = $productContent; 
								} 
							}
							/////// Product Show Process Ends ///////
							++$repeatFlag; 
						} 
						// END OF WHILE LOOP 

						if($currentRow == 3 && $loopFlag == 0) { 
							$currentRow = 1;
							if($adsIndex < $totalAdsCount) {
								$adsPosition = rand(1,3); 
								$setTab = rand(0,1);
								++$adsIndex;
							} 
						} else { 
							++$currentRow;
						}
					endforeach;   
				}
				++$loopFlag;
			} while($loopFlag < $adsExceedFlag);
			?>

<?php 

if($initialLoad == 1) { 
	echo Json::encode($productDetails); 
} else if($initialLoad == 0) { ?>
		  </div> 
		  <!-- #fh5co-board Ends  -->
		 </div>
		</div>
		<!-- #fh5co-main Ends  -->
	</div>
  </div>
 </div> 
</div> 

	<?php if($productCountList >= 32) { ?> 
		<div style="height: 150px;  display: flex;" id="heightt">
			<div class="more-listing col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="UrgentFilterLoadmore">

				<div class="classified-loader" id="loaader" style="width: 60px;">
					<div class="cssload-loader"></div>
				</div>
				<a class="loadmorenow load">
					<div class="load-more-icon" onclick="home_more()"></div>
					<div class="load-more-txt"><?=Yii::t('app','More Listing')?></div>
				</a>
			</div> 
		</div>
	<?php  } ?>  

	<script type="text/javascript">
		function home_more()
		{ 
			var lat = $('#map-latitude').val();
			var lon = $('#map-longitude').val();
			var searchType = '<?php echo $searchType; ?>';
			var distance = <?php echo $searchList; ?>;  
			//alert(offset);
			$.ajax({
		        url: '<?=Yii::$app->getUrlManager()->getBaseUrl()?>/',
		        type: "POST",
		        dataType : "html",
		        data: {"offset": offset, "adsarray": adsarray,"lat" : lat,"lon" : lon, "searchType": searchType, "distance": distance 
		        },
		        beforeSend: function(data) {
						//$(".more-listing").show();
						$(".loadmorenow").hide();
						$("#loaader").show();
					},
		         success: function (response) {
		         	$("#loaader").hide();  
		       		var grid = document.querySelector("#fh5co-board");
		       		var data = response.trim();
		       		data = data.split("~#~");

		       		var offsetInc = parseInt(data[0].trim());
		       		adsarray = data[1].trim(); 
		       		var count = parseInt(data[2].trim());

		       		var contentData = eval(data[3].trim()); 
		       		for(var i = 0; i < contentData.length; i++){  
							var item = document.createElement("div");
							salvattore["append_elements"](grid, [item]);
							item.outerHTML = contentData[i];
						} 
						offset = offset + offsetInc; 
						if(count >= 32) {
							$(".loadmorenow").show(); 
						}
		        },
		    }); 
		}
	</script>
<?php } ?> 

