<?php

use yii\helpers\Html;
use conquer\toastr\ToastrWidget;



?>

<div class="categories-create">
  

    <?= $this->render('_subcategory', [
        'model'=>$model, 
        'parentCategory'=>$parentCategory,
        'attributes'=>$attributes,
        'parentAttributes'=>$parentAttributes
    ]) ?>


</div>



