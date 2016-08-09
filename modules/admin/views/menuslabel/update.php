<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MenusLabel */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Menus Label',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus Labels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menus-label-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
