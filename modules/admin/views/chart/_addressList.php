<?php

use yii\grid\GridView;
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
CounterTabFilter::begin(['searchModel' => $searchModel]);

echo GridView::widget(
        [
            'id' => 'browse-address-grid',
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
            'rowOptions' => function ($model) {
        return [
            'geo_location_id' => $model->id,
            'class' => 'drillDown',
            'style' => 'cursor:pointer'
        ];
    },
            'columns' =>
            [


                [
                    'header' => 'Город',
                    'format' => 'raw',
                    'options' => [],
                    'value' => function ($model) {
                return $model->region->name;
            },
                ],
                [
                    'header' => 'Улица',
                    'attribute' => 'street',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->street;
                    },
                ]
                ,
                [
                    'header' => 'Дом',
                    'attribute' => 'house',
                    'filter' => false,
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->house;
                    },
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
