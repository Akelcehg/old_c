
<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);

?>
<div id="content">
    <section id="widget-grid">
        <div class="row" >&nbsp;</div>
        <div class="row" >
   <?php
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class' => 'jarviswidget-color-blue',
        'disabledButtons' => ['fullscreenbutton'],
        'header' =>
        $this->render('/layouts/partials/jarviswidget/title', array(
            'title' => 'Карта',
            'icon' => 'map-marker',
                ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(
            'buttons' => array(
            )
                ), true),
        'content' => \app\components\CounterMap::widget(['type' => 'list'])
    ));
    ?>
        </div>
        <div class="row" >
            <?php
            /*echo $this->render('/layouts/partials/jarviswidget', array(
                'class' => 'jarviswidget-color-blue',
                'disabledButtons' => ['fullscreenbutton'],
                'header' =>
                $this->render('/layouts/partials/jarviswidget/title', array(
                    'title' => 'Предупреждения',
                    'icon' => 'map-marker',
                        ), true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                    'buttons' => array(
                    )
                        ), true),
                'content' => \app\components\AllAlertsNew::widget(['mode' => 'tab'])
            ));*/
            ?>
        </div>
        <div class="row" >

        </div>

        <!-- NEW WIDGET START -->
        <div class="row" >
                <?php
                echo $this->render('/layouts/partials/jarviswidget', array(
                    'class' => 'jarviswidget-color-blue',
                    'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => 'Таблица Показаний'
                            ), true),
                    'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                        'buttons' => array(
                            Html::a(Html::img('/images/LoadingLine.gif',[ 'style' => 'width:100px;']),'#',['id'=>'loadingBar','class'=>'button-icon','style' => 'display:none;padding: 0 10px;width:120px;']),
                            Html::a('<i class="fa fa-columns"></i> Экспорт в 1C', '#', array(
                                'class' => 'button-icon  btn-export-excel',
                                'onClick' => 'return false;',
                                'id' => 'exportCounter1C',
                                'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                            )),
                            Html::a('<i class="fa fa-columns"></i> Экспорт в Газолин', '#', array(
                                'class' => 'button-icon  btn-export-excel',
                                'onClick' => 'return false;',
                                'id' => 'exportCounter',
                                'style' => 'padding: 0 10px;background-color :#E0FFFF;',
                            )),
                            Html::a('<i class="fa fa-file-excel-o"></i> Экспорт в Эксель', '#', array(
                                'class' => 'button-icon  btn-export-excel',
                                'onClick' => 'return false;',
                                'id' => 'exportExcel',
                                'style' => 'padding: 0 10px;background-color :#C1FFC1;',
                            ))
                        )
                            ), true),
                    'content' => $this->render('_counterList', [
                        'dataProvider' => $dataProvider,
                        'model' => $address,
                        'searchModel' => $searchModel,
                            ], true)
                ));
                ?>
        </div>
</div>

</section>
