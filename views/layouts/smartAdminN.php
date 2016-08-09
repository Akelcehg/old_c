<?php 
use yii\helpers\Html;
use app\assets\AdminAppAsset;
AdminAppAsset::register($this);



?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en-us" xmlns="http://www.w3.org/1999/html">
<head>
    
    <meta charset="utf-8">
    <?= Html::csrfMetaTags() ?>
    
    <title>
        <?php
            echo Yii::$app->params['client_name_titles'];
        ?>
    </title>
    <?php $this->head() ?>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    
    <script>
    $.ajaxSetup({
        data: <?= \yii\helpers\Json::encode([
            \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
        ]) ?>
    });
</script>
    <?php $this->registerJS('var BASE_URL = "'.Yii::$app->request->baseUrl.'";',\yii\web\View::POS_HEAD); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-73973507-1', 'auto');
        ga('send', 'pageview');

    </script>
    <style>
       .menu-on-top #main{ margin-top: 0px!important;}
    </style>
</head>
<?php /*
<body <?php if (!empty($this->gantt_body_onload)) { ?>onload="createChartControl('GanttDiv')"<?php } ?> class="<?php echo (isset($_COOKIE['sm_style']) && strpos($_COOKIE['sm_style'], 'smart-style') !== false ? $_COOKIE['sm_style'] : 'smart-style-1'), (isset($_COOKIE['sm_minified']) && $_COOKIE['sm_minified'] == 1 ? ' minified' : ''), (isset($_COOKIE['sm_hidemenu']) && $_COOKIE['sm_hidemenu'] == 1 ? ' hidden-menu' : '') ?>">
 */

if (Yii::$app->mobileDetect->isMobile())
    {echo '<body class="smart-style-2 menu-on-top fixed-page-footer">';}
    else
    {echo '<body class="smart-style-2 menu-on-top fixed-page-footer">';}
?>


<?php $this->beginBody() ?>
        <?php echo $this->render('@app/views/_partials/smartN/_admin_header'); ?>




        <!-- MAIN PANEL -->
        <div id="main" role="main">


            <!-- Open wraper for content, not footer -->
            <div class="container_content">

                <div id="container">
                    <div class="warning_message">
                    </div>
                    <?php if (in_array('1', array('user', 'messages', 'userPayment', 'tutorial'))) : echo $content; ?>
                    <?php else : ?>
                        <div id="leftColumn"></div>
                        <div id="rightColumn">
                            <?php echo $content; ?>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="clear"></div>
                <div class="empty"></div>

          
                
                
            </div>
            <!-- END wraper content -->


        </div>

        <!-- MAIN APP JS FILE -->
        <?php $this->registerJS(
                'window.sessionTimeout = "'.Yii::$app->session->timeout.'";'
                . 'window.sessionTimeoutUrl = "'.Yii::$app->request->getBaseUrl(true).'";'
                );
        ?>
        
        <div class="page-footer">
            <?php echo $this->render('@app/views/_partials/smartN/_admin_menu'); ?>
	</div>


 
        <?php $this->endBody() ?>
  
    </body>
</html>
<?php $this->endPage() ?>