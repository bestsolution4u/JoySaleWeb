<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>


<div class="boxShadow p-3 bgWhite m-b20">
    <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Product').' '.Yii::t('app','Details'); ?></h4>
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['products/index']); ?> 
                </button> 
            </div>
    </div>
                

   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
             'productId',
            [
                'label'=>Yii::t('app','User Name'),
                'format'=>'raw',
                 'value'=>  yii::$app->Myclass->getUserDetailss($model->userId)->name,
           ],
            
            [
              'attribute' => 'name',
              'label'=>Yii::t('app','Name'),
            ],
               [
                'attribute' => 'description',
                'header'=>Yii::t('app','Description'),
                'value'=> preg_replace("/<.+>/sU", "", htmlspecialchars_decode($model->description)),
                'contentOptions' => ['style' => 'word-break: break-all'],
              ],
            [
                'label'=>Yii::t('app','Category'),
                'format'=>'raw',
                 'value'=>  yii::$app->Myclass->getCatName($model->category),
           ],

           [
            'label'=>Yii::t('app','Subcategory'),
            'format'=>'raw',
             'value'=>  yii::$app->Myclass->getCatName($model->subCategory),
       ],
       [
        'label'=>Yii::t('app','Price'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->price == "" || $model->price == 0)
                {
                  return "Giving Away";
                }else
                {
                  return yii::$app->Myclass->getCurrency($model->currency).' '.$model->price;
                }           
         }
        ],
            'quantity',

            [
        'label'=>Yii::t('app','Product Condition'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->productCondition == "")
                {
                  return "None";
                }else
                {
                  return $model->productCondition;
                }           
         }
        ],
            'createdDate:date',
            'likes',
            'views',
         /*        [
        'label'=>Yii::t('app','Chat And Buy'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->chatAndBuy == "" || $model->chatAndBuy == 0)
                {
                  return "Disabled";
                }else
                {
                  return "Enabled";
                }           
         }
        ],*/
         [
        'label'=>Yii::t('app','Exchange To Buy'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->exchangeToBuy == "" || $model->exchangeToBuy == 0)
                {
                  return "Disabled";
                }else
                {
                  return "Enabled";
                }           
         }
        ],
         [
        'label'=>Yii::t('app','Instant Buy'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->instantBuy == "" || $model->instantBuy == 0)
                {
                  return "Disabled";
                }else
                {
                  return "Enabled";
                }           
         }
        ],
            //'chatAndBuy',

        //   [
        // 'label'=>Yii::t('app','Paypal ID'),
        // 'format'=>'raw',
        //  'value'=> function ($model) {
        //     if($model->instantBuy == "" || $model->instantBuy == 0)
        //         {
        //           return "Unavailable";
        //         }else
        //         {
        //           return $model->paypalid ;
        //         }           
        //  }
        // ],

           
          //  'paypalid',
           /*   [
        'label'=>Yii::t('app','Size Options'),
        'format'=>'raw',
         'value'=> function ($model) {
            if($model->sizeOptions == "" || $model->sizeOptions == 0)
                {
                  return "Not set";
                }else
                {
                  return $sizeOptions;
                }           
         }
        ],*/
          //  'sizeOptions:ntext',
            [
                  'label'=>Yii::t('app','Youtube Link'),
                  'value'=> function ($model) {
                    if($model->videoUrl == "")
                    {
                      return "Not set";
                    }else
                    {
                      return $model->videoUrl;
                    }           
                 }
              ],
          
        ],
    ]) ?>

 <?php
      foreach ($getPhotos as $key => $value) {
        if(!empty($value->name))
        {
    ?>
         <img src="<?php echo Yii::$app->urlManagerfrontEnd->baseUrl.'/media/item/'.$model->productId.'/'.$value->name; ?>" width="150" height="150" class="img_mar">
    <?php        
        }
      }
    ?>  
</div>

            


      
<style type="text/css">
  .img_mar {
    margin-top: 10px; 
    margin-right: 15px; 
    border-radius: 10px; 
  }
</style>
              