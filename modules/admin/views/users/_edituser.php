<?php

use app\models\AlertsTypes;
use app\models\User;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use \app\models\Regions;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
?>
<?php
$this->registerJs(
    "$('#showPass').click(function(e) {
        $('#passwordForm').show();
        e.preventDefault();
    });", $position = 3);

$this->registerJs(
    "$('.checkbox > label').mouseenter( function(){ $('#'+this.firstElementChild.value).show() }).mouseleave( function(){ $('#'+this.firstElementChild.value).hide() });
    ", $position = 3)
?>

<?php
if (Yii::$app->session->getFlash('passchanged')) { ?>
    <div class="alert alert-success fade in">
        <button class="close" data-dismiss="alert"> ×</button>
        <i class="fa-fw fa fa-check"></i>
        <?= Yii::$app->session->getFlash('passchanged') ?>
    </div>
<?php } ?>

<ul class="nav nav-tabs bordered" id="myTab1">
    <li class="active"><a data-toggle="tab" href="#s1"><?=Yii::t('users','main')?></a></li>
    <li><a data-toggle="tab" href="#s2"><?=Yii::t('users','notifications')?></a></li>
    <?php if (User::is('superadmin')) {
        echo '<li><a data-toggle="tab" href="#s3">'.Yii::t('users','roles').'</a></li>';
    } ?>
</ul>

<div class="tab-content padding-10" id="myTabContent1">
    <div class="tab-pane fade in active" id="s1">
        <div style="width:60%;padding-left: 20px;">
            <?php $form = ActiveForm::begin(array('id' => 'form')); ?>
            <div class="row">
                <?php echo $form->errorSummary($user); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_errors_em_" style="display: none;"></div>
            </div>

            <?php echo $form->field($user, 'id')->hiddenInput()->label(false)->error(false); ?>
            <?php if (\app\models\User::is('admin')) { ?>
                <?php echo $form->field($user, 'id')->hiddenInput()->label(false); ?>
                <div class="row">
                    <div class="clear"></div>

                    <?php echo $form->field($user, 'email')->textInput(); ?>
                    <?php //echo $form->error($model, 'email'); ?>
                    <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                    <div class="clear"></div>
                </div>
            <?php } ?>

            <div id="passwordForm" style="display: none;">
                <div class="row">
                    <div class="clear"></div>
                    <?php echo $form->field($user, 'password')->passwordInput()->label(Yii::t('users','new_password')); ?>
                    <?php //echo $form->error($model, 'email'); ?>
                    <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                    <div class="clear"></div>
                </div>
                <div class="row">
                    <div class="clear"></div>
                    <?php echo $form->field($user, 'password_repeat')->passwordInput()->label(Yii::t('users','password_repeat')); ?>
                    <?php //echo $form->error($model, 'password'); ?>
                    <div class="errorMessage <?php echo get_class($user) ?>_password_repeat_em_"
                         style="display: none;"></div>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 1%;margin-bottom: 1%;">
                <button class="btn btn-info" id="showPass"><?=Yii::t('users','change_password')?></button>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'first_name')->textInput(); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <!--<div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'last_name')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'nick_name')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>-->

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'address')->textInput(); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'facility')->textInput(); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>


            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'status')->dropDownList($user->getAllStatuses()); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'language_id')->dropDownList(ArrayHelper::map(\app\models\Language::find()->all(),'id','name')); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>


            <?php if (\app\models\User::is('admin')) { ?>
                <div class="row">
                    <div class="clear"></div>
                    <?php
                    echo Html::label(Yii::t('users','Region'));
                    echo Html::dropDownList('region', '', [Yii::t('users','chose_region')] + ArrayHelper::map(Regions::find()->where('parent_id=0')->all(), 'id', 'name'), [
                        'value' => Yii::$app->request->get('CounterAddressSearch[region]', 0),
                        'id' => 'region',
                        'class' => "form-control"
                    ]);
                    echo Html::label('');
                    ?>

                    <div class="clear"></div>
                </div>

                <div class="row">
                    <div class="clear"></div>
                    <?php
                    echo $form->field($user, 'geo_location_id')
                        ->dropDownList([Yii::t('users','chose_region')] + ArrayHelper::map(Regions::find()->where('parent_id!=0')->all(), 'id', 'name'), [

                            'id' => 'city'
                        ]);

                    ?>
                    <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                    <div class="clear"></div>
                </div>

            <?php } ?>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'legal_address')->textInput(); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>

            <div class="row">
                <div class="clear"></div>
                <?php echo $form->field($user, 'inn')->textInput(); ?>
                <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                <div class="clear"></div>
            </div>


        </div>
    </div>


    <div class="tab-pane fade" id="s2">

        <div style="margin-right: 5%;">

            <h3><?=Yii::t('users','notification_types')?></h3>

            <?php

            echo '<div style="margin-left: 20px;">';
            echo Html::checkBoxList('alertsType', ArrayHelper::map($type, 'alerts_type_id', 'alerts_type_id'), ArrayHelper::map(AlertsTypes::find()->all(), 'id', 'alertTypeText'));
            echo '</div>';
            echo '<hr>';
            echo $form->field($user, 'email_notification_enable')->checkbox(['label' =>Yii::t('users','enable_email_notif')]);

            echo $form->field($user, 'telegram_notification_enable')->checkbox(['label' => Yii::t('users','enable_telegram_notif')]);
            echo '<hr>';
            echo '<div style="margin-left: 20px; width: 50%;">';
            echo '<table class="table table-bordered">';
            echo '<thead><tr><th>'.Yii::t('users','telegram_id').'</th><th></th></tr></thead>';
            echo '<tbody>';
            foreach ($telegrams as $telegram) {
                echo '<tr>';
                echo '<td>' . $telegram['telegram_id'] . '</td>';
                echo '<td>' . Html::a(Yii::t('app','Delete'), 'deletetelegram?id=' . $telegram['id'] . "&user_id=" . $user->id, [
                        'class' => 'btn btn-danger',
                    ]) . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';

            echo '<div style="margin-top: 1%;">';
            echo Html::a(Yii::t('users','add_bot_in_contact_list'), 'http://telegram.me/' . Yii::$app->params['TelegramNotificationBotName'], [
                'class' => 'btn btn-info btn-lg'
            ]);
            echo '</div>';

            echo '</div>';

            ?>
        </div>
    </div>

    <?php if (User::is('superadmin')) { ?>
        <div class="tab-pane fade" id="s3" style="clear: both">

            <div style="margin-right: 5%;margin-left: 10px;">
                <div style="width: 50%;float: left">
                    <?php if (User::is('superadmin')) { ?>
                    <?php
                    $arr = [
                        'admin' => Yii::t('roles','admin'),//'Админ',
                        'adjuster' => Yii::t('roles','adjuster'),//'Монтажник',
                        'GRS' => Yii::t('roles','GRS'),//'ГРС',
                        'PromAdmin' => Yii::t('roles','PromAdmin'),//'Промышленность',
                        'summary' => Yii::t('roles','summary'),//'Сводка',
                        'metrix' => Yii::t('roles','metrix'),//'Сводка',
                        'superadmin' => Yii::t('roles','superadmin'),//'СуперАдмин',

                    ];
                    $roleArr = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');
                    $responce = [];
                    foreach ($arr as $key => $value) {
                        if (isset($roleArr[$key])) {
                            $responce[$roleArr[$key]] = $value;
                        }
                    }

                    ?>
                    <div class="row">
                        <div class="clear"></div>
                        <?php /*print_r($user['role'])*/ ?>
                        <?php echo $form->field($user, 'role')->checkboxList([] + $responce, ['multiple' => 'multiple', 'class' => 'roles']); ?>


                        <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
                        <div class="clear"></div>
                    </div>
                </div>
                <div style="width: 50%;height:100%;float: left">
                    <div id="admin" style="display: none">Доступ в админ панель</div>
                    <div id="adjuster" style="display: none">Доступ к монтажному интерфейсу</div>
                    <div id="GRS" style="display: none">Доступ к показаниям ГРС</div>
                    <div id="PromAdmin" style="display: none">Доступ к показаниям промышленности</div>
                    <div id="summary" style="display: none">Доступ к сводке</div>
                    <div id="superadmin" style="display: none"> Полный доступ к функционалу</div>
                </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <?php
    echo '<div style="margin-top: 1%;clear: both">';
    echo Html::submitButton(Yii::t('app','Save'), ['style' => ['clear' => 'both']]);
    echo '</div>';
    ActiveForm::end();
    ?>
</div>



