<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use conquer\toastr\ToastrWidget;
use common\models\Sitesettings;
$logo = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
$path1 = Yii::$app->urlManagerfrontEnd->baseUrl;
$path = $path1.'/media/logo'.'/';

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="d-table w-100 h-100 text-center position-absolute">
    <div class="centerAlignment">
        <div class="wrapper fadeInDown  d-flex align-items-center justify-content-center w-100 h-100">
                <div id="loginform" class="borderRadious-3 position-relative p-0 text-center m-r20 m-l20 w-100">
                  
                    <?php $form = ActiveForm::begin(['id' => 'login-form',  'options' => ['class' => 'm-b25 p-4']]); ?>
                        <div class="m-b15">
                            <img src="<?=$path.$logo->logoDarkVersion?>" class="height40" />
                            </div>

                            <?= $form->field($model, 'username')->textInput(['id'=> 'login','class' => 'p-t15 p-b15 p-l15 w-100','placeholder'=>Yii::t('app','Email'),  'options' => ['autocomplete' => 'off']])->label(false) ?>

                            <?= $form->field($model, 'password')->passwordInput(['id'=> 'password','class' => 'p-t15 p-b15 p-l15 w-100','placeholder'=>Yii::t('app','Password'),  'options' => ['autocomplete' => 'off']])->label(false) ?>

                        <div class="m-t20">

                            <?= Html::submitButton(Yii::t('app','LOGIN'), ['class' => 'btn-block bgBlue whiteTxtClr p-t10 p-b10 p-r30 p-l30 borderRadious-3 fontSize13 border-0', 'name' => 'login-button']) ?>

                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
        </div>
    </div>
</div>
   