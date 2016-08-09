<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.16
 * Time: 10:53
 */


use yii\grid\ActionColumn;
use yii\grid\GridView;



echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'alias',
        'name',
        'created_at',
        'updated_at',

        ['class' => 'yii\grid\ActionColumn'],
        [
            'class' => ActionColumn::className(),
            'header' => '-',
            'options' =>
                [
                    'class' => 'button-column',
                    'width' => '60px',
                ],
            'template' => '{move},{local}',
            'buttons' => [
                'move' => function($url, $model) {
                    $url = Yii::$app->urlManager->createUrl(["admin/menus/index", 'menuId' => $model->id]);
                    $label = Yii::t('menu','to menu');
                    return \yii\helpers\Html::a('<i class="fa fa-tasks "></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                },
                'local' => function($url, $model) {
                    $url = Yii::$app->urlManager->createUrl(["admin/menuslabel/index", 'menuId' => $model->id]);
                    $label = Yii::t('menu','to localization');
                    return \yii\helpers\Html::a('<i class="fa fa-font  "></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                },
            ]
        ]
    ],
]);
