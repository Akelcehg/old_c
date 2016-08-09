<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use app\components\ChartCalc;
use app\components\ExcelChart;
use app\models\Counter;
use app\models\Modem;

class ExcelController extends Controller {

    public function actionAjaxchartbyday() {

        $counter_id = Yii::$app->request->get('counter_id', null);
        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $counter = Counter::findOne(['id' => $counter_id]);
        $modem = Modem::findOne(['serial_number' => $counter->modem_id]);

        $chartDataCons = $chartCalc->chartByDay();

        $chartDataTemp = $chartCalc->tempChartByDay();
        
        $data=[];
        $data2=[];
        
        for ($i = 0; $i < count($chartDataCons); $i++) {
            if (isset($chartDataCons[$i]['data']) and isset($chartDataCons[$i]['label'])) {
                $data[] = [
                    $chartDataCons[$i]['label'],
                    $chartDataCons[$i]['data'][0],
                ];
            }
        }

        for ($i = 0; $i < count($chartDataTemp); $i++) {
            if ($modem->type == 'built-in' and isset($chartDataTemp[$i]['label'])) {
                $data2[] = [
                    $chartDataTemp[$i]['label'],
                    $chartDataTemp[$i]['data'][0],
                ];
            }
        }

        $excelChart = new ExcelChart();
        $excelChart->dataArray1 = $data;



        if ($modem->type == 'built-in') {
            $excelChart->dataArray2 = $data2;
        }

        $title = $counter_id . '-' . $counter->address->fulladdress . '-' . date('Y-m-d');
        $title = strtr($title, ["\\" => '-', "/" => '-']);
        $excelChart->nameFile = 'excel/' . $title . '.xlsx';
        $excelChart->mode = 'day';
        $counterArray=$counter->getTitledArray(['serial_number','modem_id','user_type','account','fulladdresswithcity','flat']);
        
        $counterArray[]=['Дата Выгрузки',date('d.m.Y')];
        $counterArray[]=['Период',$this->YmdToDmy($beginDate).' - '.$this->YmdToDmy($endDate)];

            
        
        $excelChart->counterDataArray=$counterArray;
        $excelChart->getTableWithChart();

        return $title;
    }

    public function YmdToDmy($Ymd='0000-00-00')
            {
                $YmdArray=  explode('-', $Ymd);
                
                return date('d.m.Y',  mktime(0, 0, 0,$YmdArray[1], $YmdArray[2], $YmdArray[0]));
            }
    
    public function actionAjaxchartbyweek() {

        $counter_id = Yii::$app->request->post('counter_id', null);
        $data1 = Yii::$app->request->post('data', []);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->data = $data1;

        $counter = Counter::findOne(['id' => $counter_id]);
        $modem = Modem::findOne(['serial_number' => $counter->modem_id]);

        $chartDataCons = $chartCalc->chartByWeek();

        $chartDataTemp = $chartCalc->tempChartByWeek();

        $data=[];
        $data2=[];
        
        for ($i = 0; $i < count($chartDataCons); $i++) {
            if (isset($chartDataCons[$i]['data']) and isset($chartDataCons[$i]['label'])) {
                $data[] = [
                    $chartDataCons[$i]['label'],
                    $chartDataCons[$i]['data'][0],
                    $chartDataCons[$i]['data'][1],
                    $chartDataCons[$i]['data'][2],
                    $chartDataCons[$i]['data'][3],
                    $chartDataCons[$i]['data'][4],
                    $chartDataCons[$i]['data'][5],
                    $chartDataCons[$i]['data'][6],
                ];
            }
        }

        for ($i = 0; $i < count($chartDataTemp); $i++) {
            if ($modem->type == 'built-in' and isset($chartDataTemp[$i]['label'])) {
                $data2[] = [
                    $chartDataTemp[$i]['label'],
                    $chartDataTemp[$i]['data'][0],
                    $chartDataTemp[$i]['data'][1],
                    $chartDataTemp[$i]['data'][2],
                    $chartDataTemp[$i]['data'][3],
                    $chartDataTemp[$i]['data'][4],
                    $chartDataTemp[$i]['data'][5],
                    $chartDataTemp[$i]['data'][6],
                ];
            }
        }


        $excelChart = new ExcelChart();
        $excelChart->dataArray1 = $data;



        if ($modem->type == 'built-in') {
            $excelChart->dataArray2 = $data2;
        }

        $title = $counter_id . '-' . $counter->address->fulladdress . '-' . date('Y-m-d');
        $title = strtr($title, ["\\" => '-', "/" => '-']);
        $excelChart->nameFile = 'excel/' . $title . '.xlsx';
        $excelChart->mode = 'week';
        
        $counterArray=$counter->getTitledArray(['serial_number','modem_id','user_type','account','fulladdresswithcity','flat']);
 
        
        //$counterArray[]=['Дата Выгрузки',date('Y-m-d')];
        //$counterArray[]=['Период',$data1[1].' - '.$data1[6]];
        
        $counterArray[]=['Дата Выгрузки',date('d.m.Y')];
        $counterArray[]=['Период',$this->YmdToDmy($data1[1]).' - '.$this->YmdToDmy($data1[6])];
        
        $excelChart->counterDataArray=$counterArray;
        
        $excelChart->lineColorArray=['ff0000','ffa500','ffff00','008B00','87ceff','0000ff','551a8B'];
        $excelChart->markerColorArray=['ff0000','ffa500','ffff00','008B00','87ceff','0000ff','551a8B'];
        $excelChart->getTableWithChart();

        return $title;
    }

    public function actionAjaxchartbymonth() {

        $counter_id = Yii::$app->request->get('counter_id', null);
        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $chartCalc = new ChartCalc();
        $chartCalc->counter_id = $counter_id;
        $chartCalc->beginDate = $beginDate;
        $chartCalc->endDate = $endDate;

        $counter = Counter::findOne(['id' => $counter_id]);
        $modem = Modem::findOne(['serial_number' => $counter->modem_id]);

        $chartDataCons = $chartCalc->graph();

        $chartDataTemp = $chartCalc->tempChart();

        $data=[];
        $data2=[];
        
        for ($i = 0; $i < count($chartDataCons); $i++) {

            if (isset($chartDataCons[$i]['data']) and isset($chartDataCons[$i]['label'])) {
                $data[] = [
                    $chartDataCons[$i]['label'],
                    $chartDataCons[$i]['data'][0],
                ];
            }
        }

        for ($i = 0; $i < count($chartDataTemp); $i++) {
            if ($modem->type == 'built-in' and isset($chartDataTemp[$i]['label'])) {
                $data2[] = [
                    $chartDataTemp[$i]['label'],
                    $chartDataTemp[$i]['data'][0],
                ];
            }
        }

        $excelChart = new ExcelChart();
        $excelChart->dataArray1 = $data;



        if ($modem->type == 'built-in') {
            $excelChart->dataArray2 = $data2;
        }

        $title = $counter_id . '-' . $counter->address->fulladdress . '-' . date('Y-m-d');
        $title = strtr($title, ["\\" => '-', "/" => '-']);
        $excelChart->nameFile = 'excel/' . $title . '.xlsx';
        $excelChart->mode = 'month';
        
        $counterArray=$counter->getTitledArray(['serial_number','modem_id','user_type','account','fulladdresswithcity','flat']);
        
        $counterArray[]=['Дата Выгрузки',date('d.m.Y')];
        $counterArray[]=['Период',$this->YmdToDmy($beginDate).' - '.$this->YmdToDmy($endDate)];
        
        $excelChart->counterDataArray=$counterArray;
        
        $excelChart->getTableWithChart();

        return $title;
    }

    public function actionExportexcel() {
        $title = Yii::$app->request->get('title', 0);
        $file = 'excel/' . $title . '.xlsx';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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

}
