<?php
use app\models\CounterModel;
use app\models\Indication;
use app\modules\mount\models\CounterAddress;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container">

    <h1 style="text-align: center;">Редактирование показаний счётчика</h1>

    <div class="col-md-3"></div>

    <div class="col-md-6">


        <div style="margin-top: 5%;">
            <?php

            $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>



            <?php if (Indication::find()->where(['counter_id' => $counter['id']])->count() < 5) { ?>

                <div class="row">
                    <div class="clear"></div>
                    <div class="form-group field-usercounter-initial_indications">
                        <label class="control-label" for="usercounter-initial_indications">Показания счетчика</label>
                        <input type="text" id="usercounter-initial_indications" class="form-control"
                               name="Counter[initial_indications]" value="0">

                        <div class="help-block"></div>
                    </div>
                    <div class="errorMessage app\modules\api\v1\models\UserCounter_password_em_"
                         style="display: none;"></div>
                    <div class="clear"></div>
                </div>

                <button type="submit" class="btn btn-info" style="width: 100%;">Обновить</button>

            <?php } else { ?>

                <div class="alert alert-info">
                    <strong>Внимение</strong> Редактировать нельзя. Т.к. существует 5 показаний.
                </div>

                <div class="row">
                    <div class="clear"></div>
                    <div class="form-group field-usercounter-initial_indications">
                        <label class="control-label" for="usercounter-initial_indications">Показания счетчика</label>
                        <input type="text" id="usercounter-initial_indications" class="form-control"
                               name="Counter[initial_indications]" value="0" disabled>

                        <div class="help-block"></div>
                    </div>
                    <div class="errorMessage app\modules\api\v1\models\UserCounter_password_em_"
                         style="display: none;"></div>
                    <div class="clear"></div>
                </div>

                <?php echo \yii\helpers\Html::a('Следуйщий шаг', array('step/step3',
                    'counter_id' => $counter['id']), [
                    'class' => 'btn btn-info btn-lg',
                    'style' => 'width:100%;'
                ]); ?>


            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
<div class="col-md-3"></div>

</div>