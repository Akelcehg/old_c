<?php

namespace app\modules\api\v1\controllers;

use app\models\CorrectorToCounter;
use app\models\DayData;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\rest\Controller;

use yii\filters\auth\QueryParamAuth;

class PromMonthReportController extends Controller
{
    public $year;
    public $month;


 public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' =>QueryParamAuth::className(),

        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $filter= Yii::$app->request->get('filter', "мак");
        $this->year= Yii::$app->request->get('year',date("y"));
        $this->month= Yii::$app->request->get('month', date("n"));
        if(!is_null($filter)){
            $this->ListCorrectorsByCsv($filter);
        }else{
            return "invalid filter parametr";
        }

    }

    protected function timestampToDBF($timestamp)
    {
        $timestampArray = explode(' ', $timestamp);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        return $yearMounthDayArray[0] . $yearMounthDayArray[1] . $yearMounthDayArray[2];
    }

    public function ListCorrectorsByCsv($filter)
    {

        $filter=urldecode($filter);
        $correctorList=CorrectorToCounter::find()
            ->andFilterWhere(['LIKE', 'company', $filter])
            ->all();


            $this->correctorsToCSV($correctorList);

    }

    protected function getDaylabel($dt){



        $arr=[];

        if($this->month==date('n')){

            $j=date('d')-1;

        }else
        {
            $j=$dt->format('t');
        }

        for ($i=1;$i<=$j;$i++){
         $arr[]=$i;
        }
        return $arr;
    }


    protected function correctorsToCSV($correctorList)
    {
        $dt=new \DateTime($this->year.'-'.$this->month.'-01');
        $arr[]=[''];
       $arr[]=array_merge(['УЕГГ ПС','Населений пункт ПС','№','Назва','Назва','Адреса','Договір','Ліміти','Всього'],$this->getDaylabel($dt));
        foreach ($correctorList as $oneCorrector) {
            //print_r($this->timestampToDBF($oneCorrector->updated_at));
            echo '</br>';

                if (!empty($oneCorrector->contract)) {


                    $arr3 = [


                        $oneCorrector->promInfo->uegg_ps,
                        $oneCorrector->promInfo->np,
                        $oneCorrector->promInfo->number_ca,
                        $oneCorrector->promInfo->company,
                        $oneCorrector->promInfo->name,
                        $oneCorrector->promInfo->address,
                        $oneCorrector->promInfo->contract,
                        '',
                        ''
                       // round($oneCorrector->lastDayData->v_sc + $oneCorrector->lastDayData->vav_sc, 3),

                    ];
                    $hd=DayData::find()->where(['all_id'=>$oneCorrector->id,'year'=>$dt->format("y"),"month"=>$dt->format("n")])->orderBy(["day"=>SORT_ASC])->groupBy("day")->all();

                    $arr2=[];

                    foreach( $hd as $od){
                        $arr2[]=round($od->v_sc+$od->vav_sc,3)/1000;
                    }

                    $arr[]=array_merge($arr3,$arr2);
                   // print_r($arr);
                }

            }

            $this->CSV($arr);

    }

    protected function CSV($array){

        //print_r($columns);

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()
            ->setCreator('Aser')
            ->setTitle('Export');




        $excelSheet = $objPHPExcel->getSheet(0);




        $excelSheet->fromArray($array, 0, 'A1', true);

        $writer2 = \PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
        //$writer2->setDelimiter(";");
        $writer2->setIncludeCharts(true);
       // $writer2->setExcelCompatibility(true);
        $title=date('Y-m-d');
        $file = sys_get_temp_dir() . '/'.'Оперативный учет' .date('Y-m') .'.xls';
        $writer2->save($file);


        $this->Export();
    }

    public function Export()
    {
        $file = sys_get_temp_dir() . '/'.'Оперативный учет' .date('Y-m') .'.xls';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла

            header('Content-Description: File Transfer');
            header('Content-Type: application/xls');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // читаем файл и отправляем его пользователю
            readfile($file);

            unlink($file);
            exit;
        }
    }



      public function actions()
     {
          $actions = parent::actions();

        /*$actions['index'] = [
            'class'       => 'app\modules\api\v1\actions\SearchAction',
            'modelClass'  => $this->modelClass,
        ];

        /* $actions['view'] = [
            'class' => 'yii\rest\ViewAction',
            'modelClass'  => $this->modelClass,
        ];

        $actions['create'] = [
            'class' => 'yii\rest\CreateAction',
            'modelClass'  => $this->modelClass,
        ];

        $actions['update'] = [
            'class' => 'yii\rest\UpdateAction',
            'modelClass'  => $this->modelClass,
            'scenario' => 'editCounter'
        ];*/

      //  return $actions;
    }





}