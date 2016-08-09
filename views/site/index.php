<div id="main" role="main">


	<!-- MAIN CONTENT -->
	<div id="content" class="container">
		<div class="row" id="indexWrapper">

			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4" id="indexImageContainer" >
				<img src="<?php echo \Yii::$app->request->baseUrl ?>/images/schemeASER.png" id="indexImage">
			</div>

			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-4"  id="authFormContainer" >
				<div style="margin:auto">
					<div class="well no-padding" id="authForm">
						<form action="<?php echo Yii::$app->urlManager->createUrl("site/login");?>" method="POST" id="login-form" class="smart-form client-form">
							<header>
								Авторизация
							</header>

							<fieldset>

								<?php
								if(Yii::$app->session->getFlash('loginError')){ ?>
									<div class="alert alert-danger fade in">
										<button class="close" data-dismiss="alert"> × </button>
										<i class="fa-fw fa fa-times"></i>
										<strong>Ошибка!</strong>
										<?=Yii::$app->session->getFlash('loginError')[0]?>
									</div>
								<?php } ?>
								<section>
									<label class="label">E-mail</label>
									<label class="input"> <i class="icon-append fa fa-user"></i>
										<input type="email" name="LoginForm[email]">
										<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Введите email</b></label>
								</section>
								<section>
									<label class="label">Пароль</label>
									<label class="input"> <i class="icon-append fa fa-lock"></i>
										<input type="password" name="LoginForm[password]">
										<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Введите пароль</b> </label>
									<div class="note">

									</div>
								</section>
							</fieldset>
							<footer>
								<a class="btn btn-danger" href="<?php echo Yii::$app->urlManager->createUrl("site/registration");?>">Регистрация</a>
								<button type="submit" class="btn btn-primary">
									Войти
								</button>
							</footer>
						</form>
					</div>
					<div  id="contactInfo">
						<p> <i class="fa fa-home txt-color-teal"></i> г. Одесса ул. Балковская 120/1</p>
						<p> <i class="fa fa-phone txt-color-teal"></i> +38 (095) 064-33-81 </p>
						<p><i class="fa fa-phone txt-color-teal"></i> +38 (063) 457-78-89 </p>
						<p>	<i class="fa fa-phone txt-color-teal"></i> +38 (048) 770-24-87</p>
						<p> <i class="fa fa-envelope txt-color-teal"></i> sales@aser.com.ua</p>
					</div>

				</div>
			</div>
		</div>
		<div  class="row" id="indexFooter">
			<a href="http://aser.com.ua">Системы учета энергоресурсов 2016 </a> |
			<a href="mailto:sales@aser.com.ua">Задавайте вопросы по электронной почте</a>
		</div>

	</div>

</div>