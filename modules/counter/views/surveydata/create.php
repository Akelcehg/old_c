<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Documents */

$this->title = 'Create Documents';
$this->params['breadcrumbs'][] = ['label' => 'Documents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="content">
    <?php echo $this->render('/layouts/partials/h1',array('title'=>'Добавить Акт Обследования','icon'=>'print'));
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => 'Добавить Акт Обследования'
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_form',['model'=>$model] , true)
    ));
    ?>

</div>
