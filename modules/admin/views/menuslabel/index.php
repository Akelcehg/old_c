<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MenusLabelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menus Labels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menus-label-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'label',
            'menu_id',
            'level',
            [
                'header' => 'варианты',
                'format' => 'html',
                'value' => function ($model) {
                    $string='';
                    foreach($model->menuItemsLabel as $label){

                        if(isset($label->language)) {
                            $string .= $label->language->name . '-' . $label->label . '<br>';
                        }
                    }
                    return $string;
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
