
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
<?php echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','sim_edit'),'icon'=>'user'));
   
    
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' =>Yii::t('counter','sim_edit')), true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[
            'buttons'=>[

                Html::a('<i class="fa fa-upload"></i>'.Yii::t('counter','modem'),
                    Yii::$app->urlManager->createUrl([
                        'admin/modem/editmodem/',
                        'serial_number'=>$modem->modem_id]), array(
                        'class' => 'button-icon',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),

            ]
        ],true),
        'content' =>$this->render('_editSim',['card'=>$modem] , true)
    ));
    

    
    ?>  
    
</div>
