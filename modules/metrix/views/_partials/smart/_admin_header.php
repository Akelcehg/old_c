<!-- HEADER -->
<?php
use app\models\Option;
use app\components\AllAlerts;
use app\components\AllAlertsNew;
use app\models\User;

?>
<header id="header">

    <div id="logo-group">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logo"> 
            <img src="<?php echo \Yii::$app->request->baseUrl ?>/img/logo.png"/>
            <span
                style="color:#ffffff;font-size: 8px;padding-top: 10px;"><?php echo Yii::$app->params['version'] ?></span>
            <a href="tel:<?php echo \Yii::$app->params['HeaderTelephone'] ?>"
               style="color:#ffffff;font-size: 20px;margin-left: 50px"><?php echo \Yii::$app->params['HeaderTelephone'] ?></a>
            
        </span>

        <!-- END LOGO PLACEHOLDER -->

    </div>

    <!-- pulled right: nav area -->
    <div class="pull-right">

        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" title="Свернуть меню"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->

        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
            <span> <a href="<?php echo Yii::$app->urlManager->createUrl('/site/logout') ?>" title="Выйти"><i
                        class="fa fa-sign-out"></i></a>
            </span>
        </div>
        <!-- end logout button -->

        <!-- fullscreen button -->
        <!--div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" onclick="launchFullscreen(document.documentElement);" title="Full Screen"><i class="fa fa-fullscreen"></i></a> </span>
        </div-->
        <!-- end fullscreen button -->
        <div class="btn-header system-clock pull-right">
            <span id="clock" ><i class="fa fa-clock"></i> <?php echo date("j-m-Y | h:i:s"); ?></span>

            <?php if(Yii::$app->user): ?>
            <a href="<?=Yii::$app->urlManager->createUrl(["admin/users/selfedit"])?>" title="<?=User::findOne(['id'=>Yii::$app->user->id])->email;?>">
                <label class="smart-btn btn_header btn-info"  rel="tooltip">
                    <i class="fa fa-user"></i>
                </label></a>
            <?php endif; ?>


        </div>

        <div class="btn-header pull-right" style="margin-right: 10px;">
            <?php
            //echo AllAlerts::widget(['mode'=>'header']);
            //echo AllAlertsNew::widget(['mode' => 'header']);
            ?>
        </div>

    </div>

    <!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->