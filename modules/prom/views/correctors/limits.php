<?php

use app\modules\prom\components\Limit\Limits;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
use app\models\CounterModel;
use app\models\User;

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

$limit=new Limits();
$limit->all_id=$cc->id;
$limit->year=date('Y');
$limit->month=date('m');

if ($limit->GetLimit()) {
    $limitCount = $limit->GetLimit()->limit;
}else{
    $limitCount=0;
}

if ($limit->GetNextMonthLimit()) {
    $limitNMCount = $limit->GetNextMonthLimit()->limit;
}else{
    $limitNMCount=0;
}

?>


<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <?php echo $form->errorSummary($cc); ?>
        <div class="errorMessage <?php echo get_class($cc) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo Html::label(Yii::t('prom','Limit of this month').'<br>'.Html::textInput('limit',$limitCount)); ?>

        <div class="errorMessage <?php echo get_class($cc) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo Html::label(Yii::t('prom','Limit the next month').'<br>'.Html::textInput('limitNM',$limitNMCount)); ?>

        <div class="errorMessage <?php echo get_class($cc) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo $form->field($cc, 'id')->hiddenInput()->label(false)->error(false); ?>
    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


