<!-- HEADER -->
<?php
use app\models\Option;
use app\components\AllAlerts;
use app\components\AllAlertsNew;
use app\models\User;

$this->registerJs("
$(function(){

$('#object').on('mouseenter',function(){
$('#objectMenu').show()

})

$('#objectMenuCont').on('mouseleave',function(){
$('#objectMenu').hide()

})

});
",2)

?>
<header id="headerN">



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

            <?=\app\components\Alerts\widgets\AlertsCountWidget::widget()?>
            <?=\app\components\LanguageWidget::widget()?>

            <span style="color:#000000;font-size: 8px;padding-top: 10px;"> версия: <?php echo Yii::$app->params['version'] ?></span>
            <!--<span id="clock" ><i class="fa fa-clock"></i> <?php echo date("j-m-Y | h:i:s"); ?></span>-->


            <a href="<?=Yii::$app->urlManager->createUrl(["admin/users/edituser",'id' =>Yii::$app->user->id,"&backUrl=admin/users"])?>" title="<?=User::findOne(['id'=>Yii::$app->user->id])->email;?>">
                <label class="smart-btn btn_header btn-info"  rel="tooltip">
                    <i class="fa fa-user"></i>
                </label></a>


        </div>

        <div class="btn-header pull-right" style="margin-right: 10px;">
            <?php
            //echo AllAlerts::widget(['mode'=>'header']);
             //AllAlertsNew::widget(['mode' => 'header']);
            ?>
        </div>

        <div  id="objectMenuCont" class="btn-header pull-right" style="display:none;margin-top: 20px;color:#000000!important;">
            <a style="margin-right: 80px;color:#000000!important;" href="/admin/counter/" id="object" ">Объекты</a>

            <ul id="objectMenu">
                <li class="has-sub">
                    <a style="margin-right: 10px;color:#000000!important;" href="/admin/counter/" id="object" ">Население</a>
                </li >

                <li class="has-sub">
                    <a style="margin-right: 10px;color:#000000!important;" href="/admin/chart/graph" id="object" ">Графики</a>
                </li>

                <li class="has-sub">
                    <a style="margin-right: 10px;color:#000000!important;" href="/prom/correctors" id="object" ">ПромОбъекты</a>
                </li>
            </ul>

        </div>

    </div>

    <!-- end pulled right: nav area -->



</header>
<!-- END HEADER -->