
<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
AdminAppAsset::register($this);

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/counter/CounterScripts.js',['position'=>2]);



?>
<div id="content">
    <?php
            echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','modems_list'),'icon'=>'upload'));
     ?>


<section id="widget-grid">

    <?php
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => Yii::t('counter','modems_list')
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(
            'buttons' => array(

            )
        ), true),
        'content' =>$this->render('_modemsList',[
            'dataProvider'=>$dataProvider,
            'searchModel'=>$searchModel
        ] , true)
    ));
    ?>
</section>
</div>


