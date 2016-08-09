<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">

        <?=\app\components\TabMenu::widget(['alias'=>'tabmenu1'])?>

    <section id="widget-grid">
        <?php if(Yii::$app->request->get('user_type','legal_entity')!='individual'){ ?>
        <div class="row" >
            <?= $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('prom','Monitoring')], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => []]),
                'content' => $this->render('_summaryDayCounter1', ['globalChartSettings' => $globalChartSettings])
            ]); ?>
        </div>
        <?php } ?>
        <div class="row" >
        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
            $this->render('/layouts/partials/jarviswidget/title', [
                'title' =>  Yii::t('prom','Indications table')
                    ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => [
                    Html::a('<i class="fa fa-columns"></i>'.Yii::t('prom','Export in 1C'), '#', [
                        'class' => 'button-icon  btn-export-excel',
                        'onClick' => 'return false;',
                        'id' => 'exportCounter1C',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    ]),
                    Html::a('<i class="fa fa-columns"></i>'.Yii::t('prom','Export in Gasoline'), '#', [
                        'class' => 'button-icon  btn-export-excel',
                        'onClick' => 'return false;',
                        'id' => 'exportCounter',
                        'style' => 'padding: 0 10px;background-color :#E0FFFF;',
                    ]),
                    Html::a('<i class="fa fa-file-excel-o"></i> '.Yii::t('prom','Export in Excel'), '#', [
                        'class' => 'button-icon  btn-export-excel',
                        'onClick' => 'return false;',
                        'id' => 'exportExcel',
                        'style' => 'padding: 0 10px;background-color :#C1FFC1;',
                    ])
                ]
                    ], true),
            'content' => $this->render('_counterList', [
                'dataProvider' => $dataProvider,
                'model' => $address,
                'searchModel' => $searchModel,
                    ], true)
        ]);
        ?>

           </div>

</div>

</section>