<?php

use app\assets\AdminAppAsset;
use yii\helpers\Html;

AdminAppAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */




$header = $this->render('/layouts/partials/h1', array('title' => Yii::t('menu', 'Menus'), 'icon' => 'upload'));

$createButton=Html::a(Yii::t('menu', 'Create Menu'), ['create'], ['class' => 'btn btn-success']);
$createButton=Html::tag('p',$createButton);

$jarWidget = $this->render('/layouts/partials/jarviswidget', array(
    'class' => 'jarviswidget-color-blue',
    'header' =>
        $this->render('/layouts/partials/jarviswidget/title', array(
            'title' => Yii::t('menu', 'menus_list')
        ), true),
    'control' => $this->render('/layouts/partials/jarviswidget/control', array(
        'buttons' => array()
    ), true),
    'content' => $this->render('_menus_list', [
        'dataProvider' => $dataProvider,
        'searchModel' => $searchModel
    ], true)
));

$section = Html::tag('section', $jarWidget, ['id' => 'widget-grid']);
echo Html::tag('div', $header .$createButton. $section, ['id' => 'content']);

