<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components;


use app\components\Prom\PromReportParts;
use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\models\Intervention;
use app\models\Option;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class RtSwitchWidget extends Widget
{


    public function run()
    {
        $cheked='';
        $rtEnabled=Option::find()->where(['name'=>'rtchart'])->one();
        $rtArr=Option::find()->where(['name'=>'rtarr'])->one();
        $correctors=CorrectorToCounter::find()
            ->andFilterWhere(['device_type'=>'floutech'])
            ->andFilterWhere(['work_status'=>'work'])
            ->all();



        if(!empty($rtEnabled)and $rtEnabled->value==1) {$cheked='checked="checked"';}




        if(!empty($rtEnabled)and $rtEnabled->value==1 and !empty($rtArr)){

            $str= count(json_decode($rtArr->value)) .' / '.count($correctors);
        }else{
            $str=false;
        }

        $this->renderWidget($cheked,$str);
    }


    public function renderWidget($cheked,$str)
    {
        ?>
        <span id="RtSwitch" class="demo-liveupdate-1">
            <span class="onoffswitch-title"><?= Yii::t('promWidgets','Online mode')?></span>
            <span class="onoffswitch">
                <input type="checkbox" id="start_interval" class="onoffswitch-checkbox" name="start_interval" <?=$cheked?>>
                <label for="start_interval" class="onoffswitch-label">
                    <span data-swchoff-text="<?= Yii::t('promWidgets','Off')?>" data-swchon-text="<?= Yii::t('promWidgets','On')?>" class="onoffswitch-inner" id="onoffswitch"></span>
                    <span class="onoffswitch-switch"></span>
                </label>
            </span>
            <?=Html::tag('span', $str?Yii::t('promWidgets','online').' '.$str :'',['style'=>'margin-left:10px;'] )?>
        </span>
        <?php
    }

}