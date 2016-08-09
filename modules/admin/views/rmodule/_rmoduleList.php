<?php

use yii\grid\GridView;
use app\components\AjaxLinkPager;
use app\components\GetAlerts;
use yii\grid\ActionColumn;

echo GridView::widget(
        [

            'layout' => '{summary}{items} {pager}',
            'dataProvider' => $dataProvider,
            'options' => [



            ],

            'columns' =>
            [
                'id',
                'user_id',
                'counter_id',
                'modem_id',
                'serial_number',
                'battery_level',
                'is_ignore_alert',
                [
                    'class' => ActionColumn::className(),
                    'header' => '-',
                    'options' =>
                    [
                        'class' => 'button-column',
                        'width' => '60px',
                    ],
                    'template' => '{login}&#160;{edit}',
                    'buttons' => [
                        'edit' => function($url,$model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/rmodule/editrmodule", 'serial_number' => $model->serial_number]);
                            $label = 'Редактировать радиомодули';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },
                                
                            ]
                        ]
                    ]
                ]
        );


        