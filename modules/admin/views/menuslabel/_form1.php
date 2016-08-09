<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MenusLabel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menus-label-form">

    <?php $form = ActiveForm::begin();

        $languages=\app\models\Language::find()->all();
    $string='';
    foreach($languages as $language){
        $td1= Html::label($language->name);
        $td2=Html::input('text','Lang['.$language->id.']',$model->getI18nLabel($language->id));
        $td1=Html::tag('td',$td1);
        $td2=Html::tag('td',$td2);
        $tr=Html::tag('tr',$td1.$td2);
        $string.=$tr;
    };
    echo Html::tag('table',$string);
    echo Html::hiddenInput('id',$model->id);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);


   ActiveForm::end(); ?>

</div>
