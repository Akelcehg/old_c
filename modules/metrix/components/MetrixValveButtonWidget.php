<?php
namespace app\modules\metrix\components;
use app\modules\metrix\models\MetrixCounter;
use Yii;
use yii\helpers\Html;


class MetrixValveButtonWidget extends \yii\base\Widget
{

    public $id;

    public function run() {

        $this->getView()->registerJsFile('/js/metrix/metrixScript.js');

        $counter=MetrixCounter::findOne($this->id);

        $string='';
        if($counter->lastCommand) {
            $timeValveOperation = (new \DateTime($counter->lastCommand->created_at))->format("H:i:s");
            $timeValveOperation=Html::tag('p',Yii::t('metrix','command_send_in').'  ('.$timeValveOperation.')');
        }else{
            $timeValveOperation='';
        }

        if($counter->performedOperation){

            switch ($counter->performedOperation){
                case 'OpenValveWithLeakCheck':
                case 'OpenValveWithLeakCheckAndKeyConfirm':
                case 'ForceOpenValve':
                    $string.=Html::tag('span',Yii::t('metrix','close'),['style'=>'margin-right:15px']).Html::button(Yii::t('metrix','open_valve'),['class'=>'btn btn-success disabled','id'=>'openValve','counter_id'=>$this->id]).$timeValveOperation;
                    break;
                case 'CloseValve':
                    $string.= Html::tag('span',Yii::t('metrix','open'),['style'=>'margin-right:15px']).Html::button(Yii::t('metrix','close_valve'),['class'=>'btn btn-danger disabled','id'=>'closeValve','counter_id'=>$this->id]).$timeValveOperation;
                    break;
            }

        }else{

            switch($counter->valve_status ){
                case 'open':
                    $string.= Html::tag('span',Yii::t('metrix','open'),['style'=>'margin-right:15px']).Html::button(Yii::t('metrix','close_valve'),['class'=>'btn btn-danger','id'=>'closeValve','counter_id'=>$this->id]);
                    break;
                case 'close':
                    $string.= Html::tag('span',Yii::t('metrix','close'),['style'=>'margin-right:15px']).Html::button(Yii::t('metrix','open_valve'),['class'=>'btn btn-success','id'=>'openValve','counter_id'=>$this->id]);
                    break;
            }

        }

        return Html::tag('div',$string,['id'=>'MetrixValveButton']);

    }
}