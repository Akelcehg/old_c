<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CounterAddress;
use app\models\CounterModel;
use app\models\User;

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
        'prompt' => Yii::t('counter','chose_precision')
    ])
?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($counterModels, 'type')->dropDownList([Yii::t('counter','chose_environment')] + $counterModels->getCounterTypeList()); ?>
        <div class="errorMessage <?php echo get_class($counterModels) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>



<?php echo Html::submitButton('Сохранить'); ?>
    
    <?php ActiveForm::end(); ?>
</div>


