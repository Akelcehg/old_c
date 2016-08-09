<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.02.16
 * Time: 13:12
 */

use app\models\CorrectorToCounter;

$cc=CorrectorToCounter::find()->where(['id'=>Yii::$app->request->get('id')])->one();
if(isset($cc->status)){
?>

    <table  class='table-striped table-hover table-bordered' id='gsmDataTable'>
    <tr>
        <td><?=Yii::t('prom','Phone number')?>:</td>
        <td><?php echo $cc->status->phone ?></td>
    </tr>

    <tr>
        <td><?=Yii::t('prom','Balance')?>:</td>
        <td><?php echo $cc->status->balance?></td>
    </tr>
    <tr>
        <td><?=Yii::t('prom','Status')?>:</td>
        <td><?php echo $cc->status->getModemStatusText() ?></td>
    </tr>

    <tr>
        <td><?=Yii::t('prom','Signal level')?>:</td>
        <td><?php echo $cc->status->signal_level ?></td>
    </tr>

    <tr>
        <td><?=Yii::t('prom','Last online')?>:</td>
        <td><?php echo $cc->status->time_on_line ?></td>
    </tr>

        <?php
        if(isset($cc->cycle)){
            ?>
            <tr>
                <td><?=Yii::t('prom','Cycle ')?>:</td>
                <td><?php echo $cc->cycle ?></td>
            </tr>
            <?php
        }
        ?>
    </table>

    <!--//\app\modules\prom\components\ForcedPaymentButton::widget(['corrector'=>$cc]) -->


<?php } ?>