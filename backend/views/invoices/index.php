<?php
use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
?>

<div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Invoice Management'); ?></h4>
        </div>

        <div class="table-responsive"  id="users-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Invoices").' </div>',
                'columns' => [
                    ['attribute' => 'invoiceNo',
                    'value' => 'invoiceNo',
                    'label' => Yii::t('app','Invoice ID')],
                     [
                         'attribute' => 'orderId',
                        'label'=>Yii::t('app','Order ID'),
                       ],
                   
                     
                     [
                         'attribute'=>'invoiceDate',
                         'label' => Yii::t('app','Invoice Date'),
                         'value'=>'invoiceDate',
                         'format'=>['DateTime','php:d-m-Y'],
                                             'filter'=>DatePicker::widget([
                                 'name' => 'invoiceDate',
                                 'value' => isset($_GET['invoiceDate']) ? $_GET['invoiceDate'] : '',
                                 'template' => '{addon}{input}',
                                     'clientOptions' => [
                                         'autoclose' => true,
                                         'format' => 'dd-mm-yyyy'
                                     ]
                             ]),
                    
                      ],
                      [
                         'attribute' => 'invoiceStatus',
                        'label'=>Yii::t('app','Invoice Status'),
                       ],
                       [
                         'attribute' => 'paymentMethod',
                        'label'=>Yii::t('app','Payment Method'),
                       ],
                       [
                        'attribute'=>'invoiceStatus',
                        'header'=>Yii::t('app','View'),
                        'filter' =>false,
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {   
                            if($model->invoiceStatus == 'Completed')
                            {
                                $icon='<i class="fa fa-eye"></i>';
                              // return Html::submitButton($icon, ['title'=>Yii::t('app','View').' '.Yii::t('app','Invoice'), 'class' => 'showinvoicepopup btn btn-sm btn-success','onclick'=>'showinvoicepopup('.$model->orderId.')']);
                               return ' <a  onclick="showinvoicepopup('.$model->orderId.')" class="showinvoicepopup btn btn-sm btn-primary" href="" data-toggle="modal" data-target="#invoice-modal">'.$icon.'</a>';
             
                            }
                           
                        },
                    ],
                    ]
                ]); 
            ?>
        </div>
        <!--<div id="popup_container">
    <div id="show-exchange-popup"
        style="display: none; height: auto; width: 800px; overflow-y: hidden;margin-bottom:20px;"
        class="popup ly-title update show-invoice-popup"></div>
</div>-->

<!-- <div class="modal fade" id="invoice-modal" role="dialog">
    <div class="modal-dialog modal-big-width-dialog modal-dialog-width">
        <div class="login-modal-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
            <div class="login-modal-header col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                    <h2 class="login-header-text"><?php echo Yii::t('app','Invoice');?></h2>
                    <button data-dismiss="modal" class="close login-close" type="button">×</button>
            </div>

            <div class="login-line col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding"></div>

                <div class="login-content col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding ">
                    <div class="promotion-details-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
                        <div id="invoice_content" class="invoice-popup col-xs-12 col-sm-12 col-md-12 col-lg-12">

                        </div>
                    </div>
                </div>
        </div>
    </div>
</div> -->

<div class="modal fade" id="invoice-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
      
      <div class="login-line"></div>

<div class="login-content ">
    <div class="promotion-details-cnt">
        <div id="invoice_content" class="invoice-popup">

        </div>
    </div>
</div>

      </div>

    </div>
  </div>
</div>
    </div>
    