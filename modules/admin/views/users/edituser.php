<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/counter/CounterScripts.js',['position'=>2]);
?>


<div id="content">
    <?php echo $this->render('/layouts/partials/h1', array('title' => Yii::t('users','user_edit'), 'icon' => 'user'));
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class' => 'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => Yii::t('users','user_edit')
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', [], true),
        'content' => $this->render('_edituser', ['user' => $user, 'type' => $type,'telegrams'=>$telegrams], true)
    ));
    ?>

</div>
