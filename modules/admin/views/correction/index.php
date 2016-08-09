<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">
    <?php
    $header = Yii::$app->request->get('type', 'gas');
   
        echo $this->render('/layouts/partials/h1', ['title' => 'Корректировка Показаний', 'icon' => 'user']);
  
    ?>


    <section id="widget-grid">

        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
            $this->render('/layouts/partials/jarviswidget/title', [
                'title' => 'Таблица Коррекций'
                    ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
              
                    ], true),
            'content' => $this->render('_index', [
                  'dataProvider' => $dataProvider,
                    'corrections' =>$corrections
                    ], true)
        ]);
        ?>

</div>

</section>