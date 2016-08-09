<?php


use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);
/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/CorrectorScripts.js', ['position' => 2]);

?>
<div id="content">

    <?=\app\components\TabMenu::widget(['alias'=>'tabmenu1'])?>
    <section id="widget-grid">

        <div class="row" >
            <?= $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('metrix','Monitoring')], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => []]),
                'content' => $this->render('_summaryDayCounter1', ['globalChartSettings' => $globalChartSettings])
            ]); ?>
        </div>


        <div class="row">
            <?php
            echo $this->render('/layouts/partials/jarviswidget', array(
                'class' => 'jarviswidget-color-blue',
                'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => Yii::t('metrix','metrix_list')
                    ), true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => [
                        Html::a('<i class="fa fa-columns"></i>'.Yii::t('metrix','export_dbf'), '#', array(
                            'class' => 'button-icon  btn-export-excel',
                            'onClick' => 'return false;',
                            'id' => 'export1Cm',
                            'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                        )),
                        Html::a('<i class="fa fa-columns"></i>'.Yii::t('metrix','export_csv'), '#', array(
                            'class' => 'button-icon  btn-export-excel',
                            'onClick' => 'return false;',
                            'id' => 'exportCSV',
                            'style' => 'padding: 0 10px;background-color :#E0FFFF;',
                        )),
                    ]

                ], true),
                'content' => $this->render('_metrixList', [
                    'dataProvider' => $dataProvider,


                ], true)
            ));
            ?>


        </div>
</div>

</section>
