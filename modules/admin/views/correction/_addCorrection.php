
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\UserCounters;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */
?>



<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">                               
        <?php echo $form->errorSummary($indication); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'counter_id')->textInput(['readonly'=>'readonly']); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

   <!--   <div class="row">
        <div class="clear"></div>
        <label class="control-label" for="userindications-indications">Old Indications</label><br/>
        <?php echo $oldIndication->indications ?>
        <br/>
    </div>-->
    
    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'indications')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php
    /*
     <div class="row">
        <div class="clear"></div>
        <label class="control-label" for="userindications-indications">Old Impuls</label><br/>
        <?php echo $oldIndication->impuls ?>
        <br/>
    </div>
    
  <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'impuls')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'created_at')->widget(DatePicker::className(),['dateFormat' => 'yyyy-MM-dd','value'=>date('Y-M-d')]);?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>
    */
?>
    <div class="row">
        <div class="clear"></div>
        <label class="control-label" for="userindications-indications">Описание</label><br/>
        <?php echo Html::textarea('description', '', [
            'style' => 'width:100%',
        ]);?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


