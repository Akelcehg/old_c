<?php

use app\models\Address;
use app\models\CounterModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Documents */
/* @var $form yii\widgets\ActiveForm */
?>


<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/binaryajax.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/exif.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/canvasResize.js', ['position' => 2]);

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

$this->registerJs(
    "$('#documentFileSelect').change(function(e) {
                var files = e.target.files;

                for(var i = 0; i<files.length; i++){

                    canvasResize(files[i], {
                    width: 1000,
                    height: 700,
                    crop: false,
                    quality:80,

                    callback: function(data, width, height) {

                        $('#documentsGallery').append(
                            '<input type=hidden name=DocumentImages[] value='+data+'>'
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

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <?php echo $form->field($model, 'address_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['id' => 'select3']); ?>

    <?php echo $form->field($model, 'type')->dropDownList(
        ['gas' => 'Газ', 'water' => 'Вода']
    ); ?>


    <?php echo $form->field($model, 'counter_model_id')->dropDownList(['Выберите  модель счетчика'] + ArrayHelper::map(CounterModel::find()->all(), 'id', 'name'), ['id' => 'select2']); ?>



    <?php echo $form->field($model, 'how_hard')->dropDownList(
        ['Легко' => 'Легко', 'Средне' => 'Средне', 'Трудно' => 'Трудно']
    ); ?>

    <?php echo $form->field($model, 'status')->dropDownList(
        ['Новый' => 'Новый', 'Активный' => 'Активный', 'В работе' => 'В работе', 'Завершен' => 'Завершен']
    ); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>



        <div class="row">

            <h3 style="color: black;">Акт
                составил <?= \app\models\User::findOne(['id' => $model->user_id])['email'] ?></h3>

        </div>
        <input type="hidden" name="Documents[images]" id="imagesInput">


        <label class="control-label" for="documents-how_hard">Фотографии</label>

        <div class="clear"></div>
        <input type="file" id="documentFileSelect" multiple/>


    <div class="clear"></div>

    <div style="margin-top: 2%;">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <div id="documentsGallery"></div>

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
    <?php ActiveForm::end(); ?>

</div>
