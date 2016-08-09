
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
    /*if($header=='gas')
        {
        echo $this->render('/layouts/partials/h1',array('title'=>'Система учета газа' ,'icon'=>'user'));
        }
        else
            {
            echo $this->render('/layouts/partials/h1',array('title'=>'Система учета воды ','icon'=>'user'));
            }*/
     ?>


<section id="widget-grid">

<?php
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'disabledButtons' => ['fullscreenbutton'],
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => Yii::t('counter','Map'),
                'icon' => 'map-marker',
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(
            'buttons' => array(
            )
        ), true),
        'content' => \app\components\CounterMap::widget(['type'=>'list','height'=>'600px','ajaxEnabled'=>'false'])
    ));
    
    ?>

</div>

</section>
