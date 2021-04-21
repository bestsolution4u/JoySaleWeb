<?php

use yii\helpers\Html;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="categories-create">
<?php
if(!empty($parentAttribute))
{
	$parentAttributedata = $parentAttribute;
}else{
	$parentAttributedata = array();
}

?>
   

    <?= $this->render('_form', [
        'model'=>$model, 
        'parentCategory'=>$parentCategory,
        'attributes'=>$attributes,
        'parentAttribute'=>$parentAttribute,
        'multilevel'=>$multilevel,
    ]) ?>


</div>



