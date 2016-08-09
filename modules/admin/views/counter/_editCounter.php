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
        <?php echo $form->errorSummary($counter); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_errors_em_" style="display: none;"></div>
    </div>

    <input type="hidden" name="Counter[image]" id="imageInput">

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'resizedImage')->fileInput(['id' => 'counterFileSelect']) ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php

    if(file_exists("./img/counters_foto/" . $counter->id . '/' . $counter->id . ".png")) {

        echo Html::img(\yii\helpers\BaseUrl::base() . "/img/counters_foto/" . $counter->id . '/' . $counter->id . ".png", ['id' => 'canvas', 'style' => 'max-width: 100%;height: auto;']);
    }

    ?>


    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'user_id')->dropDownList([Yii::t('counter','chose_user')] + ArrayHelper::map(User::find()->all(), 'id', 'fullname'), ['id' => 'select2']) ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'fullname')->textInput() ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'user_type')->dropDownList($counter->getUserTypeList()) ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'modem_id')->textInput([
            'disabled' => !in_array('admin', $userRoles)
        ]); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'rmodule_id')->textInput([
            'disabled' => !in_array('admin', $userRoles)
        ]); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'serial_number')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'initial_indications')->textInput([
            'disabled' => !in_array('admin', $userRoles), 'disabled' => $counter->initial_indications != 0 ? true : false,
        ]); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'last_indications')->textInput(['disabled' => true]); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'account')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'flat')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'type')->dropDownList([Yii::t('counter','chose_environment')] + $counter->getCounterTypeList()); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'geo_location_id')->dropDownList([Yii::t('counter','chose_address')] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['id' => 'select3']); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counter, 'counter_model')->dropDownList([Yii::t('counter','chose_counter_model')] + ArrayHelper::map(CounterModel::find()->all(), 'id', 'name')); ?>
        <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>




    <?php echo $form->field($counter, 'id')->hiddenInput()->label(false)->error(false); ?>
    <?php echo Html::submitButton(Yii::t('counter','Save')); ?>
    <?php ActiveForm::end(); ?>
</div>


