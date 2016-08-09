<?php

use yii\helpers\Html;
use app\components\managePageSize\ManagePageSize;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;
use app\models\Regions;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use app\components\Chart;
use app\components\CounterMap;
use app\components\AllAlerts;
use app\models\CounterAddressSearch;
use app\components\CounterQuery;
use app\models\UserCounters;
use app\components\CounterTabFilter;
?>
<div class="clientSearch ">
    <?php
    $searchedValue = (Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
    $selectedNativeLanguage = (Yii::$app->request->get('nativeLanguageFilter')) ? Yii::$app->request->get('nativeLanguageFilter') : '0';
    $selectedSpokenLanguage = (Yii::$app->request->get('spokenLanguageFilter')) ? Yii::$app->request->get('spokenLanguageFilter') : '0';


    ?>

    <div style="float:right;">
        <span id="count_users" class="count_users"></span>
    </div>
    <div class="clear"></div>
</div>

<?php        
CounterTabFilter::begin(['searchModel'=>$searchModel]);

        echo GridView::widget(
                [
                    'id' => 'browse-address-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterRowOptions' => ['style' => 'display:none;'],
                    'options' => [
                        'cellspacing' => 0,
                        'cellpadding' => 0,
                        'class' => 'grid-items table table-striped table-bordered table-hover dataTable',
                        'width'=>'100%',
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
                    'rowOptions' => function ($model){
                                        return [
                                            'geo_location_id'=>$model->id,
                                            'class'=>'drillDown',
                                            'style'=>'cursor:pointer'
                                            ];
                                      },
                    'columns' =>
                    [
                        [
                            //'header' => 'Город',
                            'attribute'=>'city',
                            'format' => 'raw',
                            'options'=>[],
                            'value' => function ($model) {
                                $regionName = "--";
                                $region = $model->region;

                                if($region) {
                                    $regionName = $region->name;
                                }

                                return $regionName;
                            },
                        ],
                        [
                            //'header' => 'Улица',
                            'attribute' => 'street',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->street;
                            },
                        ]
                        ,
                        [
                            //'header' => 'Дом',
                            'attribute' => 'house',
                            'filter' => false,
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->house;
                            },
                        ]
                        ,
                        [
                            //'header' => 'Потребление за период',
                            'attribute'=>'flatindications',
                            'format' => 'raw',
                            'value' => function ($model)
                            { return '<button id="consumptionShow" geo_location_id="'.$model->id.'" class=" btn-primary" style="padding: 6px 12px" type="button">'.Yii::t('prom','calculate').'</button>';},
                        ],
                        [
                            'class' => ActionColumn::className(), 
                            'header' => '-',
                            'contentOptions' =>
                                [
                                    'class' => 'button-column',
                                    'width' => '60px',
                                ],
                            'template' => '{login}',
                            'buttons' => [
                                'login' => function($url,$model) {
                                            $model->getHouseCount();
                                            if($model->count>0){

                                                $label = 'Export';
                                                return \yii\helpers\Html::tag('span','<i class="fa fa-file-excel-o"></i>', ['title' => Yii::t('yii', $label),'class'=>'excel-export','style'=>'color:#228B22', 'data-pjax' => '0']);
                                            }
                                
                                        },

                                    ]
                        ]
                    ]
                ]
        );


        ?>

<script type="text/javascript">
    $(document.body).on("dblclick", "#browse-users-grid table tr", function () {
        id = $(this).find('td:first-child').text();
        url = "<?php echo Yii::$app->getUrlManager()->createUrl('admin/users/viewUser', array('backTo' => 'approvedUsers', 'id' => '')); ?>" + id;
        if (parseInt(id)) {
            location.href = url;
        }
    });
</script>
<?php
?>