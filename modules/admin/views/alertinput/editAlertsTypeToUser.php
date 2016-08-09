<?php
use app\assets\AdminAppAsset;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);
/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */
?>
<div id="content">
    <section id="widget-grid">
        <div class="row" >
            <?php
            echo $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => 'Доступные типы предупреждений'], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', ['buttons' => []], true),
                'content' => $this->render('_editAlertsTypeToUser', ['type' => $type], true)
                    ]
            );
            ?>
        </div>
    </section>
</div>

