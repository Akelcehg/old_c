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
    "$('#FileSelect').change(function(e) {
                var files = e.target.files;

                for(var i = 0; i<files.length; i++){

                    canvasResize(files[i], {
                    width: 1000,
                    height: 700,
                    crop: false,
                    quality:80,

                    callback: function(data, width, height) {

                        $('#Gallery').append(
                            '<input type=hidden name=Images[] value='+data+'>'
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

    <?php echo $form->field($model, 'install_place')->dropDownList(['inside'=>'Внутри','outside'=>'Снаружи']); ?>
    <?php echo $form->field($model, 'install_replace')->dropDownList(['new'=>'Новый','replace'=>'Замена']); ?>
    <?php echo $form->field($model, 'is_restricted_area')->textarea(['rows' => 6]); ?>
    <?php echo $form->field($model, 'device_type')->dropDownList(['counter'=>'Счетчик','corrector'=>'Корректор']); ?>
    <?php echo $form->field($model, 'corrector_type')->textarea(['rows' => 6]); ?>
    <?php echo $form->field($model, 'interface_converter_info')->textarea(['rows' => 6]); ?>
    <?php echo $form->field($model, 'data_cable_info')->textarea(['rows' => 6]); ?>
    <?php echo $form->field($model, 'supply_type')->textInput(); ?>
    <?php echo $form->field($model, 'gsm_signal_level')->textInput(); ?>
    <?php echo $form->field($model, 'service_company_phone')->textInput(); ?>
    <?php echo $form->field($model, 'modem_mount_type')->textarea(['rows' => 6]); ?>


    <?php echo $form->field($model, 'status')->dropDownList(
        ['new' => 'Новый', 'active' => 'Активный', 'inwork' => 'В работе', 'disabled' => 'Завершен']
    ); ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>



        <div class="row">

            <h3 style="color: black;">Акт
                составил <?= \app\models\User::findOne(['id' => Yii::$app->user->id])['email'] ?></h3>

        </div>
        <input type="hidden" name="Documents[images]" id="imagesInput">


        <label class="control-label" for="documents-how_hard">Фотографии</label>

        <div class="clear"></div>
        <input type="file" id="FileSelect" multiple/>


    <div class="clear"></div>

    <div style="margin-top: 2%;">
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <div id="Gallery"></div>

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
