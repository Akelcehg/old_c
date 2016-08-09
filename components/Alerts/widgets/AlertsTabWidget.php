<?php

namespace app\components\Alerts\widgets;
use app\components\Alerts\AlertsHandler;
use app\models\AlertsList;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 23.03.16
 * Time: 9:58
 */
class AlertsTabWidget extends \yii\base\Widget
{
    public $mode=['prom','modem'];
    public $status=null;
    public $type=null;

    public function run()
    {
       $this->renderWidget();
    }

    public function renderWidget(){

        $alertsType=AlertsList::find()
            ->andWhere(['in','alerts_list.type',AlertsHandler::GetTypesForUser()])
            ->andFilterWhere(['in','device_type',$this->type])
            ->andWhere(['status'=>$this->status])
            ->groupBy('type')->all();
        $array=[];
        if($alertsType) {
            foreach ($alertsType as $oneAlertType) {

                $array[] =
                    [

                        'label' => $oneAlertType->getAlertTypeText(),
                        'content' => AlertsOneTypeWidget::widget(['mode' => $this->mode, 'type' => $oneAlertType->type, 'status' => $this->status]),

                    ];


            }
        }
        else{



            echo "
            <div id=\"splash-container\">
                <div class=\"splash-content\">
                    <div class=\"splash-sphere\"></div>
                    <div class=\"splash-logo\"></div>
                    <div class=\"splash-soon\">
                        <h1 style=\"text-align: center\">  Нет предупреждений <br> ".Html::a('на главную',['/counter/search/'])."</h1>
                    </div>
                    <div class=\"splash-line\"></div>
                </div>
            </div>
                ";


        }

        echo Tabs::widget(['items'=>$array,'options'=>['style'=>'margin-top:10px;']]);

    }

}