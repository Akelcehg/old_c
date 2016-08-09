<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">



    <section id="widget-grid">
        <div class="row" >&nbsp;</div>
        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
            $this->render('/layouts/partials/jarviswidget/title', [
                'title' => 'Таблица Показаний'
                    ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => [
                    Html::a('<i class="fa fa-columns"></i> Экспорт в 1C', '#', [
                        'class' => 'button-icon  btn-export-excel',
                        'onClick' => 'return false;',
                        'id' => 'exportCounter1C',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    ]),
                    Html::a('<i class="fa fa-columns"></i> Экспорт в Газолин', '#', [
                        'class' => 'button-icon  btn-export-excel',
                        'onClick' => 'return false;',
                        'id' => 'exportCounter',
                        'style' => 'padding: 0 10px;background-color :#E0FFFF;',
                    ]),
                    Html::a('<i class="fa fa-file-excel-o"></i> Экспорт в Эксель', '#', [
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

</section>