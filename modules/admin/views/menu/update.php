<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */


$header=$this->render('/layouts/partials/h1',array('title'=>Yii::t('app', 'Update {modelClass}: ', ['modelClass' => 'Menu',]) . ' ' . $model->name,'icon'=>'user'));

$form=$this->render('/layouts/partials/jarviswidget', array(
    'class'=>'jarviswidget-color-blue',
    'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' => Yii::t('menu','menu_edit')), true),
    'control' =>$this->render('/layouts/partials/jarviswidget/control',[
        'buttons'=>[ ]],true),
    'content' =>$this->render('_form', [
        'model' => $model,
    ] , true)
));
echo Html::tag('div',$header.$form,['id'=>'content']);
