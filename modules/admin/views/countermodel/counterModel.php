<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">
    <?php
        echo $this->render('/layouts/partials/h1', ['title' => Yii::t('counter','counters_models_list'), 'icon' => 'user']);
    ?>


    <section id="widget-grid">

        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
            $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('counter','counters_models_list') ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [], true),
            'content' => $this->render('_counterModels', [
                'dataProvider' => $dataProvider,
                'searchModel'=>$searchModel
                    ], true)
        ]);
        ?>

</div>

</section>