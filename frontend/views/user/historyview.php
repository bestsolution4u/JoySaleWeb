<?php
use yii\data\ArrayDataProvider;
use yii\widgets\LinkPager;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use yii\base\Configurable;
use yii\data\DataProviderInterface;
use yii\data\BaseDataProvider;
use yii\data\ActiveDataProvider;

?>
<?php if(!empty($history)) { ?>
<div class="messgage-container col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
<ul class="exchange-history-ul col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">


	<?php 
 

foreach($dataProvider->allModels as $record): ?>
		<li class="exchange-history-li col-xs-12 col-sm-12 col-md-12 col-lg-12 no-hor-padding">
		<div class="exchange-history-list-left col-xs-12 col-sm-12 col-md-6 col-lg-6 no-hor-padding">
			<?php  $useridvalue = yii::$app->Myclass->getUserDetailss($record['user'])->userId; ?>
			<?php $useridurl =  Yii::$app->urlManager->createAbsoluteUrl(['user/profiles','id'=>yii::$app->Myclass->safe_b64encode($useridvalue.'-'.rand(0,999))]); ?>
			<?php echo Yii::t('app',strtoupper($record['status'])).' '.Yii::t('app','By').' <a href='.$useridurl.' target="_blank">'.yii::$app->Myclass->getUserDetailss($record['user'])->name.'</a>'; ?>
             
		</div>
		<div class="exchange-history-list-right col-xs-12 col-sm-12 col-md-6 col-lg-6 no-hor-padding">
       <?php
       	date_default_timezone_set($timezoneName);
			$date=date('Y-m-d h:i:s A', $record['date']);
			date_default_timezone_set('UTC');   
		?>
												<?php echo $date; ?>
		</div>
		</li>
		<?php endforeach;  ?>

</ul>
</div>

			<?php	echo LinkPager::widget([
    'pagination' => $pages,
]);
		
} else { ?>
<p align="center"><?php echo Yii::t('app','No Exchange History Found'); ?></p>
<?php } ?>

<script>
$(document).ready(function(){
	var exchangeId = '<?php echo $slug; ?>';
	$('ul.yiiPager > li > a').each(function(){
                    $(this).click(function(ev){
                            ev.preventDefault();
                            $.get(this.href,{ajax:true,exchangeId :exchangeId},function(html){
                                            $('#exchangeHistory').html(html);
                                    });
                            });
            });
    });
</script>
