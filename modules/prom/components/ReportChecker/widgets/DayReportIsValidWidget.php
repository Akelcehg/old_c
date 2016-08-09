<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components\ReportChecker\widgets;


use app\components\Prom\PromReportParts;
use app\models\CorrectorToCounter;
use app\models\Diagnostic;
use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class DayReportIsValidWidget extends Widget
{
    public $id;
    public $date=false;

   function run()
   {
       $corrector=CorrectorToCounter::find()->where(['id'=>$this->id])->one();


       $test= new ReportCheckerComponent();
       $test->id=$this->id;
       $dt=new \DateTime($this->date);
       $dt->sub(new \DateInterval("P1D"));
       $test->date=$dt->format("Y-m-d");
       $this->renderWidget($test->dayReportIsValid(), $corrector);

   }

    function renderWidget($is, $corrector){

        if($is){
            echo Html::tag('i','',[
                'class'=>'glyphicon glyphicon-ok-sign txt-color-green',
                'alt'=>'Включено'
            ]);
        }else{
            if(isset($corrector->dateOptions)){
                $ch='<br>'.$corrector->dateOptions->contract_hour.':00';
            }else{
                $ch='<br> нет данных';
            }
            echo Html::tag('i',$ch,[
                'class'=>'glyphicon glyphicon-minus-sign txt-color-red',
                'alt'=>'Выключено'
            ]);
        }

    }
}