<?php
use yii\web\Request;
 use yii\helpers\Html;
 use yii\helpers\Url;
 use yii\helpers\Json;
 use yii\helpers\FileHelper;

 $baseUrl = Yii::$app->request->baseUrl;

$search = (isset($_GET['search'])) ? trim($_GET['search']) : "";

$sitesetting = yii::$app->Myclass->getSitesettings();
$colorArray = array('50405d', 'f1ed6e', 'bada55', '5eaba6', 'ab5e63', '5eab86', 'deba5e', 'de5e82',
	'5e82de'); 
	?>
<?php echo $echo_val;?>
<?php 
	if($initialLoad == 0) { ?>
		<?php 
			$style = ($worldData == 1) ? "display: block;" : "display: none;"; 
		?>
		<div class="row show-world-widee" style="<?= $style; ?> margin-top:15px;" id="showUrgentStatus"> 
			<div class="no-item col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div>		
					<?php if($category!="allcategories" && $category!="") { ?>			
						<span class="no-item-text col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php echo Yii::t('app','Sorry! No item found'); ?>.
						</span>
						<span class="world-wide col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php echo Yii::t('app','We are showing related items');?>
						</span>

						<div class="back-botton col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php if($locationReset == 1) { ?>
								<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>" class="back-btn"  onclick="return gotogetLocationDataHome();">
									<?php echo Yii::t('app','Home'); ?>
								</a>
							<?php } else { ?>
								<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>" class="back-btn"  onclick="">
									<?php echo Yii::t('app','Home'); ?> 
								</a>
							<?php } ?> 
						</div>
					<?php } else { ?>
						<span class="no-item-text col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php echo Yii::t('app','Sorry! No item found'); ?>.
						</span>
						<span class="world-wide col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<?php echo Yii::t('app','We are showing world wide');?>
						</span>

						<div class="back-botton col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php if($locationReset == 1) { ?>
								<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>" class="back-btn"  onclick="return gotogetLocationDataHome();">
									<?php echo Yii::t('app','Home'); ?>
								</a>
							<?php } else { ?>
								<a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>" class="back-btn"  onclick="">
									<?php echo Yii::t('app','Home'); ?> 
								</a>
							<?php } ?> 
						</div>

					<?php } ?>		 			
				</div>
			</div>
		</div>

		<div class="slider container-fluid section_container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="row product_align_cnt">
						<div id="fh5co-main">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="" id="fh5co-board" data-columns> 
<?php } ?>

	<?php 
		$productDetails = array();
		

		if((!empty($products) && count($products) > 0) ) {
			foreach($products as $productKey => $product):
				$soldData = ""; 
				$productContent = "";

				$randKey = array_rand($colorArray);
				$colorvalue = "#".$colorArray[$randKey];

				if(!empty($product)) { 		
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
										$productContent .= '<div class="item-img productimage filter-img" style="/*background-image: url(\''.$img.'\');*/ background-color:'.$colorvalue.';">';  												
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
					</div>';
					// Ends Columncls
					if($initialLoad == 0 || $loadMore == 1){
						echo $productContent;  
					} else {
						$productDetails[] = $productContent; 
					}
				}
			endforeach;  
		}
	?>


	<?php 
	if($initialLoad == 1 && $loadMore == 0) {  
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
		<input type="hidden" value="<?php echo $sub_subcategory;?>" id="lResult_sub_subcategory" />
		<input type="hidden" value="<?php // $lat;?>" id="lResult_lat" />
		<input type="hidden" value="<?php // $lon;?>" id="lResult_lon" /> 

		<?php 
			$moreAds = ($productcount >= 32) ? "display: block;" : "display: none;";   
		?>
			<div style="height: 150px;  display: flex; <?= $moreAds; ?>" id="heightt"> 
				<div class="more-listing col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="UrgentFilterLoadmore">

					<div class="classified-loader" id="loaader" style="width: 60px;">
						<div class="cssload-loader"></div>
					</div>
					<a class="loadmorenow load">
						<div class="load-more-icon" onclick="load_more()"></div>
						<div class="load-more-txt"><?=Yii::t('app','More Listing')?></div> 
					</a>
				</div> 
			</div>

		<script type="text/javascript">

			var loadClicks = 0;
			function load_more() {

				var whereto = $("#pac-input").val();
				var lat = $('#map-latitude').val();
				var lon = $('#map-longitude').val();
				var price = $('#SliderPrice').val();
				var searchval = $("#searchval").val();
				
				var distanceval = $("#Sliders2").val();
				var PriceValue = price;

				if (typeof distanceval == 'undefined')
				{
					distanceval = "";
					var distance = "";
				} else {
					distanceval = distanceval.split(";");
					var distance = distanceval[1];
				}

				var category = $('.category-filter').val();
				var search = $("#searchval").val();
				var subcategory = $('.subcategory-filter').val();
				var sub_subcategory = $('.sub_subcategory-filter').val();
				var urgent = $('.urgent-filter').val();
				var ads = $('.ads-filter').val();
				var lth = $('.lth').val();
				var htl = $('.htl').val();
				var last24hrs = $('.last24hrs').val();
				var last7days = $('.last7days').val();
				var last30days = $('.last30days').val();
				var dropdownvalues = $('#dropdownvalues').val();
				var multilevelvalues = $('#multilevelvalues').val();
				var rangevalues = $('#rangevalues').val();
				var loadTracker = 1;

				if (urgent == "0") {
					urgent = '';
				}
				if (ads == "0") {
					ads = '';
				}
				if (lth == "0") {
					lth = '';
				}
				if (htl == "0") {
					htl = '';
				}
				if (last24hrs == "1") {
					posted_within = 'last24hrs';
				}
				else if (last7days == "1") {
					posted_within = 'last7days';
				}
				else if (last30days == "1") {
					posted_within = 'last30days';
				}
				else
				{
					posted_within = '';
				}


				if (loadTracker == 1) {
					loadTracker = 0;

					$.ajax({
						url: '<?=Yii::$app->getUrlManager()->getBaseUrl(); ?>/site/loadresults/',
			        type: "POST",
			        dataType : "html",
						data: {
							"offset": offset,
							"lat": lat,
							"lon": lon,
							"distance": distance,
							"loadMore": 0,  
							"category": category,
							"search": search,
							"subcategory": subcategory,
							"sub_subcategory": sub_subcategory,
							"urgent": urgent,
							"ads": ads,
							"lth": lth,
							"htl": htl,
							"posted_within": posted_within,
							"productcond" : loadProductCond, 
							"price": price,
							"dropdownvalues":dropdownvalues,
							"multilevelvalues":multilevelvalues,
							"rangevalues":rangevalues,
						},
						beforeSend: function () {
							$(".loadmorenow").hide();
							$("#loaader").show();
						},
						success: function (response) {
							$("#loaader").hide(); 
			       		var grid = document.querySelector("#fh5co-board");
			       		var data = response.trim();
			       		data = data.split("~#~");

			       		var count = parseInt(data[0].trim());
			       		var oldCount = parseInt(data[1].trim());

			       		var contentData = eval(data[2].trim());  
			       		for(var i = 0; i < contentData.length; i++){  
								var item = document.createElement("div");
								salvattore["append_elements"](grid, [item]);
								item.outerHTML = contentData[i];
							} 
				
							offset = offset + count;  

							if(count >= 32) {   
								$(".loadmorenow").show(); 
							} 							
							loadTracker = 1;
						} 
					});
				}
			} 
		</script>
	<?php } ?> 