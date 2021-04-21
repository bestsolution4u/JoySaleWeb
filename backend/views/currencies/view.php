<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
$this->title = "Joysale - View Currencies";
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="boxShadow p-3 bgWhite m-b20">
    <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Currency').' '.Yii::t('app','Details'); ?></h4>
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['currencies/index']); ?> 
                </button> 
            </div>
    </div>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'currency_name',
               'label'=>Yii::t('app','Name'),
              ],
              [
                'attribute' => 'currency_shortcode',
               'label'=>Yii::t('app','Shortcode'),
              ],
              [
                'attribute' => 'currency_symbol',
               'label'=>Yii::t('app','Symbol'),
              ],
            ['label'=>'Braintree Merchant Key',
            'value'=>$merchant_sc_id,
            ],
        ],
    ]) ?>

</div>

