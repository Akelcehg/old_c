<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 31.03.16
 * Time: 16:41
 */

namespace app\modules\prom\components\ReportChecker\widgets;


use app\components\Prom\PromReportParts;
use app\models\Diagnostic;
use app\modules\prom\components\ReportChecker\ReportCheckerComponent;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class DayReportIsValidTopWidget extends Widget
{
    public $id;
    public $date=false;

   function run()
   {

       $test= new ReportCheckerComponent();
       $test->id=$this->id;
       $dt=new \DateTime($this->date);
       $dt->sub(new \DateInterval("P1D"));
       $test->date=$dt->format("Y-m-d");
       $this->renderWidget($test->dayReportIsValid());

   }

    function renderWidget($is){

        if($is){
            echo Html::tag('span',Yii::t('promWidgets','ready'),['class'=>'bg-color-green label' ]);
        }else{
            echo Html::tag('span',Yii::t('promWidgets','not ready'),['class'=>'bg-color-red label']);
        }


    }
}