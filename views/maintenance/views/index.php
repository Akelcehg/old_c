<?php use yii\helpers\Html; ?>
<h1>Сервис временно недоступен</h1>
<div>
    <p>
        <?php if (Yii::$app->maintenanceMode->message): ?>

            <?php echo Yii::$app->maintenanceMode->message; ?>

        <?php else: ?>

            Sorry for the inconvenience but we’re performing some maintenance at the moment.
            If you need to you can always <?= Html::mailto('contact us', (\Yii::$app->params['adminEmail'] ? \Yii::$app->params['adminEmail'] : '#')) ?>,
            otherwise we’ll be back online shortly!

        <?php endif; ?>
    </p>
</div>