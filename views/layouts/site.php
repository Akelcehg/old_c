<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="utf-8">


	<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

	<title> <?php echo \Yii::$app->params['client'] ?> </title>

	<meta name="description" content="">
	<meta name="author" content="">


	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

	<!-- Basic Styles -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/bootstrap.min.css'])?>">
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/font-awesome.min.css'])?>">

	<!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/smartadmin-production.css'])?>">
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/smartadmin-skins.css'])?>">
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/site.css'])?>">

	<!-- SmartAdmin RTL Support is under construction
        <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.css"> -->

	<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
	<link rel="stylesheet" type="text/css" media="screen" href="<?= Yii::$app->urlManager->createUrl(['css/demo.css'])?>">

	<!-- FAVICONS -->
	<link rel="shortcut icon" href="<?= Yii::$app->urlManager->createUrl(['img/favicon/favicon.ico'])?>" type="image/x-icon">
	<link rel="icon" href="<?= Yii::$app->urlManager->createUrl(['img/favicon/favicon.ico'])?>" type="image/x-icon">

	<!-- GOOGLE FONT -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-73973507-1', 'auto');
		ga('send', 'pageview');

	</script>
</head>
<body id="login" class="animated fadeInDown">
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<!--<header id="header">
    <!--<span id="logo"></span>-->

<div id="logo-group" style="width: 100%;text-align: center">
	<p><span id="logo" > <img src="<?php echo \Yii::$app->request->baseUrl ?>/images/logoAser3.png" alt="Aser" id="logoImage"> </span></p>

	<!-- END AJAX-DROPDOWN -->
</div>

<!--<span id="login-header-space"> <span class="hidden-mobile">Need an account?</span> <a href="register.html" class="btn btn-danger">Creat account</a> </span>

</header>-->

<?= $content ?>

<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/pace/pace.min.js'])?>"></script>

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.0.2.min.js"><\/script>');} </script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/bootstrap/bootstrap.min.js'])?>"></script>

<!-- CUSTOM NOTIFICATION -->


<!-- JARVIS WIDGETS -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/smartwidgets/jarvis.widget.min.js'])?>"></script>

<!-- EASY PIE CHARTS -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js'])?>"></script>

<!-- SPARKLINES -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/sparkline/jquery.sparkline.min.js'])?>"></script>

<!-- JQUERY VALIDATE -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/jquery-validate/jquery.validate.min.js'])?>"></script>

<!-- JQUERY MASKED INPUT -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/masked-input/jquery.maskedinput.min.js'])?>"></script>

<!-- JQUERY SELECT2 INPUT -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/select2/select2.min.js'])?>"></script>

<!-- JQUERY UI + Bootstrap Slider -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/bootstrap-slider/bootstrap-slider.min.js'])?>"></script>

<!-- browser msie issue fix -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/msie-fix/jquery.mb.browser.min.js'])?>"></script>

<!-- FastClick: For mobile devices -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/plugin/fastclick/fastclick.js'])?>"></script>

<!--[if IE 7]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="<?= Yii::$app->urlManager->createUrl(['js/app.js'])?>"></script>

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				email : {
					required : 'Пожалуйста введите  ваш email',
					email : 'Пожалуйста введите Правильный email '
				},
				password : {
					required : 'Пожалуйста введите ваш  пароль'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>

</body>

</html>