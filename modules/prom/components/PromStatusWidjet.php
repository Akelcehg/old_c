<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 21:32
 */

namespace app\modules\prom\components;



use app\models\CorrectorToCounter;
use yii\base\Widget;
use yii\helpers\Html;

class PromStatusWidjet extends Widget
{

    public $id;

    public function run()
    {
        $corrector=CorrectorToCounter::findOne($this->id);

        if(isset($corrector)and ($corrector->hw_status =='ENABLED')){
            echo Html::tag('i','',[
                'class'=>'glyphicon glyphicon-ok-sign txt-color-green',
                'alt'=>'Включено'
            ]);
        }else{
            echo Html::tag('i','',[
                'class'=>'glyphicon glyphicon-minus-sign txt-color-red',
                'alt'=>'Выключено'
            ]);
        }

    }

}