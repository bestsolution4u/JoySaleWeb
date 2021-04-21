<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
use dosamigos\ckeditor\CKEditor;
use common\models\Filter;
use common\models\Filtervalues;
use common\models\Productfilters;
use yii\helpers\ArrayHelper;
//use frontend\components\MyClass;
error_reporting(0);

$baseUrl = Yii::$app->request->baseUrl;
	//print_r($shipping_country_code);exit;
?> 



<?php

if (isset($_SESSION['language'])) 
{
   if($_SESSION['language'] == 'ar')
  {
    $lang='rtl';
  }
} else
{
	$lang='ltr';

}

?>

<div class="form product-form-container">
	<div id="page-container" class="product-new-update">

		<div class="container-small container">

			<div class="row">
				<div class="classified-breadcrumb add-product col-xs-12 col-sm-12 col-md-12 col-lg-12">
					 <ol class="breadcrumb">
					 <li><a href="<?php echo Yii::$app->getUrlManager()->getBaseUrl().'/'; ?>"><?php echo Yii::t('app','Home'); ?></a></li>
						<li><a href="#"><?php echo Yii::t('app','Sell your stuff1'); ?></a></li>
						
					 </ol>
				</div>

			</div>
		
    <?php $form = ActiveForm::begin(['id'=>'products-form','options' => ['enctype' => 'multipart/form-data','onsubmit' => 'return validateProduct()']]); 

?>

<?php
if (isset($plen)) {?>
	<input type="hidden" name="count" id="count" value="<?php echo $plen; ?>">
<?php }else{ ?>

	<input type="hidden" name="count" id="count" value="0">
<?php }
?>
<?php

if(isset($pricerange))
{?>
<input type="hidden" name="before_decimal" id="before_decimal" value="<?php echo $pricerange->before_decimal_notation; ?>">
<input type="hidden" name="after_decimal" id="after_decimal" value="<?php echo $pricerange->after_decimal_notation; ?>"> 
<?php
}
?>

			<div class="row">
				<div class="full-horizontal-line col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
			</div>

			<?php if(!$model->isNewRecord) { 

				?>
				<div class="row">
					<div class="add-product-heading col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h2 class="top-heading-text"><?=Yii::t('app','Post your list free')?></h2>
						<p class="top-heading-sub-text">
						<?=Yii::t('app','Provide more information about your item and upload good quality photos')?>
						</p>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding:0px; !important">
						<div class="edit-btn">
						<?php

						 if($model->soldItem == 1){ ?>
							<a href="#" data-loading-text="Posting..." id="load" data-toggle="modal"
								class="sold-btn sale-btn" onclick="soldItems('<?php echo yii::$app->Myclass->safe_b64encode($model->productId.'-0') ?>', '0')">
								<?php echo Yii::t('app','Back to sale'); ?>

							</a>
						<?php }else if($model->soldItem != 1 && $model->quantity != 0){ ?>
							<a href="#" data-loading-text="Posting..." id="load" data-toggle="modal"
								class="sold-btn" onclick="soldItems('<?php echo yii::$app->Myclass->safe_b64encode($model->productId.'-0') ?>', '1')">
								<?php echo Yii::t('app','Mark as sold'); ?>
							</a>
						<?php } ?>
							<a data-target="#" data-toggle="modal" href="#" class="delete-btn"
								onclick="confirmModal('method', 'deleteItem', '<?php echo yii::$app->Myclass->safe_b64encode($model->productId.'-0') ?>')">
								<?php echo Yii::t('app','Delete Sale'); ?>
							</a>
						</div>
					</div>
				</div>
			<?php }else{ 

				?>
			<div class="row">
					<div class="add-product-heading col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h2 class="top-heading-text"><?php echo Yii::t('app','Post your list free'); ?></h2>
						<p class="top-heading-sub-text">
						<?php echo Yii::t('app','Provide more information about your item and upload good quality photos'); ?>
						</p>
					</div>
			</div>
			<?php } ?>

<div class="row">
			
							<div class="add-photos col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="add-photos-heading">
									<span><?php echo Yii::t('app','Add photos of your stuff'); ?></span>
								</div>

								 <div class="input-file-container dis_inline_block align_middle">
        
          <input class="input-file dis_none" type="file" id="image_file" multiple="true" name="XUploadForm[file]" accept=".png, .jpg, .jpeg" onchange="start_image_upload();">
          <div class="errorMessage" id="Sitesettings_logo_em_" ></div>	
          
         <div class="blog_images margin_bottom20"> <label tabindex="0" for="image_file" class="input-file-trigger direcc"><div class="img_browse"><div class="add_img"></div></div></label> 
         
        

          <?php
//print_r($photos);exit;
      if(!empty($photos)):
                foreach ($photos as $photo) { 
                	//print_r($photo);exit;

	echo '<div class="uploaded_img align_middle margin_left10" style="float: inherit;"><img src="'.$baseUrl.'/media/item/'.$model->productId.'/'.$photo->name.'"" class="img-responsive"><button type="button" class="close post_img_cls" data-dismiss="modal" aria-label="Close" onclick="remove_images1(this,\''.$photo->name.'\',\''.$model->productId.'\')"><span aria-hidden="true">×</span></button></div>';

                } 


    endif;?>
</div>
         
          </div>
   
<?php echo '<img id="loadingimg" src="'.$baseUrl.'/images/load.gif" class="loading" style="display:none;">'; ?>
              
<input type="hidden" value='' name="uploadedfiles" id="uploadedfiles">
<input type="hidden" name="removefiles" id="removefiles">
<input type="hidden" value='<?php echo Yii::$app->request->referrer; ?>' name="previousurl" id="previousurl">					
<div id="image_error" class="errorMessage"></div></div>
					

					<div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

							<div class="add-stuff-Category-heading">
								<span><?php echo Yii::t('app','What is your listing based on'); ?>?</span>
							</div>



					  <div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
					  <input type="hidden" name="productId" id="productId" value="<?php echo $model->productId; ?>">
								<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<label class="Category-select-box-heading required" for="Products_category"><?=Yii::t('app','Select Category')?> <span class="required">*</span></label>		
	
                                <?php if (!empty($parentCategory)){
     echo '<select class="form-control select-box-down-arrow" name="Products[category]" id="Products_category">
									<option value="">'.Yii::t('app','Select Category').'</option>';
									foreach ($parentCategory as $key => $value) {
										if($model->category == $key)
											echo '<option value="'.$key.'" selected>'.Yii::t('app',$value).'</option>';
										else
											echo '<option value="'.$key.'">'.Yii::t('app',$value).'</option>';
									}
									echo '</select>';

									}else{
										echo $form->dropDownList(Yii::t('app',$model), 'category', array('prompt'=>Yii::t('admin','Select Parent category'), 'class' => 'form-control select-box-down-arrow'));
									}
	?>
		<div id="Products_category_em_" class="errorMessage"></div>
</div>

							</div>
							<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

								<label class="Category-select-box-heading" for="Products_subCategory"><?=Yii::t('app','Select Subcategory')?></label>		
									
									<?php if (!empty($subCategory)){

												// echo $form->field($model, 'subCategory')->dropDownList( 
												// 	$subCategory,
												// 				['prompt'=>Yii::t('app','Select Subcategory'),'class'=>'subcatid form-control select-box-down-arrow','id'=>'Products_subCategory'])->label(false);

										 echo '<select class="form-control select-box-down-arrow" name="Products[subCategory]" id="Products_subCategory">
									<option value="">'.Yii::t('app','Select Subcategory').'</option>';
									foreach ($subCategory as $key => $value) {
										if($model->subCategory == $key)
											echo '<option value="'.$key.'" selected>'.Yii::t('app',$value).'</option>';
										else
											echo '<option value="'.$key.'">'.Yii::t('app',$value).'</option>';
									}
									echo '</select>';

										
									}else{
												echo $form->field($model, 'subCategory')->dropDownList( 
													$subCategory,
																				['prompt'=>Yii::t('app','Select Subcategory'),'class'=>'subcatid form-control select-box-down-arrow','id'=>'Products_subCategory'])->label(false);

									}

									?>



								</div>
								<div id="Products_subcategory_em_" class="errorMessage"></div>
								
							</div>
	<!-- New Field -->
			<div id="showField"> 
				<?php if (!empty($sub_subCategory)){ ?>
					<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<label class="Category-select-box-heading required" for="Products_category" id="Products_sub_subCategory_head"><?=Yii::t('app','Select child category for')?></label>
							<?php if (!empty($sub_subCategory)){
								echo '<select id="Products_sub_subCategory" class="form-control select-box-down-arrow productattributes" name="Products[sub_subCategory]">
								<option value="">'.Yii::t('app','Select child category for').'</option>';
								foreach ($sub_subCategory as $key => $value) {
									if($model->sub_subCategory == $key)
										echo '<option value="'.$key.'" selected>'.Yii::t('app',$value).'</option>';
									else
										echo '<option value="'.$key.'">'.Yii::t('app',$value).'</option>';
								}
								echo '</select>';
							}else{
								echo $form->field($model, 'Select child category for')->dropDownList( 
									$sub_subCategory,
									['prompt'=>Yii::t('app','Select child category for'),'class'=>'form-control select-box-down-arrow productattributes','id'=>'Products_sub_subCategory_head'])->label(false);
							}
							?>
						</div>
						<div id="Products_sub_subCategory_em_" class="errorMessage"></div>
					</div>
					<?php } ?>
			</div>

							<?php //if (!empty($sub_subCategory)){ ?>
							<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

								
								<div id="Products_sub_subCategory_em_" class="errorMessage"></div>
								</div>
								
							</div>
									<?php //} ?>

							<!-- New Field -->
							<div id="showsubfield">
                              <?php
               $options = '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
		$multilevelvalues = array();
	
		foreach($attributes as $key=>$val)
		{
			$filterModel = Filter::find()->where(['id'=>$val])->one();


			if($filterModel->type == 'dropdown'){

				$filtervalueModel = Filtervalues::find()->where(['filter_id'=>$filterModel->id])->one();

				$options .='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
				$options .= '<label class="Category-select-box-heading required" for="Products_category">'.Yii::t('app',ucfirst($filterModel->name)).'</label>';
				$options.=' <select id="product_attributes_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" >';
				
					$options .='<option value="">'.Yii::t('app','Select').' '.Yii::t('app',ucfirst($filterModel->name)).'</option>';
					$getchildvals = Filtervalues::find()->where(['parentid'=>$filtervalueModel->id, 'parentlevel'=>'1'])->all();

					$getProductfilter = Productfilters::find()->where([
						'product_id'=>$model->productId,
						'filter_id'=>$filterModel->id,
						'filter_type'=>'dropdown'
						])->one();
					
					foreach($getchildvals as $cData) {
						$checkstatus = ($getProductfilter->level_two == $cData->id) ? 'selected="selected"' : '';
						$options .='<option value="'.$cData->id.'" '.$checkstatus.'>'.Yii::t('app',$cData->name).'</option>';
					}
				
				$options .= '</select>';
				$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
				$options .= '</div>';
				$options .= '</div>';
			}elseif($filterModel->type == 'range')
			{
				$getFilterrange = Productfilters::find()->where([
						'product_id'=>$model->productId,
						'filter_id'=>$filterModel->id,
						'filter_type'=>'range'
						])->one();
				   $getminMax = explode(';', $filterModel->value);
				$fieldname = str_replace(' ', '_', strtolower($filterModel->id));
				$options .='<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding location-container">';
				$options .= "<label class='Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding'>".Yii::t('app',ucfirst($filterModel->name))."</label>";
				$options.='<input type="text" id="product_attributes_'.$filterModel->id.'" class="form-control productattributerange" value="'.$getFilterrange['level_two'].'" name="Products[attributes]['.$fieldname.']"  placeholder = "'.Yii::t('app','Values between').' '.$getminMax[0].' - '.$getminMax[1].'">';
				$options.= '<input type="hidden" id="product_attributes_'.$filterModel->id.'_values" class="form-control" value="'.$filterModel->value.'" name="range_values">';
				$options .= '<div class="product_attributes_'.$filterModel->id.' errorMessage"></div>';
				$options.= '<input type="hidden" id="'.$fieldname.'" value="'.$filterModel->value.'" />';
				$options.= '</div>';
			}elseif($filterModel->type == 'multilevel')
			{
				$getFiltermulti = Productfilters::find()->where([
						'product_id'=>$model->productId,
						'filter_id'=>$filterModel->id,
						'filter_type'=>'multilevel'
						])->one();

				$getFiltervals = Filtervalues::find()->where(['filter_id'=>$filterModel->id,
					'type'=>'multilevel'])->one();
				$getparentlevel = Filtervalues::find()->where(['parentid'=>$getFiltervals->id,
					'parentlevel'=>'3'])->all();

				$options.='<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" id="multilevelss_'.$filterModel->id.'">
								<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">';
				$options.= '<label class="Category-select-box-heading " for="Products_category">'.Yii::t('app',$filterModel->name).'</label>';
				$options.=' <select id="multilevel_'.$filterModel->id.'" class="form-control select-box-down-arrow productattributes" name="Products[attributes]['.$filterModel->id.']" onchange="getval(this);" >';
				$options.= '<option value="">'.Yii::t('app','Select parent option').'</option>';
				foreach($getparentlevel as $parentvalues)
				{
					$checked = ($getFiltermulti->level_two == $parentvalues->id) ? 'selected="selected"' : '';
					$options.= '<option value="'.$parentvalues->id.'" '.$checked.'>'.Yii::t('app',$parentvalues->name).'</option>';
				}
				$options.= '</select>';
				   $options .= '<div class="text-danger multilevel_'.$filterModel->id.' errorMessage"></div>';
				$options.= '</div>';
				$options.='<div id="multilevel_'.$filterModel->id.'">';
				$loadFilter = Filtervalues::find()->where(['filter_id'=>$filterModel->id,
					'parentid'=>$getFiltermulti->level_two,
					'parentlevel'=>'4'])->all();

				$options.= '<div class="Category-select-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding childlevelattr '.$filterModel->id.'">
									<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding '.$loadFilter[0]->parentid.'">';
				$options.=' <select id="childattribute_'.$loadFilter[0]->parentid.'" class="form-control productattributes" name="Products[attributes][multilevel]['.$loadFilter[0]->parentid.']" >';
				$options.= '<option value="">'.Yii::t('app','Select Child value').'</option>';
				foreach( $loadFilter as $key=>$value )
				{

					$checked = ($getFiltermulti->level_three == $value->id) ? 'selected="selected"' : '';
					$options.= '<option value="'.$value->id.'" '.$checked.'>'.Yii::t('app',$value->name).'</option>';
				}
				$options.= '</select>';

				$options.= '</div>';
				$options.= '</div>';
				$options.='</div>';
				$options.='</div>';
			}
		}
		$options .= '</div>';
		echo $options;
		?>
                            

                
                      
                     <!-- End Field -->
</div>









							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<label><?=Yii::t('app','Name')?> <span class="required">*</span></label>		

                            <?= $form->field($model, 'name')->textInput(['maxlength' => true,'id' => 'Products_name','placeholder' => Yii::t('app','Stuff title')])->label(false); ?>
<div id="Products_name_em_" class="errorMessage"></div>
							</div>

							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

<?= $form->field($model, 'description')->widget(CKEditor::className(), [
	'options' => ['rows' => 6, 'id' => 'Products_description'],
	'preset' => 'basic',
	'clientOptions' => ['contentsLangDirection'=>$lang]
])->label(false) ?>


<div id="Products_description_em_" class="errorMessage"></div>
							
							</div>
							</br>
							<?php if($model->productId !=0 && $model->productId!="") { 

							if ($givingaway == "yes") { ?>
								<div class="Category-give-away-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Giving Away'); ?></label>

										<div class="switch-box col-xs-6 col-sm-2 col-md-2 col-lg-2 no-hor-padding" style="width:50%;">
											<div class="switch-1 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

											<?php if( $model->price == 0)
											{ ?>
												  <input id="giving_away" class="cmn-toggle cmn-toggle-round" name="giving_away" type="checkbox" value="1" checked="checked">
											<?php } else { ?>  
												  <input id="giving_away" class="cmn-toggle-1 cmn-toggle-round-1" name="giving_away" type="checkbox" value="0">
											<?php } ?>

												  <label for="giving_away"></label>
											</div>
										</div>
									</div>
								</div>
								<?php } else if($model->price == 0 && $givingaway == "yes")
								{ ?>
									<div class="Category-give-away-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" for="Products_giving_away"><?php echo Yii::t('app','Giving Away'); ?></label>
										<div class="switch-box col-xs-6 col-sm-2 col-md-2 col-lg-2 no-hor-padding" style="width:50%;">
											<div class="switch-1 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
												  <input id="giving_away" class="cmn-toggle cmn-toggle-round" name="giving_away" type="checkbox" value="1" checked="checked">
												  <label for="giving_away"></label>
											</div>
										</div>
									</div>
								</div>
								<?php } else { ?>
								<input id="giving_away" type="hidden" value="0">
							<?php }  } elseif ($givingaway == "yes") { ?>

							<div class="Category-give-away-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<label class="Category-input-box-heading  col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Giving Away'); ?></label>

									<div class="switch-box col-xs-6 col-sm-2 col-md-2 col-lg-2 no-hor-padding" style="width:50%;">
										<div class="switch-1 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">

										<?php if($model->price != "" && $model->price == 0)
										{ ?>
											  <input id="giving_away" class="cmn-toggle cmn-toggle-round" name="giving_away" type="checkbox" value="1" checked="checked">
										<?php } else { ?>  
											  <input id="giving_away" class="cmn-toggle-1 cmn-toggle-round-1" name="giving_away" type="checkbox" value="0">
										<?php } ?>
											  <label for="giving_away"></label>
										</div>
									</div>
								</div>
							</div>

							<?php } else { ?>
							<input id="giving_away" type="hidden" value="0">
							<?php } ?>
							</br>
							<?php if(isset($model->price) && $model->price == 0 && $givingaway == "yes" ) { 
								$price_box = "display: none;";
							} else {
								$price_box = "display: block;";
							}
							?>
							<div class="Category-price-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="<?php echo $price_box; ?> ">
								<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding" style="margin-top: auto;">
									

									 	<label><?=Yii::t('app','Price')?><!-- (<?= Yii::t('app','only')?> <?php echo $pricerange->before_decimal_notation;?> <?= Yii::t('app','digits allowed before decimal point and')?> <?php echo $pricerange->after_decimal_notation;?> <?= Yii::t('app','digits after decimal point')?>) --> <span class="required">*</span></label>
									  <?= $form->field($model, 'price')->textInput(['class' => 'col-xs-12 col-sm-10 col-md-9 col-lg-10 no-hor-padding', 'placeholder'=>Yii::t('app','Stuff price'),'id' => 'Products_price'])->label(false); ?>
									
									<div class="currency-select-box-row col-xs-12 col-sm-2 col-md-3 col-lg-2 no-hor-padding">
										<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
										<?php $currencyList = yii::$app->Myclass->getCurrencyData();$hideCurrencyFlag = 0; ?>
										  <select class="form-control select-box-down-arrow" id="sel1" name="Products[currency]">
										  <?php foreach ($currencyList as $currency){
										  	$currencySelect = "";
										  	$currencyDetails = $currency->currency_symbol."-".$currency->currency_shortcode;
										  	if($model->currency == $currencyDetails)
										  		$currencySelect = "selected";
										  	echo "<option $currencySelect value='$currencyDetails'>$currency->currency_shortcode</option>";
										  }?>
										  </select>
										</div>
									</div>
									<div id="Products_price_em_" class="errorMessage"></div>
								
									
								</div>
							</div>

							
                        


					</div>
						<div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding dynamic-section" style="display: none;">
							<div class="add-stuff-Category-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?=Yii::t('app','What is your expectation and other details'); ?>?</span>
							</div>

							<div class="dynamicProperty"></div>
							<div id="Products_productCondition_em_" class="errorMessage"></div>
						</div>
						<?php
						$sitesetting = yii::$app->Myclass->getSitesettings();
						$paymentmode = Json::decode($sitesetting->sitepaymentmodes,true);

						if($paymentmode['buynowPaymentMode'] == 1)
						{

							$cate = yii::$app->Myclass->getCategoryDet($model->category);

							$buynow = Json::decode($cate->categoryProperty,true);

							if(!$model->isNewRecord){
								$instantBuyDetails = "";

								if($model->instantBuy == 1 && $buynow['buyNow'] == 'enable'){
									$instantBuyDetails = "style='display:block;'";
								}
							}else{
								$userId = Yii::$app->user->id;
								$instantBuyDetails = "style='display:none;'";
								$model->paypalid = yii::$app->Myclass->getLastProductPaypalId($userId);
							}
						?>
						<div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding instant-buy-details"<?php echo $instantBuyDetails; ?>>
							<div class="add-stuff-Category-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?=Yii::t('app','Instant buy details')?></span>
							</div>


						<!-- 		<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<?=Yii::t('app','Paypay Id')?>	
									<?php echo $form->field($model,'paypalid')->textInput(['class' => 'col-xs-12 col-sm-10 col-md-9 col-lg-10 no-hor-padding', 'placeholder'=> Yii::t('app','Paypal Id'),'id' => 'Products_paypalid'])->label(false); ?>
									<span class="label-note col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"><?php echo Yii::t('app','Note');?>: <?php echo Yii::t('app','This will be your default payment processing account'); ?>.</span>
									<div id="Products_paypalid_em_" class="errorMessage"></div>

								</div> -->
								<?php
								if($paymentmode['buynowPaymentMode'] == 1) {
									if($model->shippingCost == "" || $model->shippingCost == '0')
										//$model->shippingCost = "";
								?>
								<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<?=Yii::t('app','Shipping Cost')?>	
									<?php echo $form->field($model,'shippingCost')->textInput(['class' => 'col-xs-12 col-sm-10 col-md-9 col-lg-10 no-hor-padding', 'placeholder'=> Yii::t('app','Shipping Cost'),'id' => 'Products_shippingCost'])->label(false); ?>
									<div id="Products_shippingCost_em_" class="errorMessage"></div>

									
								</div>
								<?php } ?>
						</div>
						<?php } ?>
<div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<div class="add-stuff-Category-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?php echo Yii::t('app','Where the item is located?'); ?></span>
							</div>

							
							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding location-container">
							<!--here we add label tag-->
							<label>
							<?php echo Yii::t('app','Listing’s location'); ?> <span class="required">*</span></label>
							
							<input id="Products_location" class="form-control" type="text"
			placeholder="<?php echo Yii::t('app','Tell where you sell the item'); ?>"
			name="Products[location]" onchange="return resetLatLong()"
			value="<?php echo $model->location; ?>">

		<!-- <input id="Products_location" class="form-control" type="text"
			placeholder="<?php echo Yii::t('app','Tell where you sell the item'); ?>"
			name="Products[location]" onchange="return resetLatLong()"
			value="<?php echo $model->location; ?>"> -->
			 <input id="latitude" type="hidden" name="Products[latitude]"
			value="<?php echo $model->latitude; ?>"> 

			<input id="longitude" type="hidden" name="Products[longitude]"	value="<?php echo $model->longitude; ?>">   

			<input id="Productscountry" type="hidden" name="Products[country]" value="<?php echo $model->country; ?>">

			<input id="Productstate" type="hidden" name="Products[city]" value="<?php echo $model->city; ?>"> 
			
		<p>
		<?php echo Yii::t('app',"Note: Select the item location from the dropdown. Avoid entering the location manually."); ?>		</p>
		<div class="errorMessage" id="Products_location_em_"></div>
				 <?php /*$logUserdetail = yii::$app->Myclass->getcurrentUserdetail();
							if($model->isNewRecord && $sitesetting->facebookshare == "1") {

							?>
							<div class="switch-box col-xs-6 col-sm-3 col-md-2 col-lg-2 no-hor-padding" style="width:50%; margin-top:20px;">
								<div class="add-stuff-Category-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?php echo Yii::t('app','Share this item to facebook?'); ?></span>
								</div>
								<div class="switch-1 col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<?php if(isset($userModel->facebook_session) && $userModel->facebook_session != "")
								{ ?>
								<input type="hidden" value="1" id="facebooksession">
								  <input id="facebook_share" class="cmn-toggle-1 cmn-toggle-round-1" name="facebook_share" type="checkbox" checked="checked" value="1">
								  <label for="facebook_share"></label>
								<?php } else { ?>
								<input type="hidden" value="0" id="facebooksession">
								  <input id="facebook_share" class="cmn-toggle-1 cmn-toggle-round-1" type="checkbox" name="facebook_share" value="1">
								  <label for="facebook_share"></label>
								  <?php } ?>
								</div>
							</div>
							<?php }  */ ?>  
	</div></div>
<?php
								if($paymentmode['buynowPaymentMode'] == 1) {


									if(isset($shipping_country_code) && $shipping_country_code != "") {
								?>
								<input id="shippingcountry" type="hidden" name="Products[shippingcountry]" value="<?php echo $shipping_country_code; ?>">
								<?php }else {?>
								<input id="shippingcountry" type="hidden" name="Products[shippingcountry]" value="<?php echo $model->shippingcountry; ?>">
								 <?php } }?>



					</div>

					<div class="add-stuff-Category-section col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<div class="add-stuff-Category-heading col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<span><?=Yii::t('app',"Youtube Video Embed URL")?></span>
							</div>

							
							<div class="Category-input-box-row col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding location-container">
							<label><?=Yii::t('app','Product Video')?></label>
							<input id="videoUrl" class="form-control" placeholder="https://youtu.be/embed/SMcFnPQ2FAA" name="Products[videoUrl]"  value="<?php echo $model->videoUrl; ?>" type="text">
								<div id="Products_videourl_" class="errorMessage"></div>
				 	</div></div>

						
					
                    <?php echo Html::submitButton($model->isNewRecord ? Yii::t('app','Save') : Yii::t('app','Update'),
									array('id'=>'addProduct','class'=>'post-btn btnUpdate btn','disabled'=>'disabled','onclick'=>'return validateProduct()')); 

									?>
										<?php if(!$model->isNewRecord){ ?>
				  <?= Html::a(Yii::t('app','Cancel'), ['/user/profiles'], ['class'=>'delete-btn margin-10']) ?>
										<?php }?>


						<!--
							<a class="post-btn" href="#" data-toggle="modal" data-target="#post-your-list">Post your list</a>
						</div>  -->
						<input type="hidden" name="Products[promotion][type]" value="" id="promotion-type">
						<input type="hidden" name="Products[promotion][addtype]" value="" id="promotion-addtype">
						<input type="hidden" name="Products[uploadSessionId]" value="<?php // $sessionId; ?>" id="promotion-addtype">


						<?php if($model->isNewRecord){
							echo "<input type='hidden' value='0' class='product-update-flag' />";
						}else{
							echo "<input type='hidden' value='1' class='product-update-flag' />";
						}?>

				</div>

			</div>

		</div>

		


     <?php ActiveForm::end(); ?>

	<div class="paypal-form-container"></div>

	<!--Add popup modal-->

			<div class="modal fade" id="post-your-list" role="dialog" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog post-list-modal-width">
				<div class="post-list-modal-content login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
						<div class="post-list-header login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="modal-header-text"><p class="login-header-text"><?php $autoApporveDetails = yii::$app->Myclass->getSitesettings();
								$autoApprove=$autoApporveDetails->product_autoapprove;
								if($autoApprove==1)
								{
									echo Yii::t('app','Your stuff successfully posted!');
								}
								else
								{
									echo Yii::t('app','Your product is waiting for admin approval');
									} ?></p></div>
								<!-- <button data-dismiss="modal" class="close login-close" type="button" id="white">×</button> -->
						</div>

						<div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

						<div class="post-list-cnt login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
							<div class="login-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
								<div class="login-text-box col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
									<div class="post-list-modal-heading"><?php echo Yii::t('app','Highlight your listing?'); ?></div>
									<div class="post-list-content">
										<?php echo $sitesetting->sitename." ".Yii::t('app','allows you to highlight your listing with two different options to reach more number of buyers. You can choose the appropriate option for your listings. Urgent listings gets more leads from buyers and featured listings shows at various places of the website to reach more buyers.'); ?>
									</div>
								</div>
								<div class="post-list-tab-cnt">
									<ul class="post-list-modal-tab nav nav-tabs">
									  <li class="active"><a data-toggle="tab" href="#urgent"><?php echo Yii::t('app','Urgent'); ?></a></li>
									  <li><a data-toggle="tab" href="#promote"><?php echo Yii::t('app','Ad'); ?></a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="post-list-tab-content  tab-content">
						  <div id="urgent" class="tab-pane fade in active">
							<p> <?php echo Yii::t('app','To make your ads instantly viewable you can go for Urgent ads, which gets highlighted at the top.'); ?> <?php $promoteCurrency = explode("-", $promotionCurrency);echo $promoteCurrency[1].' '.$urgentPrice; ?>.</p>
							<div class="urgent-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
								<ul><div class="urgent-tab-heading"><?php echo Yii::t('app','Urgent tag Features')?>:</div>
									<li><i class="tick-icon fa fa-check"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','More opportunities for your buyers to see your product'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Higher frequency of listing placements'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Highlight your listing to stand out'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="urgent-tab-left-list"><?php echo Yii::t('app','Use for Make fast sale for seller and Make buyer to do purchase as Urgent'); ?></span></li>
									<li class="stuff-post">
										<form name="promotionbraintreeform" method="post" action="<?php echo $baseUrl.'/products/promotionpaymentprocess/'; ?>" onsubmit="return promotionUpdate('urgent')">
											<input type="hidden" name="BPromotionType" value='urgent' />
											<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">

											<button class="btn post-btn" href="javascript:void(0);" onclick="" type="submit"><?php echo Yii::t('app','Highlight now'); ?></button>
										<!-- 	<a class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
											<div class="urgent-promote-error delete-btn"></div> -->
										</form>

														    <?php $form = ActiveForm::begin(['id'=>'promotionstripeform',
									    'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>

											<input type="hidden" name="BPromotionType" value='urgent' />
											<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">

										
											<button class="btn post-btn" id="customButton"><?php echo Yii::t('app','Highlight with stripe'); ?></button>



											<input type="hidden" id="itemids" name="itemids">
											<?php 
											$userId = Yii::$app->user->id;
											 $sitesetting = yii::$app->Myclass->getSitesettings();
						                 	 $stripeSetting = json_decode($sitesetting->stripe_settings, true); 
								
											$stripe_key = $stripeSetting['stripePublicKey'];
											$total_price = ($sitesetting->urgentPrice)*100;
											$currency =  explode('-', $sitesetting->promotionCurrency);	
											$promotionType = "urgent";
											$customField = $promotionType."-_-".$currency[0]."-_-0-_-".$total_price."-_-".$userId;
											$customField = yii::$app->Myclass->cart_encrypt($customField, "pr0m0tion-det@ils");
											?>
											<input type="hidden" value="<?php echo $total_price; ?>" id="price" >
											<input type="hidden" value="<?php echo $promoteCurrency[1]; ?>" id="displaycurrency" >

											<input type="hidden" value="" id="promotiontype" name="promotiontype">
											<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekey" >
											<input type="hidden" value="" id="totalprice" name="totalPrice">
											<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;"></div>
											<input type="hidden" value="<?php echo $customField; ?>" id="customField1" name="customField1">
											<input type="hidden" value="" id="customField" name="customField">
											<input type="hidden" name="BPromotionType" value='urgent' />
											<input type="hidden" name="BPromotionProductid" id="UPromotionProductid" value="">

											 <input type="hidden" name="currency" value="<?php echo $currency[0]; ?>"/>

											<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
											<div class="urgent-promote-error delete-btn"></div>
											<?php ActiveForm::end(); ?>
									</li>
								</ul>
							</div>
							<div class="urgent-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
								<div class="urgent-right-circle-icon"><span class="item-urgent-1"><?php echo Yii::t('app','Urgent');?></span></div>
							</div>
						  </div>
						  <div id="promote" class="tab-pane fade">
							<p><?php echo Yii::t('app','Promote your listings to reach more users than normal listings. The promoted listings will be shown at various places to attract the buyers easily.'); ?></p>
							<div class="tab-radio-button-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
							<?php foreach ($promotionDetails as $promotion){ ?>
								<div class="tab-radio-button col-xs-6 col-sm-6 col-md-3 col-lg-3 no-hor-padding">
									<div class="tab-radio-content">
										<label><input type="radio" name="optradio" onclick="updatePromotion('<?php echo $promotion->id; ?>')"></label>
										<div class="radio-tab-period"><?php echo $promotion->name; ?></div>
										<div class="radio-tab-price col-xs-offset-3 col-sm-offset-5 col-md-offset-4 col-lg-offset-4">
											<?php echo $promoteCurrency[1].' '.$promotion->price; ?>
										</div>
										<div class="radio-tab-days"><?php echo $promotion->days; ?> <?php echo Yii::t('app','days'); ?></div>
									</div>
								</div>
							<?php } ?>
							</div>
							<div class="promote-tab-left col-xs-12 col-sm-8 col-md-8 col-lg-8 no-hor-padding">
								<ul><div class="promote-tab-heading"><?php echo Yii::t('app','promote tag Features:'); ?></div>
									<li><i class="tick-icon fa fa-check"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','View-able with highlight for all users on desktop and mobile'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Displayed at the top of the page in search results'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Higher visibility in search results means more buyers'); ?></span></li>
									<li><i class="tick-icon fa fa-check"></i><span class="promote-tab-left-list"><?php echo Yii::t('app','Listing stands out from the regular posts'); ?></span></li>
									<li class="stuff-post">
										<form name="promotionbraintreeform" method="post" action="<?php echo $baseUrl.'/products/promotionpaymentprocess/'; ?>" onsubmit="return promotionUpdate('adds')">
											<input type="hidden" name="BPromotionType" value='adds' />
											<input type="hidden" name="BPromotionProductid" id="ADPromotionProductid" value="">
											<input type="hidden" name="BPromotionid" id="ADPromotionid" value="">

											<button class="post-btn btn" href="javascript:void(0);" onclick=""><?php echo Yii::t('app','Promote now'); ?></button>
										<!-- 	<a class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
											<div class="adds-promote-error delete-btn"></div> -->
										</form>



									    <?php $form = ActiveForm::begin(['id'=>'adpromotionstripeform',
									    'action'  => Yii::$app->urlManager->createAbsoluteUrl('/products/promotionstripepaymentprocess'),'options' => ['enctype' => 'multipart/form-data']]); ?>
											<button class="btn post-btn" id="customButton1">Promote with Stripe</button>
											<input type="hidden" id="promotionids" name="promotionids">
											<?php 

										
											$userId = Yii::$app->user->id; 
											 $sitesetting = yii::$app->Myclass->getSitesettings();
						                 	 $stripeSetting = json_decode($sitesetting->stripe_settings, true); 
								
											$stripe_key = $stripeSetting['stripePublicKey'];
											$currency =  explode('-', $sitesetting->promotionCurrency);
											$promotionTypes = "adds";
											$total_pricess = $promotion->price;
											$customFieldd = $promotionTypes."-_-".$currency[0]."-_-0-_-".$total_pricess."-_-".$userId;
											$customFieldd = yii::$app->Myclass->cart_encrypt($customFieldd, "pr0m0tion-det@ils"); 
											?>



											<input type="hidden" value="<?php echo $promotionTypes; ?>" id="promotiontypee" name="promotiontypee">
											<input type="hidden" value="" id="itemide" name="itemide" >

											<input type="hidden" value="<?php echo $stripe_key; ?>" id="stripekeyy" >
											<input type="hidden"  value="<?php echo $total_pricess; ?>" id="totalpricee" >
											<div id="shiperr-stripe" style="font-size:13px;color:red;margin-left: 20px;display:none;"></div>
											<input type="hidden" value="<?php echo $customFieldd; ?>" id="customFieldd" name="customFieldd">

											 <input type="hidden" id ="currencyy" name="currencyy" value="<?php echo $currency[0]; ?>"/>
									   <?php ActiveForm::end(); ?>
										<a  data-dismiss="modal" class="delete-btn promotion-cancel" href="javascript:void(0);"><?php echo Yii::t('app','Cancel'); ?></a>
										<div class="adds-promote-error delete-btn"></div>
									</li>
								</ul>
							</div>
							<div class="promote-tab-right col-xs-12 col-sm-4 col-md-4 col-lg-4 no-hor-padding">
								<div class="promote-right-circle-icon"><span class="item-ad-1"><?=Yii::t('app','Ad')?></span></div>
							</div>
						  </div>
						</div>
				</div>
			</div>
			<input type="hidden" class="promotion-product-id" value="">
		</div>


	
	</div>

	

</div>
</div>
<script>

var shippingArray = new Array();
<?php
	// if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar') { ?>

		// $('#cke_1_toolbox').addClass('rtl_float');
<?php 
	// }

 if (isset($jsShippingDetails) && $jsShippingDetails != ''){ ?>
shippingArray = [<?php echo $jsShippingDetails; ?>];
<?php } ?>
<?php if (!$model->isNewRecord){ 

 ?>

window.onload = function() {

    productId = "<?php echo $model->productId; ?>";

    $.getJSON('<?php echo Yii::$app->urlManager->createUrl("upload", array("_method" => "list", "id" => $model->productId)); ?>', function (result) {
        var objForm = $('#products-form');
        if (result && result.length) {
            objForm.fileupload('option', 'done').call(objForm, null, {result: result});
            productImage = parseInt(result.length);
            console.log("In product append: "+productImage);
        }
    });
    var selectedCategory = $('#Products_category').val();
    var giving_away = $("#giving_away").val();

    //alert(selectedCategory);
    
    console.log('Products_category on change call');
    $.ajax({
        url: "<?=Yii::$app->getUrlManager()->getBaseUrl()?>/products/productproperty/",
        type: "post",
        data: {'selectedCategory':selectedCategory, 'givingAway':giving_away, 'productId': productId},
        dataType: "html",
       success: function (responce) {
             //   $('.instant-buy-details').fadeOut('fast');
                responce = responce.trim();
                propertyData = eval(responce);
                if (propertyData[0] == 0) {
                    $('.dynamicProperty').html(propertyData[1]);
                    $('.dynamic-section').hide();
                } else {
                    $('.dynamicProperty').html(propertyData[1]);
                    $('.dynamic-section').show();
                }
                //$('#Products_subCategory').html(propertyData[2]);
                //productPropertyUpdate = 0;
            }
    });
}
<?php } ?>
</script> 
<script type="text/javascript">
 function initMap() {
       
      var autocomplete = new google.maps.places.Autocomplete((document
            .getElementById('Products_location')), {
        types: ['(cities)']
    });
	 autocomplete.addListener('place_changed', function() {
        
     var place = autocomplete.getPlace();

     var latitude = place.geometry.location.lat();
     var longitude = place.geometry.location.lng();
     $('#Productstate').val('');  
    // $('#Productcity').val('');   
 	  var placeDetails = place.address_components;
     var count = placeDetails.length;
   
 	  var country = "";
 	  var state = "";

    	for (var i = count; i >= 1; i--) { 
	     if(placeDetails[i-1].types[0] == "country") { 
	         country = placeDetails[i-1].short_name;
	         $('#shippingcountry').val(country);
	         $('#Productscountry').val(country); 
	     } else if(placeDetails[i-1].types[0] == "administrative_area_level_1") {
	         state = placeDetails[i-1].long_name; 
	         $('#Productstate').val(state);   
	     }   /*else if(placeDetails[i-1].types[0] == "locality") {
	         city = placeDetails[i-1].short_name;
	         $('#Productscity').val(city);  
	     } */ 
    	} 
    
    $("#latitude").val(latitude);
     $("#longitude").val(longitude);

    });

    
      }

  function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
function preview_image() 
{
	//alert("RRR");
 var total_file=document.getElementById("image_file").files.length;
 //alert(total_file);
 for(var i=0;i<total_file;i++)
 {
  $('.add_img').append("<div class='column'><img src='"+URL.createObjectURL(event.target.files[i])+"' style='width:100%'></div><br>");
 }
}


</script>

<?php
$siteSettings = yii::$app->Myclass->getSitesettings();
//print_r($siteSettings->googleapikey);exit;
if(!empty($siteSettings) && isset($siteSettings->googleapikey) && $siteSettings->googleapikey!="")
//$googleapikey = "&key=".$siteSettings->googleapikey;
 $googleapikey = $siteSettings->googleapikey;

else
$googleapikey = "";
?>
   <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>&libraries=places&callback=initMap&language=en" 
         ></script>

    <!-- <script> CKEDITOR.replace( 'Products[description]' );</script> -->

<style type="text/css">
	.form-group {
    margin-bottom: 0px;
}
/*.add_img {
	float:left;
    overflow-x: auto;
    overflow-y: hidden;
    max-width: 100%;
    white-space: nowrap;
}*/
/** {
    box-sizing: border-box;
}
.column {
    float: left;
    width: 33.33%;
    padding: 5px;
        height: 100%;
}*/

label[for=Products_price]
{
   float: left;
}

</style>

<script>
// function myFunction() {

//   var subCategory = document.getElementById("Products_subCategory").value;

//   $.ajax({
// 		url: baseUrl+'/products/getupdatefilter',
// 		type: "post",
// 		dataType: "json",
// 		data: {'subcat':subCategory },
// 		beforeSend: function () {
// 				$('.showField').hide();
// 		},
// 		success: function (responce) {
// 			console.log(responce)
// 			if(responce==0)
// 			{
// 				$("#showField").html("");
// 			}
// 			else
// 			{
// 			$("#showField").html(responce);
// 			}
// 			return false;
// 		}
// 	});
// }



CKEDITOR.editorConfig = function( config )
{

config.enterMode = CKEDITOR.ENTER_BR;
};
</script>
<?php
if (isset($_SESSION['language']) && $_SESSION['language'] == 'ar') { ?>
<style type="text/css">
	.cke_toolbox {
	float: right!important;
}
</style>
<?php
}

?>



<script src="https://checkout.stripe.com/checkout.js"></script>
<script src="https://code.jquery.com/jquery-latest.min.js"></script> 
<script>


	$('#customButton').click(function(){
		
		if(this.disabled = true){
		var stripekey = $('#stripekey').val();//alert(stripekey);
		var x='<?php echo $promoteCurrency[1]; ?>';
		var promotionType = $('#promotiontype').val('urgent');//alert(promotionType);
		//var promotionType = $('#promotiontype').val();alert(promotionType);
		var totalprice = $('#price').val();
		var customField1 = $('#customField1').val();
		$('#customField').val(customField1);
		$('#totalprice').val(totalprice);
		$('#customButtonn').attr('disabled', 'disabled');
		$id = $('.promotion-product-id').val();//alert($id);
		$('#itemids').val($id);
		
		 var token = function(res){
		 var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
		 $('#braintreeurgentt').attr('disabled', 'disabled');
			            $('#customButton').attr('disabled', 'disabled');
		 $('#promotionstripeform').append($input).submit();
		 };
	
		 StripeCheckout.open({
			 key:         stripekey,
			 address:     false,
			
			 amount:      totalprice,
			 currency:    '<?php echo trim(strtolower($currency[0])); ?>',
			 name:        '<?php echo $sitesetting->sitename; ?>',
			 panelLabel:  'Checkout',
			 token:       token,
	
			 closed : function() {
			 		var stripeee = $('.stripee').val();
			 		
					if(typeof(stripeee) === "undefined"){
						//alert("1");
                     $("#braintreeurgentt").removeAttr('disabled');
					$("#customButton").removeAttr('disabled');

					}else{
						//alert("2");
						$('#braintreeurgentt').attr('disabled', 'disabled');
			            $('#customButton').attr('disabled', 'disabled');
                     }
                   
				
			 	}
		 });
					
		 return false;
		}

		});

	
	$('#customButton1').click(function(){
		
		var stripekey = $('#stripekeyy').val();
		//var promotionTypes = $('#promotiontypee').val('adds');alert(promotionTypes);
		var promotionTypes = $('#promotiontypee').val();//alert(promotiontypes);
		$id = $('.promotion-product-id').val();//alert($id);
		$('#itemide').val($id);
		var totalpricee = $('#totalpricee').val();
		var  totalprice =  totalpricee * 100;
		var errorSelector = ".adds-promote-error";	//alert(errorSelector);
			var promotionId = $('#promotion-addtype').val();
			$('#customButtonn1').attr('disabled', 'disabled');

		if(promotionId == ""){
			$(errorSelector).html(yii.t('app', 'Select a Promotion'));
			$(errorSelector).show();
			setTimeout(function() {
				$(errorSelector).html('');
				$(errorSelector).hide();
			}, 1500);
			return false;
		}
		else {
			if(this.disabled = true){
		 var token = function(res){
		 var $input = $('<input type=hidden name=stripeToken class=stripee />').val(res.id);
		 $('#braintreeaddss').attr('disabled', 'disabled');
			            $('#customButton1').attr('disabled', 'disabled');
		 $('#adpromotionstripeform').append($input).submit();
		 };
	
		 StripeCheckout.open({
			 key:         stripekey,
			 address:     false,
		
			 amount:      totalprice,
			 currency:    '<?php echo trim(strtolower($currency[0])); ?>',
			 name:        '<?php echo $sitesetting->sitename; ?>',
			 panelLabel:  'Checkout',
			 token:       token,
			 // closed : function() {
			
			 // 	$("#customButton1").removeAttr('disabled');
			 // 	$("#customButtonn1").removeAttr('disabled');
			 // }
			 closed : function() {
			 		var stripeee = $('.stripee').val();
			 		
					if(typeof(stripeee) === "undefined"){
						//alert("1");
                     $("#braintreeaddss").removeAttr('disabled');
					$("#customButton1").removeAttr('disabled');

					}else{
						//alert("2");
						$('#braintreeaddss').attr('disabled', 'disabled');
			            $('#customButton1').attr('disabled', 'disabled');
                     }
                   
				
			 	}
		 });
							
		
		}}
 		return false;
		
		});


$(document).on('change', '#Products_category', function () {
	$('#showsubfield').html(" ");
	});
	
</script>
