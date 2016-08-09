<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
use app\models\CounterModel;
use app\models\User;

/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/canvasresize/binaryajax.js', ['position' => 2]);
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
            $('#canvas').attr('src', data);
            $('#imageInput').val(data);
            }
            });
            });", $position = 3);
?>


<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'serial_number')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'modem_id')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'geo_location_id')->dropDownList([Yii::t('metrix','chose_address')] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['id' => 'select3']); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <?php echo $form->field($counter, 'id')->hiddenInput()->label(false)->error(false); ?>
    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


