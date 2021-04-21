<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Filtervalues;
use ruskid\nouislider\Slider;
$searchUrl = "";
$categoryUrl = "";
?>
<?php
if((!empty($products) && count($products) > 0)) {
	if(!empty($category) || (isset($_GET['search']))) 
	{
		?>
		<div class="productfileters" style="display: none;">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="filter-btn">
							<a href="#" data-toggle="modal" data-target="#filter-menu"><?php echo Yii::t('app','Filter Menu');?></a>
						</div>
					</div>
					<div class="filtermenu">
						<div class="modal fade in" id="filter-menu" role="dialog">
							<div class="modal-dialog modal-dialog-width">
								<div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<h2 class="login-header-text"><?php echo Yii::t('app','Filters');?></h2>
										<button data-dismiss="modal" class="close login-close" type="button">×</button>
									</div>
									<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>
									<div class="filter-modal col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
										<div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
											<div class="login-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												<div class="resp-filter-menu resp-filter-menus">
													<div class="categories-filter-list filter-search col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Sort By'); ?></span>
														<div class="filters-list">
															<?php
															if(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1") { ?>
																<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $urgentCheck = ""; if(isset($urgent) && $urgent == 1) $urgentCheck = "checked"; ?>
																	<input type="checkbox" name="sortby[]" value="" <?php echo $urgentCheck; ?> class="cust_checkbox urgent" onClick="promotionsearch('urgent')" />
																	<label><?php echo Yii::t('app','Urgent'); ?></label>
																</div>
															<?php } ?>
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php $adsCheck = ""; if(isset($ads) && $ads == 1) $adsCheck = "checked"; ?>
																<input type="checkbox" name="sortby[]" value="" <?php echo $adsCheck; ?> class="cust_checkbox ads" onclick="promotionsearch('ads');" />
																<label><?php echo Yii::t('app','Popular'); ?></label>
															</div>
															<!-- Low to high -->
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php $lthCheck = ""; if(isset($lth) && $lth == 1) $lthCheck = "checked";?>
																<input type="checkbox" name="sortby[]" value="" <?php echo $lthCheck; ?> class="cust_checkbox lth" onclick="pricesearch('lth');" />
																<label><?=Yii::t('app','Low to High')?></label> 
															</div>
															<!-- High to Low -->
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php $htlCheck = ""; if(isset($htl) && $htl == 1) $htlCheck = "checked";?>
																<input type="checkbox" name="sortby[]" value="" <?php echo $htlCheck; ?> class="cust_checkbox htl" onclick="pricesearch('htl');" />
																<label><?=Yii::t('app','High to Low')?></label>
															</div>
														</div>
													</div>
													<?php  
													if (isset($locationReset) && $locationReset != 1) 
													{ 
														$visible="block";
													} else { 
														$visible="none";
													}
													?>
													<div class="categories-filter-list filter-distnc-km mobile-distnce-view col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding filterTool">  
														<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Filter distance')?></span>
														<div class="filters-list filter-distance col-md-12 col-sm-12 col-xs-12 no-hor-padding">
															<div class="filter-align">
																<div class="filtr-hm"></div>
																<div class="layout-slider">
																	<?php
																	if(isset(Yii::$app->session['distance']))
																	{
																		$distance = Yii::$app->session['distance'];
																	} else {
																		$distance = '1';
																	}
																	?>
																	<input id="Sliders3" type="slider" name="price" value="0;<?php echo $distance;?>" />
																</div>
																<div class="filtr-road"></div>
															</div>
														</div>
													</div>  
													<div class="SliderPriceCol SliderPriceColSM categories-filter-list filter-distnc-km mobile-distnce-view col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Price Range'); ?></span>
														<div class="price_slider_mobile col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding filters-list" style="display: block;">
															<input id="SliderPriceSM" type="slider" name="price" value="0;5000" />
															<?php  
															echo Slider::widget([
																'name' => 'test',
																'value' => 50,
																'events' => [
																],
																'pluginOptions' => [
																	'start' => [20],
																	'connect' => false,
																	'range' => [
																		'min' => 0,
																		'max' => 100
																	]
																]
															]);
															?>
															<div class="ranges_values">
																<div class="SliderPrice_min">0</div>
																<div class="SliderPrice_max">5001</div>
															</div>
														</div>
													</div>
													<div class="categories-filter-list filter-search col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Posted Within'); ?></span>
														<div class="filters-list">
															<!-- Last 24 hrs -->
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php $last24hrsCheck = ""; if(isset($last24hrs) && $last24hrs == 1) $last24hrsCheck = "checked";?>
																<input type="checkbox" name="postedwithin[]" value="" <?php echo $last24hrsCheck; ?> class="cust_checkbox last24hrs" onClick="postwithinsearch('last24hrs')" />
																<label><?=Yii::t('app','Last 24 hrs')?></label>
															</div>
															<!-- Last 7 days -->
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php 
																$last7daysCheck = ""; if(isset($last7days) && $last7days == 1) $last7daysCheck = "checked";
																?>
																<input type="checkbox" name="postedwithin[]" value="" <?php echo $last7daysCheck; ?> class="cust_checkbox last7days" onclick="postwithinsearch('last7days');" />
																<label><?=Yii::t('app','Last 7 days ago')?></label>
															</div>
															<!-- Last 30 days -->
															<div class="checkbox checkbox-primary  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<?php $last30daysCheck = ""; if(isset($last30days) && $last30days == 1) $last30daysCheck = "checked";?>
																<input type="checkbox" name="postedwithin[]" value="" <?php echo $last30daysCheck; ?> class="cust_checkbox last30days" onclick="postwithinsearch('last30days');" />
																<label><?=Yii::t('app','Last 30 days ago')?></label>
															</div>
															<!-- all product -->
														</div>
													</div>
													<div class="categories-filter-list filter-search col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
														<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Product Conditions'); ?></span>
														<div class="filters-list">
															<?php
															foreach($productcondn as $key => $productcondition):
																if($productcondition != "empty") {
																	$productclass = str_replace(' ', '-', $productcondition->condition);
																	?>
																	<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																		<?php $allCheck = ""; if(isset($all) && $all == 1) $allCheck = "checked";?>
																		<input type="checkbox" name="sport[]" value="" <?php echo $allCheck; ?> class="cust_checkbox <?php echo $productclass ?> condn" onclick="productcondn('<?php echo $productclass ?>');"/>
																		<label><?=Yii::t('app',$productcondition->condition)?></label>
																	</div>
																<?php }
															endforeach;
															?>
														</div>
													</div>
													<?php
													if(!empty($category) && !empty($attributes))
													{
														foreach($attributes as $attributeKey=>$attributeVal) {
															echo '<div class="categories-menu-list categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
															echo '<span class="for-sale-heading filters-title">'.Yii::t('app',$attributeVal['name']).'</span>';
															echo '</span>';			
															echo '<ul class="filters-list" style="display:none;">';
															$filterVal = array();
															foreach($attributeVal['value'] as $eachVal)
															{
																?>
																<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<a href="javascript:void(0);" onclick="filtersearch('filters', '<?php echo $eachVal['id']; ?>');"><?php echo Yii::t('app',$eachVal['name']); ?></a>
																</li>
																<input type="hidden" name="dropdownvalues" id="dropdownvalues" value="" />
																<input type="hidden" name="multilevelvalues" id="multilevelvalues" value="" />
																<input type="hidden" name="rangevalues" id="rangevalues" value="" />
																<?php
															}
															echo '</ul>';
															echo '</div>';
														}
													} ?>
												</div>	
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php	
		$searchUrl = "";
		if(isset($_GET['search'])) {
			$searchUrl = "?search=".$search;
		}
		if(count($sub_subcat) > 0)
		{
			$categoryName = yii::$app->Myclass->getCategoryName($subcategory);
		}else{
			$categoryName = yii::$app->Myclass->getCategoryName($category);
		}
		if($third_level == 1){
			$categoryName = yii::$app->Myclass->getCategoryName($subcategory);
		}
		?>
		<div id="products" class="col-xs-12 col-sm-12 col-md-12 col-lg-2 margin-top-20 margin-bottom_20 filter-options pro-hor-padding">  
			<button type="button" class="btn btn-info btn-lg filter-modal-btn" data-toggle="modal" data-target="#Filter-modal"><?php echo Yii::t('app','Filter Menu');?></button>
			<div id="Filter-modal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header clearfix">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<span class="filters-resp-title" ><?php echo Yii::t('app','Filters');?></span>
						</div>
						<div class="modal-body clearfix">
							<div class="filter-options">
								<div class="item categories clearfix">
									<div class="grid cs-style-3 no-hor-padding">
										<div class="filterlists">
											<?php if(!empty($subcats)) {  ?>
												<div class="categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
													<span class="for-sale-heading filters-title padding-top-0"><?php 
													echo Yii::t('app',$categoryName); ?></span>
													<ul class="filters-list" style="display:block">
														<?php 
														foreach($subcats as $subcat):
															$subactive = "";
															$subIcon = "";
															if(count($sub_subcat) > 0)
															{
																$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcategory.'/'.$subcat->slug).$searchUrl;
															}else{
																$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcat->slug).$searchUrl;
															}
															if($subcategory == $subcat->slug) {
																$subactive = "active";
																$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category).$searchUrl;
																$subIcon = "<i class='fa fa-times-circle'></i> ";
															}
															if($third_level == 1)
															{
																$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcategory.'/'.$subcat->slug).$searchUrl;
																if($sub_subcategory == $subcat->slug) {
																	$subactive = "active";
																	$subcategoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category.'/'.$subcategory).$searchUrl;
																	$subIcon = "<i class='fa fa-times-circle'></i> ";
																}
															}
															?>
															<?php // if($subcat->subcategoryVisible==1) { ?>
																<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<a class=" btn-lg btn-category <?php echo $subactive; ?>" href="<?php echo $subcategoryUrl; ?>"><?php echo Yii::t('app',$subcat->name); ?>
																</a>
															</li>
															<?php //} ?>
														<?php endforeach; ?>
													</ul>
												</div>
											<?php } else { 
												if($category=='allcategories') 
												{ 
													$categoryName='All categories'; 
												}
												$categoryUrl = Yii::$app->urlManager->createAbsoluteUrl('/category/'.$category);
												if(!empty($category))
													{ ?>
														<div class="categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<ul>
																<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Current Category');?></span>
																<?php if($category=='allcategories') 
																{ 
																	$categorypriority = yii::$app->Myclass->getCategory();
																	foreach ($categorypriority as $key => $allcategory) :
																		if ($allcategory != "empty") {
																			$existproducts = yii::$app->Myclass->checkproductexist($allcategory->categoryId);
																			if($existproducts != 0){
																				?>
																				<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding filters-list" style="display: block;">
																					<a href="<?php echo Yii::$app->urlManager->createAbsoluteUrl('/category/' . $allcategory->slug); ?>" class="btn-category">
																						<?php echo Yii::t('app',$allcategory->name); ?>
																					</a>
																				</li>	
																			<?php  }  }
																		endforeach; }else{ ?>
																			<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding filters-list" style="display: block;">
																				<a href="<?php echo $categoryUrl; ?>" class="btn-category active">
																					<?php echo Yii::t('app',$categoryName); ?>
																				</a>
																			</li>
																		<?php } ?>
																	</ul>
																</div>
															<?php } ?>
														<?php } ?>
														<div class="categories-filter-list filter-search col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Sort By'); ?></span>
															<div class="filters-list">
																<?php 
																if(isset($sitesetting->promotionStatus) && $sitesetting->promotionStatus == "1"){ ?>
																	<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding " style="margin-top:0">
																		<?php $urgentCheck = ""; if(isset($urgent) && $urgent == 1) $urgentCheck = "checked";?>
																		<input type="checkbox" id="urgent" name="sortby[]" value="" <?php echo $urgentCheck; ?> class="cust_checkbox urgent" onClick="promotionsearch('urgent')" />
																		<label><?=Yii::t('app','Urgent')?></label>
																	</div>
																<?php } ?>
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $adsCheck = ""; if(isset($ads) && $ads == 1) $adsCheck = "checked";?>
																	<input type="checkbox" id="ads" name="sortby[]" value="" <?php echo $adsCheck; ?> class="cust_checkbox ads" onclick="promotionsearch('ads');" />
																	<label><?=Yii::t('app','Popular')?></label>
																</div>
																<!-- Low to high -->
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $lthCheck = ""; if(isset($lth) && $lth == 1) $lthCheck = "checked";?>
																	<input type="checkbox" id="lth" name="sortby[]" value="" <?php echo $lthCheck; ?> class="cust_checkbox lth" onclick="pricesearch('lth');" />
																	<label><?=Yii::t('app','Low to High')?></label>
																</div>
																<!-- High to Low -->
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $htlCheck = ""; if(isset($htl) && $htl == 1) $htlCheck = "checked";?>
																	<input type="checkbox" id="htl" name="sortby[]" value="" <?php echo $htlCheck; ?> class="cust_checkbox htl" onclick="pricesearch('htl');" />
																	<label><?=Yii::t('app','High to Low')?></label>
																</div>
															</div>
														</div>
														<?php if (isset($locationReset) && $locationReset != 0){ $visible="block"; } else { $visible="none"; } ?>
														<div class="filter-distnc-km mobile-distnce-view categories-filter-list  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding " style="display:<?php echo $visible; ?>;">
															<div class="filterTool" style="display:<?php //echo $visible;?>;">
																<span class="for-sale-heading filters-title"><?=Yii::t('app','Filter distance')?></span>
																<div class="filter-distance col-md-12 col-sm-12 col-xs-12 no-hor-padding filters-list">
																	<div class="filter-align">
																		<div class="filtr-hm"></div>
																		<div class="layout-slider">
																			<?php
																			if(isset(Yii::$app->session['distance']))
																				$distance = Yii::$app->session['distance'];
																			else
																				$distance = '1';
																			?>
																			<input id="Sliders2" type="slider" name="price" value="0;<?php echo $distance;?>" />
																		</div>  
																		<div class="filtr-road"></div>
																	</div>
																</div>
															</div>
														</div> 
														<!--Price range slider classified version 3 update-->
														<div class="SliderPriceCol categories-filter-list filter-distnc-km mobile-distnce-view col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Price Range')?></span> <!--  range2 -->
															<div class="filters-list" style="display: none;"> 
																<div class="InnerSliderPriceCol col-md-12 col-sm-12 col-xs-12 no-hor-padding " style="padding: 28px 10px 15px 10px">
																	<input id="SliderPrice" type="slider" name="price" value="0;10000" />
																	<div class="ranges_values">
																		<div class="SliderPrice_min_range">0</div>
																		<div class="SliderPrice_max_range">5000+</div>
																	</div>
																</div>
															</div>
														</div>
														<!-- Posted Time Starts Screen -->		
														<div class="categories-filter-list filter-search col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
															<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Posted Within'); ?></span>
															<div class="filters-list">
																<!-- Last 24 hrs -->
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
																	<?php $last24hrsCheck = ""; if(isset($last24hrs) && $last24hrs == 1) $last24hrsCheck = "checked";?>
																	<input type="checkbox" id="last24hrs" name="postedwithin[]" value="" <?php echo $last24hrsCheck; ?> class="cust_checkbox last24hrs" onClick="postwithinsearch('last24hrs')" />
																	<label><?=Yii::t('app','Last 24 hrs')?></label>
																</div>
																<!-- Last 7 days -->
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $last7daysCheck = ""; if(isset($last7days) && $last7days == 1) $last7daysCheck = "checked";?>
																	<input type="checkbox" id="last7days" name="postedwithin[]" value="" <?php echo $last7daysCheck; ?> class="cust_checkbox last7days" onclick="postwithinsearch('last7days');" />
																	<label><?=Yii::t('app','Last 7 days ago')?></label>
																</div>
																<!-- Last 30 days -->
																<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																	<?php $last30daysCheck = ""; if(isset($last30days) && $last30days == 1) $last30daysCheck = "checked";?>
																	<input type="checkbox" id="last30days" name="postedwithin[]" value="" <?php echo $last30daysCheck; ?> class="cust_checkbox last30days" onclick="postwithinsearch('last30days');" />
																	<label><?=Yii::t('app','Last 30 days ago')?></label>
																</div>
																<!-- all product -->
															</div>
														</div>
														<!-- Product contidion search -->
														<?php if(count($productcondn)>0){ ?>
															<div class="categories-menu-list categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Product Conditions'); ?></span>
																<div class="filters-list">
																	<?php
																	foreach($productcondn as $key => $productcondition):
																		if($productcondition != "empty"){
																			$productclass = str_replace(' ', '-', $productcondition->condition);
																			?>
																			<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																				<?php $allCheck = ""; if(isset($all) && $all == 1) $allCheck = "checked";?>
																				<input type="checkbox" name="productcondn[]" id="<?= $productclass; ?>" value="" <?php echo $allCheck; ?> class="cust_checkbox <?php echo $productclass ?> condn" onclick="productcondn('<?php echo $productclass ?>');"/>
																				<label><?=Yii::t('app',$productcondition->condition)?></label>
																			</div>
																			<?php
																		}
																	endforeach;
																	?>
																</div>
															</div> 
														<?php } ?>
														<!-- Product condition search ends -->
													</div>
												</div>
											</div>
											<!-- Posted Time Ends Screen --> 
											<?php
											if($category != 'allcategories' && count($attributes)>0)
											{
												?>
												<div class="item categories clearfix margin-top-20">
													<div class="grid cs-style-3 no-hor-padding">
														<div class="categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-padding">
															<span class="for-sale-heading filters-title"><?php echo Yii::t('app','Advanced Search');?></span>
															<div class="advancedsearchlist filters-list" style="display: block;">
																<?php
																if(!empty($category) && !empty($attributes))
																{
																	$rangeArray = array();
																	foreach($attributes as $attributeKey=>$attributeVal)
																	{
																		echo '<div class="boreder-btm SliderPriceCol categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
																		echo '<span class="for-sale-heading filters-title">'.Yii::t('app',$attributeVal['name']).'</span>';
																		echo '</span>';
																		echo '<div class="filters-list" >';
																		if($attributeVal['type'] == 'range')
																		{	
																			$sliderhidden_val['id'] = $attributeVal['id'];
																			$sliderhidden_val['value'] = $attributeVal['forrange'];

																			$rangeArray[] = $attributeVal['id'];
																			$attributeId = strtolower(str_replace(' ', '_', $attributeVal['name']).'_'.$attributeVal['id']);

																			$filterVal = array();
																			$splitranges = explode(';', $attributeVal['forrange']);
																			echo '<input type="hidden" id="sliderhiddenattribute_'.$attributeVal['id'].'" name="sliderhiddenattribute[]" value="">';

																			echo '<div class="InnerSliderPriceCol col-md-12 col-sm-12 col-xs-12 no-hor-padding "
																			style="padding: 15px 10px 15px 10px">
																			<input id="'.$attributeId.'" type="slider" name="'.$attributeVal['name'].'"
																			value="'.$attributeVal['forrange'].'" />
																			<div class="ranges_values">
																			<div id="sliderhiddenattribute_min_range_'.$attributeId.'">'.$splitranges[0].'</div>
																			<div id="sliderhiddenattribute_max_range_'.$attributeId.'">'.$splitranges[1].'</div>
																			</div>
																			</div>';																	$rangecount++;
																		} else {
																			echo '<ul>';
																			$filterVal = array();
																			foreach($attributeVal['value'] as $child => $eachVal)
																			{ 
																				?>
																				<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																					<?php
																					if($attributeVal['type'] == 'multilevel')
																					{
																						$fieldName = 'multilevel[]';
																					}elseif($attributeVal['type'] == 'dropdown'){
																						$fieldName = 'dropdown[]';
																					}
																					if($attributeVal['type'] != 'multilevel')
																					{
																						echo '<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
																						echo '<input type="checkbox" name="'.$fieldName.'" value="'.$eachVal['id'].'" id="'.$eachVal['id'].'" />';
																						echo "<label>".Yii::t('app',$eachVal['name'])."</label></div>";
																					}else{
																						echo "<div><label>".Yii::t('app',$eachVal['name'])."</label></div>";
																					}
																					?>
																				</li>
																				<ul>
																					<?php
																					foreach($eachVal['child'] as $dkey=>$dsel)
																						{ ?>
																							<li class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																								<div class="checkbox checkbox-primary col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
																									<input type="checkbox" id="<?php echo $dsel['id']; ?>" name="<?= $fieldName; ?>" value="<?php echo $dsel['id']; ?>">
																									<label><?php echo Yii::t('app',$dsel['name']); ?></label>
																								</div>
																							</li>
																							<?php
																						}
																						?>
																					</ul>
																					<?php
																				}
																				echo '</ul>';
																			}
																			echo '</div>';
																			echo '</div>';
																		} 
																	}
																	?> 
																	<?php
																	unset($_SESSION['rangevaluesarray']);
																	$_SESSION['rangevaluesarray'] = implode(',', $rangeArray);
																	?>
																	<input type="hidden" name="rangevaluesarray" id="rangevaluesarray" value="<?= implode(',', $rangeArray); ?>" />
																	<div class="categories-list col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding submit" id="searchsubmit">
																		<input type="submit"  class="classified-create-btn primary-bg-color col-xs-12 col-sm-3 col-md-3 col-lg-3 no-hor-padding direcc" onclick="filtersearch();" name="savesearch" value="<?php echo Yii::t('app','Find');?>" />
																	</div>
																</div>
															</div>
														</div>
													</div>
													<?php
												}
												?>
											</div> </div>
										</div>
									</div>
								</div>
							</div> 
						<?php  }
					}	?>
					<!--</div> -->
					<style type="text/css">
						.btn-category { 
							color: #222 !important;
						}
						.btn-category.active {
							color: #e40046 !important;
						}
					</style>

					<style type="text/css">


						.advancedsearchlist .jslider-pointer
						{
							display: block !important;
						}
					</style>