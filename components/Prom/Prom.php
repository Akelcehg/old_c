<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.03.16
 * Time: 12:11
 */

namespace app\components\Prom;


use app\models\CorrectorsData;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use kartik\mpdf\Pdf;
use yii\base\Component;


/**
 * @property integer $id № of correctors
 * @property string $date  date in format Y-m-d H:i:s
 * @property string $type report type one in('day','month')
 * @property string $format report  format one in ('pdf','html')
 * @property string $filename filename pdfreport
 */

class Prom extends Component
{


    public $id;
    public $date;
    public $contractHour;
    public $type;
    public $format;



    private function CompileReport(){

        $cd=CorrectorsData::find()->where(['all_id'=>$this->id])->one();
        $cc=CorrectorToCounter::find()->where(['id'=>$this->id])->one();


        $dateOptionsprev=DateOptions::find()
            ->where(['all_id'=>$this->id])
            ->andWhere(['<','created_at',$this->date])
            ->orderBy(['created_at'=>SORT_DESC])
            ->one();


        $dt=new \DateTime($this->date);
        $dt->add(new \DateInterval('PT'.$dateOptionsprev->contract_hour.'H'));
        $dn=clone $dt;
        $dn->add(new \DateInterval('P1D'));

        $dateOptions=DateOptions::find()
            ->where(['all_id'=>$this->id])
            ->andWhere(['>','created_at',$dt->format('Y-m-d H:i:s')])
            ->andWhere(['<','created_at',$dn->format('Y-m-d H:i:s')])
            ->orderBy(['created_at'=>SORT_ASC])
            ->one();
    if(empty($dateOptions)){
        $dateOptions=$dateOptionsprev;
    }

        echo $dateOptions->contract_hour;

        $this->contractHour=$dateOptions->contract_hour;

        $parts=new PromReportParts();
        $parts->date=$this->date;
        $parts->id=$this->id;
        $parts->type=$this->type;
        $parts->contractHour=$this->contractHour;
        $parts->dimensionType=$cd->measured_value;
        $parts->progName=$cc->prog;

        $content='<div style="width: 100%;height: 100%;" xmlns="http://www.w3.org/1999/html">';
        $content.=$parts->Header();
        $content.=$parts->CorrectorInfo();
        $content.=$parts->StaticData();
        $content.=$parts->TimeData();
        $content.=$parts->SummaryData();
        $content.=$parts->Emergency();
        $content.=$parts->Diagnoistic();
        $content.=$parts->Intervention();
        $content.=$parts->EndReport();
        $content.='</div>';
        return $content;

    }

    public function getFilename(){

        $counter=\app\models\CorrectorToCounter::find()->where(['id'=>$this->id])->one();

        $dt= new \DateTime($this->date);
        $dt->add(new \DateInterval('PT'.$this->contractHour.'H'));

        return $counter->address->fulladdress."-".$dt->format('Y-m-d').'.pdf';

    }


    public function GetReport(){

        $content=$this->CompileReport();

        switch($this->format){
            case "html":  return $content; break;
            default: return $this->PDFOutput($this->filename,$content);
        }

    }

    private  function PDFOutput($filename="output.pdf",$content){

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename'=>$filename,
            // 'defaultFont'=>,

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial;color:#000000}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => [ 'SetFooter'=>["Лист {PAGENO} из 2","RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline=0;


        return $pdf->render();

    }

}