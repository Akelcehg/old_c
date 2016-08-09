<?php

use app\modules\prom\components\ForcedPaymentButton;
use app\modules\prom\components\ForcedReportButton;
use app\modules\prom\components\GetPacketButton;

?>


<div style="width:60%;padding-left: 20px;">
<table class='table-striped table-hover table-bordered'>
    <tr>
        <th colspan="2"><?=Yii::t('prom','Request to the corrector')?></th>
    </tr>
    <tr>
        <td class='w400 tac p5'>
            <?=Yii::t('prom','Request for daily report')?>
        </td>
        <td class='w400 tac p5'>
            <?=ForcedReportButton::widget(['corrector'=>$modem])?>
        </td>
    </tr>
    <tr>
        <th colspan="2"><?=Yii::t('prom','Request to the simcard')?></th>
    </tr>
    <tr>
        <td class='w400 tac p5'>
            <?=Yii::t('prom','Request for forced debiting')?>
        </td>
        <td class='w400 tac p5'>
            <?=ForcedPaymentButton::widget(['corrector'=>$modem])?>
        </td>
    </tr>
    <tr>
        <td class='w400 tac p5'>
            <?=Yii::t('prom','Request package name')?>
        </td>
        <td class='w400 tac p5'>
            <?=GetPacketButton::widget(['modem'=>$modem])?>
        </td>
    </tr>
</table>
</div>


