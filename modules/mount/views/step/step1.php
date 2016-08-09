<?php
use app\models\CounterModel;
use app\models\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/binaryajax.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/exif.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/canvasResize.js', ['position' => 2]);

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

$this->registerJs(
    "$('#counterFileSelect').change(function(e) {
            var file = e.target.files[0];
            canvasResize(file, {
            width: 1000,
            height: 700,
            crop: false,
            quality:80,
            //rotate: 90,
            callback: function(data, width, height) {
            $('#counterImage').append(
                            '<input type=hidden name=CounterImage value='+data+'>'
                        );
            }
            });
            });", $position = 3);
$this->registerCss("
.error-summary {
   background: #fdf7f7 none repeat scroll 0 0;
    border-left: 3px solid #eed3d7;
    color: #a94442;
    margin: 0 0 15px;
    padding: 10px 20px;
    }
    ")
?>


<div class="container">

    <h1 style="text-align: center;">Редактирование данных счётчика</h1>
    <div class="col-md-3"></div>

    <div class="col-md-6">

        <div style="margin-top: 5%;">
            <?php

            $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="row">
                <?php echo $form->errorSummary($counter); ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_errors_em_" style="display: none;"></div>
                <?php echo $form->errorSummary($rmodule); ?>
                <div class="errorMessage <?php echo get_class($rmodule) ?>_errors_em_" style="display: none;"></div>
            </div>


            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($rmodule, 'geo_location_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress')); ?>
                <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($counter, 'flat')->textInput() ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($counter, 'serial_number')->textInput(['disable'=>'true']) ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php
                    echo "<label class='control-label' for='counter-serial_number'>".$rmodule->attributeLabels()["serial_number"]."</label><br/>";
                    echo $rmodule->serial_number
                ?>
                <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($counter, 'type')->dropDownList(['Выберите измеряемую  среду'] + $counter->getCounterTypeList()); ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>
            <input type="hidden" name="Rmodule[mount]" value="1">
            <input type="hidden" name="Rmodule[serial_number]" value="<?=Yii::$app->request->get('serial_number', FALSE)?>">
            <input type="hidden" name="Counter[rmodule_id]" value="<?=Yii::$app->request->get('serial_number', FALSE)?>">
            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($counter, 'counter_model')->dropDownList(['Выберите  модель модема'] + ArrayHelper::map(CounterModel::find()->all(), 'id', 'name')); ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($counter, 'resizedImage')->fileInput(['id' => 'counterFileSelect']) ?>
                <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div id="counterImage">
            </div>

            <?php if (isset($images[0])): ?>

                <?= Html::img(\yii\helpers\BaseUrl::base() . '/' . $images[0],
                    [
                        "style" => 'max-width: 100%;height: auto;',
                        'class' => 'img-responsive'
                    ]
                ) ?>

            <?php endif; ?>

            <button type="submit" class="btn btn-info btn-lg" style="width: 100%; margin-top: 5%;margin-bottom: 5%; ">
                Обновить
            </button>


            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
<div class="col-md-3"></div>

</div>