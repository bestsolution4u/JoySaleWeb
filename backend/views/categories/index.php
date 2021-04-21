<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use conquer\toastr\ToastrWidget;
use yii\helpers\Url;
use kartik\alert\Alert;
?>


 <div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20 userinfo">
 <?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 8000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 8000
                ]); 
            endif; 
        ?>
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','  Category ').' '.Yii::t('app','Management')?></h4>
            </div>
                    
            <div class="">

 


                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','All Filters'),['filter/management']); ?> 
                </button> &nbsp;&nbsp;&nbsp;
                  <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add Category'),['categories/create']); ?> &nbsp;&nbsp;&nbsp;
                </button> 
                  <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Set Priority'),['categories/showtopcategory']); ?>  
                </button> 
            </div>
        </div>



                <div class="table-responsive" id="categories-grid">
 <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

  

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
             [
                'attribute' => 'categoryId',
                'headerOptions' => ['style' => 'width:5%'],
                'label'=>Yii::t('app','ID'),
              ],
           [
                'attribute' => 'name',
               'label'=>Yii::t('app','Name'),
               'format' => 'html',  
                         
              ],
         
              [
                'attribute' => 'image',
                'label'=>Yii::t('app','Image'),
                'format' => 'html',  
                'filter'=>false,  
                'value' => function ($data) {

                    
                    return Html::img(Yii::getAlias('@web').'/uploads/'. $data['image'],
                        ['width' => '70px']);
                },
            ],
           
           
            [
                'label' => 'Sub Category',
                'format'=>'raw',
                'filter'=>false,  
                //'attribute' => 'parentCategory',
                'contentOptions' => ['style'=>'text-align:center;'],

                'value' => function ($model) { 

                    if(yii::$app->Myclass->SubCategoryCount($model->categoryId)!=0) {
                          return Html::a(yii::$app->Myclass->SubCategoryCount($model->categoryId).' ', ['categories/subcategory', 'id' => $model->categoryId],['class'=>'btn btn-primary']);
                          
                      }
                      else
                      {
                          return Html::a('0', ['categories/subcategory', 'id' => $model->categoryId],['class'=>'btn btn-primary']);
                      }


                    
                }
            ],


               ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'header'=>Yii::t('app', 'View'),
                    'buttons' => [

                        'view' => function ($url, $model) {
                    
                            return Html::a('<button class="btn btn-primary align-text-top border-0"><i class="fa fa-eye"></i></button>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
                    ],


       ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'header'=>Yii::t('app', 'Edit'),
                    'buttons' => [

                        'update' => function ($url, $model) {
                    
                            return Html::a('<button class="btn btn-success align-text-top border-0"><i class="fa fa-edit"></i></button>', $url, [
                                'title' => Yii::t('app', 'Edit'),
                                'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
                    ],

                         [
                        'header'=>Yii::t('app','Delete'),
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {  
                            $val = Html::a('<button class="btn btn-danger align-text-top border-0"><i class="fa fa-trash"></i></button>',array(''),array('data-toggle' => 'modal' , 'data-target'=>'#delete'.$model->categoryId, 'title'=>Yii::t('app','Delete')));

                            return $val.'<div class="modal fade" id="delete'.$model->categoryId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content w-75 mx-auto text-center">
                        
                                    <div class="modal-body">'.Yii::t('app','Are you sure you want to delete ?').'
                                    </div>
                                    <div class="m-t20 m-b20 text-center justify-content-center">
                                     
                                      <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['categories/delete', 'id'=>$model->categoryId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                      <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                                    </div>
                                  </div>
                                </div>
                              </div>';
                        },
                    ],


        ],
    ]); ?>



                </div>
             </div>
          