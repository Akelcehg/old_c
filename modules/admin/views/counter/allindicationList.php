<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">
    <?php
   
    
        echo $this->render('/layouts/partials/h1', ['title' =>Yii::t('indications','IndicationsHistory'), 'icon' => 'user']);
   
    ?>


    <section id="widget-grid">

        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
            $this->render('/layouts/partials/jarviswidget/title', [
                'title' =>Yii::t('indications','IndicationsHistory')
                    ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                
                    ], true),
            'content' => $this->render('_allindicationList', [
           'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            
            'gridViewId' => 'all-indications-grid' 
                    ], true)
        ]);
        ?>

</div>

</section>