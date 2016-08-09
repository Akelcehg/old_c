<?php
use yii\helpers\Html;
use app\assets\MountAppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

MountAppAsset::register($this);
?>
    <!DOCTYPE html>
    <html>
    <?php $this->beginPage() ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="Content-type: text/html; charset=utf-8">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

    <?php $this->beginBody() ?>

    <?=Html::a('Главная',Yii::$app->urlManager->createAbsoluteUrl(['admin/counter']));?>

    <?= $content ?>

    <?php $this->endBody() ?>

    </body>
    </html>
<?php $this->endPage() ?>