
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/counter/CounterScripts.js',['position'=>2]);
?>


<div id="content">   
    
    <?php $flash = Yii::$app->session->getFlash('save'); ?>
    <?php if (!empty($flash)): ?>
        <div class="flash-success alert alert-success">
    <?php echo $flash; ?>
        </div>
<?php endif; ?>   
    
<?php echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('address','EditingAddress'),'icon'=>'user'));
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => Yii::t('address','EditingAddress')
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_editAddress',['address'=>$address] , true)
    ));
    ?>
    
</div>
