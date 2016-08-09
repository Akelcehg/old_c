
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

?>


<div id="content">    
<?php echo $this->render('/layouts/partials/h1',['title'=>Yii::t('counter','add_counter_model'),'icon'=>'user']);
   
    
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('counter','add_counter_model')], true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_addCounterModel',['counterModels' => $counterModels,] , true)
    ));
    
     
    
    ?>  
    
</div>
