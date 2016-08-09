<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 16:10
 */

namespace app\modules\metrix\components;


use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\Intervention;
use Yii;
use yii\base\Widget;

class CounterSummaryWidjet extends Widget
{
    public $user_type=false;



    public function run()
    {

        ?>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 show-stats">
            <div class="row" style="padding-top: 50px">
                <?= \app\modules\metrix\components\DayConsumpProgressbarWidjet::widget(['user_type'=>$this->user_type]) ?>
                <?= \app\modules\metrix\components\PrevDayConsumpProgressbarWidjet::widget(['user_type'=>$this->user_type]) ?>
                <?= \app\modules\metrix\components\MonthDayConsumpProgressbarWidjet::widget(['user_type'=>$this->user_type]) ?>
            </div>
        </div>
        <?php
    }


}

