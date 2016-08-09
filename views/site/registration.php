<?php
use yii\widgets\ActiveForm;

?>
<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5" style="margin-left:30%;width :380px;">
                <div class="well no-padding">

                    <!--<form action="php/demo-register.php" id="smart-form-register" class="smart-form client-form">-->
                    <?php $form = ActiveForm::begin(
                        [
                            'id' => 'smart-form-register',
                            //'enableAjaxValidation' => true,
                            'options' => ['class' => 'smart-form client-form',]
                        ]);
                    ?>
                    <header>
                        Регистрация
                    </header>
                    <fieldset>
                        <section>
                            <?php echo $form->errorSummary($user); ?>
                            <div class="errorMessage <?php echo get_class($user) ?>_errors_em_" style="display: none;"></div>
                        </section>


                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <?php echo $form->field($user, 'email')->textInput(['placeholder' => "Email",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <?php echo $form->field($user, 'password')->passwordInput(['placeholder' => "Пароль",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-lock"></i>
                                <?php echo $form->field($user, 'password_repeat')->passwordInput(['placeholder' => "Повторите пароль",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Don't forget your password</b>
                            </label>
                        </section>

                        <section>


                            <label class="input">
                                <i class="icon-append fa fa-user"></i>
                                <?php echo $form->field($user, 'first_name')->textInput(['placeholder' => "Ф.И.О.",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to enter the website</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-home"></i>
                                <?php echo $form->field($user, 'address')->textInput(['placeholder' => "Адресс",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <?php echo $form->field($user, 'facility')->textInput(['placeholder' => "Предприятие",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-info-circle"></i>
                                <?php echo $form->field($user, 'inn')->textInput(['placeholder' => "ИНН",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                            </label>
                        </section>

                        <section>
                            <label class="input">
                                <i class="icon-append fa fa-table"></i>
                                <?php echo $form->field($user, 'legal_address')->textInput(['placeholder' => "Юридический Адресс",])->label(false); ?>
                                <b class="tooltip tooltip-bottom-right">Needed to verify your account</b>
                            </label>
                        </section>

                    </fieldset>


                    <footer>
                        <button type="submit" class="btn btn-primary">
                            Зарегистрировать
                        </button>
                    </footer>

                    <div class="message">
                        <i class="fa fa-check"></i>
                        <p>
                            Thank you for your registration!
                        </p>
                    </div>
                    <!--</form>-->
                    <?php ActiveForm::end(); ?>

                </div>

            </div>
        </div>
    </div>

</div>