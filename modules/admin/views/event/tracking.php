<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.07.16
 * Time: 16:06
 */

use app\models\User;
use yii\helpers\Html;
use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
?>
<div id="content">
    <?php
    $id = Yii::$app->request->get('id', null);
    $user=User::findOne($id);

    echo $this->render('/layouts/partials/h1', ['title' => Yii::t('events','UserAction').$user->fullname, 'icon' => 'user']);

    ?>


    <section id="widget-grid">

        <?php
        echo $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' =>
                $this->render('/layouts/partials/jarviswidget/title', [
                    'title' => Yii::t('events','Events Log')
                ], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [

            ], true),
            'content' => $this->render('_tracking', [
                'dataProvider' => $dataProvider,
                'searchModel'=>$searchModel,

            ], true)
        ]);
        ?>

</div>

</section>