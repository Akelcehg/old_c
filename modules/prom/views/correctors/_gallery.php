<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
use app\models\CounterModel;
use app\models\User;

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/binaryajax.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/exif.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/canvasResize.js', ['position' => 2]);

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

$this->registerJs(
    "$('#fileSelect').change(function(e) {
                var files = e.target.files;

                for(var i = 0; i<files.length; i++){

                    canvasResize(files[i], {
                    width: 1000,
                    height: 700,
                    crop: false,
                    quality:80,

                    callback: function(data, width, height) {

                        $('#gallery').append(
                            '<input type=hidden name=PromImages[] value='+data+'>'
                        );
                    }
                 });
                }
                });", $position = 3);
?>

<script>
    $(document).ready(function () {
        $('.deleteImage').click(function () {
            //$(this).closest('img').remove();
            var b = $(this);
            $.ajax({
                type: "POST",
                data: {'path': $(this).attr('id')},
                url: 'deleteimage',
                success: function (data) {
                    if (data == 'true') {
                        //adres
                        //status
                        //slojno
                        b.prev('img').remove();
                        b.remove();
                    }
                }
            });

        });
    });
</script>

<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>


    <input type="hidden" name="Prom[images]" id="imagesInput">


    <label class="control-label" for="documents-how_hard"><?=Yii::t('prom','Photo')?></label>

    <div class="clear"></div>
    <input type="file" id="fileSelect" multiple/>

    <div id="gallery"></div>

    <?php if (isset($imagesArray)): ?>
        <?php foreach ($imagesArray as $image): ?>
            <div>
                <?= Html::img(\yii\helpers\BaseUrl::base() . '/' . $image,
                    [
                        "style" => ' max-width: 100%;height: auto; margin-bottom:5%;'
                    ]
                ) ?>

                <button type="button" class="btn btn-danger deleteImage" id=<?= $image ?> value='Удалить'>Удалить
                </button>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>



    <?php echo $form->field($cc, 'id')->hiddenInput()->label(false)->error(false); ?>
    <?php echo Html::submitButton(Yii::t('app','Save')); ?>
    <?php ActiveForm::end(); ?>
</div>