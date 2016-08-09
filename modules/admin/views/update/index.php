<div class="container">
    <div class="row">

        <h1>Обновление системы</h1>

        <h3>Статус обновления : </h3>

        <div class="fusion-one-half fusion-layout-column fusion-spacing-yes" style="margin-top:0px;margin-bottom:20px;">
            <div class="fusion-column-wrapper">
                <p style="text-align: left; font-size: 18px;">
                    <?php foreach ($out as $o): ?>
                        <?= $o ?><br>
                    <?php endforeach; ?>
                </p>

                <div class="fusion-clearfix">

                </div>

            </div>

        </div>

        <a href="<?= Yii::$app->urlManager->getBaseUrl() ?>/admin/update/exec" class="btn btn-info btn-lg">Обновить систему</a>


    </div>
</div>