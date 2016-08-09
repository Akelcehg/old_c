<?php
use app\models\Address;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->registerJs(' $("#select2").select2();');
?>

<div class="widget-body">
    <form method="get" class="smart-form">
        <div class="col col-2">
            <section>
                <label class="select">
                    <?php echo Html::dropDownList('geo_location_id',
                        isset($_GET['geo_location_id']) ? Html::encode($_GET['geo_location_id']) : '',
                        ['' =>  Yii::t('counter','chose_address')] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['id' => 'select2']); ?>
                </label>
            </section>
        </div>

        <div class="col col-2">
            <section>
                <label class="input">
                    <input type="search" placeholder="<?=Yii::t('counter','modem_number')?>" name="serial"
                           value="<?= isset($_GET['serial']) ? Html::encode($_GET['serial']) : ''; ?>"/>
                </label>
            </section>
        </div>


        <div class="col col-2">
            <button type="submit" id="filterSubmit" class="btn btn-primary" style="padding: 6px 12px" data-pjax="">
                <?=Yii::t('counter','Submit filter')?>
            </button>
        </div>
    </form>
</div>


<?php

echo GridView::widget(
    [
        'id' => 'browse-modems-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterRowOptions' => ['style' => 'display:none;'],
        'options' => [
            'cellspacing' => 0,
            'cellpadding' => 0,
            'class' => 'grid-items table table-striped table-bordered table-hover dataTable'
        ],
        'pager' => [
            'options' => [
                'pagerCssClass' => 'dataTables_paginate paging_bootstrap_full',
                'itemsCssClass' => 'grid-items',
                'class' => 'pagination',
                'selectedPageCssClass' => 'active',
                'firstPageCssClass' => 'hide',
                'lastPageCssClass' => 'hide',
                'prevPageLabel' => 'Previous',
                'nextPageLabel' => 'Next',
                'template' => "{summary}<div id='browse-users-grid_container' class='dt-wrapper'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
                'selectableRows' => 0,
            ],
        ],
        'columns' =>
            [
                [
                    'attribute' => 'id',
                    'options' => [
                        'width' => '30px'
                    ],

                ],
                'serial_number',
                [
                    //'header' => 'Адрес',
                    'attribute'=>'address',
                    'format' => 'html',

                    'value' => function ($model) {
                        if ($model->address) {
                            return $model->address->fulladdress;
                        } else {
                            return '-';
                        }
                    },
                ],
                'phone_number',
                [
                    'attribute' => 'last_invoice_request',
                    'options' => [
                        'width' => '25%'
                    ],
                ],
                [
                    'attribute' => 'invoice_request',

                ],
                [
                    //'header' => 'Заряд Батареи',
                    'attribute'=>'battery_level',
                    'format' => 'html',

                    'value' => function ($model) {
                        if (isset($model->counters[0])) {

                            if($model->counters[0]->rmodule) {

                                return $model->counters[0]->rmodule->battery_level;
                            }

                        } else {

                            return '-';

                        }

                    },
                ],
                'signal_level',
                'updated_at',
                'created_at',
                [
                    'class' => ActionColumn::className(),
                    'header' => '-',
                    'options' =>
                        [
                            'class' => 'button-column',
                            'width' => '30px',
                        ],
                    'template' => '{login}&#160;{edit}',
                    'buttons' => [
                        'edit' => function ($url, $model) {
                            $url = Yii::$app->urlManager->createUrl(["admin/modem/editmodem", 'serial_number' => $model->serial_number]);
                            $label = 'Редактировать модем';
                            return \yii\helpers\Html::a('<i class="fa fa-edit"></i>', $url, ['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                        },

                    ]
                ]
            ]
    ]
);
?>  