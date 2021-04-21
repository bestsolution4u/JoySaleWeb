<?php

use yii\helpers\Html;


?>


	<?php echo $this->render('_update', ['model'=>$model, 'parentCategory'=>$parentCategory,'subCategory'=>$subCategory,'sub_subCategory' => $sub_subCategory,'attributes'=>$attributes,'options'=>$options, 'shippingTime' => $shippingTime,
				'countryModel' => $countryModel, 'itemShipping' => $itemShipping,
				'shippingCountry'=>$shippingCountry, 'jsShippingDetails' => $jsShippingDetails,
				'topCurs' => $topCurs,'currencies' => $currencies,'models' => $models,'photos' => $photos,'shipping_country_code'=>$shipping_country_code,'plen' => $plen,'sub_cat_name' => $sub_cat_name]); ?>


