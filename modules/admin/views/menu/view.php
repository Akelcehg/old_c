<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Menu */

$header=$this->render('/layouts/partials/h1',array('title'=>$model->name,'icon'=>'user'));

$buttonUpdate=Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
$buttonDelete=Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
        'method' => 'post',
    ],]);

$buttonP=Html::tag('p',$buttonUpdate.$buttonDelete);
$detailWidget=DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'alias',
        'name',
        'created_at',
        'updated_at',
    ],
]);

echo Html::tag('div',$header.$buttonP.$detailWidget,['id'=>'content']);

