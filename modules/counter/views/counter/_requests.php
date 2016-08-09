<?php

use app\modules\counter\components\ForcedPaymentButton;
use app\modules\counter\components\GetPacketButton;

?>


<div style="width:60%;padding-left: 20px;">

<table class='table-striped table-hover table-bordered'>
    <tr>
        <td class='w400 tac p5'>
            Запрос на принудительное спиcание
        </td>
        <td class='w400 tac p5'>
    <?=ForcedPaymentButton::widget(['modem'=>$modem])?>
        </td>
    </tr>
    <tr>
        <td class='w400 tac p5'>
            Запрос на получение названия пакета
        </td>
        <td class='w400 tac p5'>
    <?=GetPacketButton::widget(['modem'=>$modem])?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class='tac p5'>
        Последний выход на связь <?=$modem->updated_at?>
        </td>
    </tr>
</table>
</div>


