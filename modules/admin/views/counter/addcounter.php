
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
<?php echo $this->render('//layouts/partials/h1',array('title'=>'Add Counter','icon'=>'user'));
   echo $this->render('//layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('//layouts/partials/jarviswidget/title', array(
                'title' => 'Add Counter'
            ), true),
        'control' => $this->render('//layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_addCounter',['counter'=>$counter] , true)
    ));
    ?>
    
</div>
