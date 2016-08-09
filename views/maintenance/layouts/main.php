<?php
use yii\helpers\Html;
use brussens\maintenance\Asset;

Asset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?php echo \Yii::$app->language; ?>">
    <head>
        <meta charset="<?php echo \Yii::$app->charset; ?>">
        <title><?php echo Html::encode(Yii::$app->name); ?></title>
        <?php $this->head(); ?>
    </head>
    <body>
    <?php $this->beginBody() ?>
    <div style="width:800px; margin:0 auto;">
        <?php echo $content; ?>
    </div>
    <footer>
        <div class="container">

        </div>
    </footer>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>