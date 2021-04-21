<?php

use yii\helpers\Html;
use yii\widgets\DetailView;



$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="boxShadow p-3 bgWhite m-b20">
    <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Filter').' '.Yii::t('app','Details'); ?>  </h4>
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['categories/index']); ?> 
                </button> 
            </div>
    </div>
      


                <div class="table-responsive">
                                
 <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
           [
                'attribute' => 'categoryId',
                'headerOptions' => ['style' => 'width:5%'],
                'label'=>Yii::t('app','Category'),
              ],

              [
                'attribute' => 'image',
                'label'=>Yii::t('app','Image'),
              
                'format' => 'html',    
                'value' => function ($data) {
                    return Html::img(Yii::getAlias('@web').'/uploads/'. $data['image'],
                        ['width' => '70px']);
                },
            ],
           [
                'attribute' => 'name',
               'label'=>Yii::t('app','Name'),
              ],
            array(
            'label'=>Yii::t('app','Parent Category'),
            'type'=>'raw',
            'value'=>$model->catName,
        ),
            array(
           'label'=>Yii::t('app','Created Date'),
            'type'=>'raw',
            'value'=>$model->modDate,
        ),
        array(
                'label'=>Yii::t('app','Item Condition'),
                'type'=>'raw',
                'value'=>function ($model)
                {
                    if($model->parentCategory == 0)
                    {
                        $categoryproperty = $model->categoryProperty;
                         $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['itemCondition']);
                    }
                    else
                    {
                        $categoryproperty = $model->getCategoryproperty();

                        $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['itemCondition']);
                    }
                }
        ),
        array(
                'label'=>Yii::t('app','My Offer'),
                'type'=>'raw',
                'value'=>function ($model)
                {
                    if($model->parentCategory == 0)
                    {
                        $categoryproperty = $model->categoryProperty;
                         $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['myOffer']);
                    }
                    else
                    {
                        $categoryproperty = $model->getCategoryproperty();
                        $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['myOffer']);
                    }
                }
        ),
        array(
                'label'=>Yii::t('app','Exchange Buy'),
                'type'=>'raw',
                'value'=>function ($model)
                {
                    if($model->parentCategory == 0)
                    {
                        $categoryproperty = $model->categoryProperty;
                         $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['exchangetoBuy']);
                    }
                    else
                    {
                        $categoryproperty = $model->getCategoryproperty();
                        $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['exchangetoBuy']);
                    }
                }
        ),
        array(
                'label'=>Yii::t('app','Buy Now'),
                'type'=>'raw',
                'value'=>function ($model)
                {
                    if($model->parentCategory == 0)
                    {
                        $categoryproperty = $model->categoryProperty;
                         $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['buyNow']);
                    }
                    else
                    {
                        $categoryproperty = $model->getCategoryproperty();
                        $properties = json_decode($categoryproperty,true);
                        return ucfirst($properties['buyNow']);
                    }
                }
        ),
        ],
    ]); 
    ?>
   <h5 class="text-dark header-title m-t-0 m-b-30">Selected Filters</h5>
    <div class="table-responsive">                        
         <table id="w0" class="table table-striped table-bordered detail-view">
            <tbody>
                <?php
                    foreach($filters as $filterkey=>$filterval)
                    {
                        echo '<tr>
                                <th>'.$filterval->name.'</th>
                                <td>'.$filterval->value.'</td>
                            </tr>';
                    }
                ?>
        </tbody>
        </table>
    </div>

    
    
</div>
                </div>
              
             
