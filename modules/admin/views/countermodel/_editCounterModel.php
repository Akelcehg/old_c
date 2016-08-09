<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CounterAddress;
use app\models\CounterModel;
use app\models\User;

/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

$this->registerJs("
$('#sourceImg').change(function(e) {

var file = e.target.files[0];

canvasResize(file, {
width: 1000,
height: 0,
crop: false,
quality: 100,

callback: function(data, width, height) {
alert(file);

$('#image').attr('src',data);
}
});
});");
?>



<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">                               
        <?php echo $form->errorSummary($counterModels); ?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
<?php echo $form->field($counterModels, 'name')->textInput() ?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>
    
    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counterModels, 'rate')->dropDownList(CounterModel::$availableRates, [
            'prompt' =>  Yii::t('counter','chose_precision'),
            'disabled' => !in_array('admin', array_keys(\Yii::$app->authManager->getRolesByUser(\Yii::$app->getUser()->id)))
        ])
        ?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
<?php echo $form->field($counterModels, 'type')->dropDownList([Yii::t('counter','chose_environment'),'gas'=>'gas','water'=>'water']); ?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

<?php echo $form->field($counterModels, 'id')->hiddenInput()->label(false)->error(false); ?>
<?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


