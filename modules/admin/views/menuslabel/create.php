<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MenusLabel */

$this->title = Yii::t('app', 'Create Menus Label');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menus Labels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-label-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
