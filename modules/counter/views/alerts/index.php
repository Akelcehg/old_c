<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.03.16
 * Time: 11:39
 */

use app\components\Alerts\widgets\AlertsTabWidget;
?>
<div id="content">
    <div class="row" style="text-align: center">
        <?php
            echo AlertsTabWidget::widget(['status'=>'ACTIVE']);
            ?>
    </div>
</div>
