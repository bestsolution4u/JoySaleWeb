<?php
use yii\helpers\Html;
use dosamigos\fileupload\FileUpload;
?>



<?= FileUpload::widget([
    'model' => $model,
    'attribute' => 'image',
    'url' => ['user/imageupload', 'id' => $model->id], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // Also, you can specify jQuery-File-Upload events
    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

    <?=Html::a(Html::img('http://localhost/joysale/testingApp/frontend/web/profile/'.$model['image'],['width'=>'100','height'=>'100']), ['user/notification']);?>
