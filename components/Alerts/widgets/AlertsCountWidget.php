<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 22.03.16
 * Time: 18:06
 */

namespace app\components\Alerts\widgets;


use app\components\Alerts\AlertsHandler;
use app\models\AlertsList;
use app\models\AlertsToUser;
use app\models\AlertsTypes;
use app\models\CounterAddress;
use app\models\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;

class AlertsCountWidget extends Widget
{

    public $type=['modem','prom'];
    public $url='/counter/alerts';

    public function run()
    {





        $alerts=AlertsList::find()
            ->where(['alerts_list.status'=>'ACTIVE'])
            ->andWhere(['in','device_type',$this->type])
            ->andWhere(['in','alerts_list.type',AlertsHandler::GetTypesForUser()])
            ->joinWith('counter')
            ->joinWith('counters')
            ->distinct();




        if (User::is('user')) {
            $alerts->where(['user_id' => Yii::$app->user->id]);
        }

        if (User::is('gasWatcher')) {
            $alerts->andWhere(['counters.type' => 'gas']);
        }

        if (User::is('waterWatcher')) {
            $alerts->andWhere(['counters.type' => 'water']);
        }

        if (User::is('regionWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();

            $geoIdList = CounterAddress::find()->select('id')->where(['region_id' => $user->geo_location_id])->asArray();

            $alerts->andWhere(['in', 'geo_location_id', $geoIdList]);
        }

        $this->renderWidget($alerts->count());

    }

    public function renderWidget($count)
    {

        if($count!=0){
            echo Html::a('<span id="circle_red"><p>'.$count.'</p></span>',$this->url,['title'=> $count.' Предупреждений']);
        }else{
            echo Html::a('<span id="circle_green"><p>'.$count.'</p></span>',$this->url,['title'=>'Нет предупреждений']);
        }






    }

}