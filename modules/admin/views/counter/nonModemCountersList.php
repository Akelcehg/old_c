
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
    <?php  $header = Yii::$app->request->get('type','gas');

        echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','UnidentifiedDevices') ,'icon'=>'user'));
       
     ?>


<section id="widget-grid">


   
    <?php
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title'=>Yii::t('counter','UnidentifiedDevices')
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(), true),
        'content' =>$this->render('_nonModemCountersList',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
          
        ] , true)
    ));
    ?>

</div>

</section>
