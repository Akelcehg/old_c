
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
$alerts = Yii::$app->request->get('alerts', 0);
$this->registerJs(" $(document).ready(function(){ $('#$alerts').trigger('click');});");

?>
<div id="content">
    <?php  
        echo $this->render('/layouts/partials/h1',array('title'=>'Предупреждения' ,'icon'=>'user'));
     ?>


<section id="widget-grid">

<?php
    
    
    
  
    /*echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
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
        'content' => \app\components\AllAlertsNew::widget(['mode'=>'tab'])
    ));
    */

    ?>

</div>

</section>
