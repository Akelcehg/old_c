<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExcelComponent
 *
 * @author alks
 */

namespace app\components;

use yii\base\Component;
use app\components\myPhpExcel\my_PHPExcel_Chart_DataSeriesValues;

class ExcelChart extends Component {
    
    public $nameFile ='output.xlsx';
    public $counterDataArray=[];
    public $dataArray1 = [];
    public $dataArray2 = [];
    public $mode = 'day';
    public $lineColorArray = ['ff0000', '00ff00', '0000ff', 'ff00ff', '000000', '00ffff','ffff00'];
    public $markerColorArray = ['ff0000', '00ff00', '0000ff', 'ff00ff', '000000', '00ffff','ffff00'];
    private $ews;
    private $dayOfWeek = ['понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'субота', 'воскресенье'];

    private function dataLoad() {
        
        if($this->counterDataArray)
            {
                $this->ews->fromArray($this->counterDataArray, 0, 'A1', true);
            }

        if ($this->mode == 'week') {
            if ($this->dataArray1) {
                $this->ews->setCellValue('a9', 'время (расход)');

                for ($i = 2; $i <= 8; $i++) {
                    $this->ews->setCellValueByColumnAndRow($i-1, 10, $this->dayOfWeek[$i-2]);
                }

                $this->ews->fromArray($this->dataArray1, 0, 'A11', true);
            }

            if ($this->dataArray2) {
                $this->ews->setCellValue('A40', 'время ( температура)');

                for ($i =2; $i <= 8; $i++) {
                    $this->ews->setCellValueByColumnAndRow($i-1, 40, $this->dayOfWeek[$i-2]);
                }

                $this->ews->fromArray($this->dataArray2, 0, 'A41', true);
            }
        } else {

            if ($this->dataArray1) {
                $this->ews->setCellValue('A10', 'время (расход)'); // Sets cell 'a1' to value 'ID 
                $this->ews->setCellValue('B10', 'расход');
                $this->ews->fromArray($this->dataArray1, 0, 'A11', true);
            }

            if ($this->dataArray2) {
                $this->ews->setCellValue('A42', 'время (темп)'); // Sets cell 'a1' to value 'ID
                $this->ews->setCellValue('B42', 'температура');
                $this->ews->fromArray($this->dataArray2, 0, 'A43', true);
            }
        }
    }

    public function setHeaderStyle() {
        $header = 'a1:h1';
        $style = array(
            'font' => array('bold' => true,),
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,),
        );

        $this->ews->getStyle($header)->applyFromArray($style);
    }

    private function autoSizeColumn($beginColumn = 'a', $endColumn = 'b') {

        for ($col = ord($beginColumn); $col <= ord($endColumn); $col++) {
            $this->ews->getColumnDimension(chr($col))->setAutoSize(true);
        }
    }

    private function addExcelChart($leftTopCell = 'A1', $rightDownCell = 'B25', $leftTopChart = 'J2', $rightDownChart = 'V18', $lineColorArray = [], $markerColorArray = [], $nameChart = 'chart') {

        if (!$lineColorArray) {

            $lineColorArray = $this->lineColorArray;
        }

        if (!$markerColorArray) {

            $markerColorArray = $this->markerColorArray;
        }


        $leftTopCellChar = '';
        $leftTopCellNumber = '';

        $leftTopCell = str_split($leftTopCell, 1);
        for ($j = 0; $j < count($leftTopCell); $j++) {
            if (!is_numeric($leftTopCell[$j])) {
                $leftTopCellChar.=$leftTopCell[$j];
            } else {
                $leftTopCellNumber.=$leftTopCell[$j];
            }
        }

        $rightDownCellChar = '';
        $rightDownCellNumber = '';
        $rightDownCell = str_split($rightDownCell, 1);
        for ($j = 0; $j < count($rightDownCell); $j++) {
            if (!is_numeric($rightDownCell[$j])) {
                $rightDownCellChar.=$rightDownCell[$j];
            } else {
                $rightDownCellNumber.=$rightDownCell[$j];
            }
        }



        $xal = array(new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$' . $leftTopCellChar. '$' . ($leftTopCellNumber + 1) . ':$' . $leftTopCellChar . '$' . $rightDownCellNumber, NULL, 90));

        $dsl = [];
        $dsv = [];
        $i = -1;

        for ($col = ord($leftTopCellChar) + 1; $col <= ord($rightDownCellChar); $col++) {


            $i++;
            $dsl[] = new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$' . chr($col) . '$'.$leftTopCellNumber, NULL, 1);

            $dsv[] = new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$' . chr($col) . '$' . ($leftTopCellNumber +1) . ':$' . chr($col) . '$' . $rightDownCellNumber, NULL, 90, [], 'diamond', $lineColorArray[$i], $markerColorArray[$i]);
        }

        $chart = $this->drawExcelChart($dsl, $xal, $dsv, $leftTopChart, $rightDownChart, $nameChart);

        $this->ews->addChart($chart);
    }

    public function getTableWithChart() {

        $ea = new \PHPExcel();

        $ea->getProperties()
                ->setCreator('Aser')
                ->setTitle('Chart to Excel')
                ->setLastModifiedBy('Aser')
                ->setDescription('bla-bla-bla')
                ->setSubject('bla-bla-bla')
                ->setKeywords('bla-bla-bla')
                ->setCategory('bla-bla-bla');

      
        $this->ews = $ea->getSheet(0);

        $this->ews->setTitle('Data');

        $this->dataLoad();

        //$this->setHeaderStyle();
        
       
        $this->autoSizeColumn();
        

        if ($this->dataArray1) {
            
            switch ($this->mode) {
                case 'week':
                   $this->addExcelChart('A10', 'G34', 'I10', 'Q34', [], [], 'расход за неделю');
                    break;
                case 'month':
                   $this->addExcelChart('A10', 'B41', 'D10', 'Q34', ['ff0000'], ['ff0000'], 'расход за месяц');
                    break;
                case 'day':
                    $this->addExcelChart('A10', 'B34', 'D10', 'Q34', ['ff0000'], ['ff0000'], 'расход');
                    break;
            }
        }

        if ($this->dataArray2) {
            
            switch ($this->mode) {
                case 'week':
                    $this->addExcelChart('A41', 'G65', 'I42', 'Q70', [], [], 'температура за неделю');
                    break;
                case 'month':
                    $this->addExcelChart('A43', 'B73', 'D42', 'Q70', ['0000ff'], ['0000ff'], 'температура  за месяц');
                    break;
                case 'day':
                    $this->addExcelChart('A41', 'B65', 'D42', 'Q65', ['0000ff'], ['0000ff'], 'температура');
                    break;
            }
        }

        $writer2 = \PHPExcel_IOFactory::createWriter($ea, 'Excel2007wc');
        $writer2->setIncludeCharts(true);
        $writer2->save($this->nameFile);
    }

    public function drawExcelChart($dsl, $xal, $dsv, $topLeftPosition, $bottomRightPosition, $chartName) {

        $ds = new \PHPExcel_Chart_DataSeries(
                \PHPExcel_Chart_DataSeries::TYPE_LINECHART, \PHPExcel_Chart_DataSeries::GROUPING_STANDARD, range(0, count($dsv) - 1), $dsl, $xal, $dsv
        );

        $title = new \PHPExcel_Chart_Title($chartName);

        $pa = new \PHPExcel_Chart_PlotArea(NULL, array($ds));
        $legend = new \PHPExcel_Chart_Legend(\PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

        $chart = new \PHPExcel_Chart(
                $chartName, $title, $legend, $pa, true, 0, NULL, NULL
        );

        $chart->setTopLeftPosition($topLeftPosition);
        $chart->setBottomRightPosition($bottomRightPosition);

        return $chart;
    }

    /* $dsl2 = array(
      // название графика
      new \PHPExcel_Chart_DataSeriesValues('String', 'Data!$D$1', NULL, 1),
      );

      $xal2 = array(// линия лейбелов
      new \PHPExcel_Chart_DataSeriesValues(
      'String', 'Data!$C$2:$C$' . $xAxislength, NULL, 90
      ),
      );
      if ($this->mode == 'week') {



      $dsv2 = array(
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$B$' . ($xAxislength + 2) . ':$B$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$C$' . ($xAxislength + 2) . ':$C$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$D$' . ($xAxislength + 2) . ':$D$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$E$' . ($xAxislength + 2) . ':$E$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$F$' . ($xAxislength + 2) . ':$F$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$G$' . ($xAxislength + 2) . ':$G$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$H$' . ($xAxislength + 2) . ':$H$' . $xAxislength * 2 + 2, NULL, 90, [], 'diamond', 'ff0000', 'ff0000'),
      );
      } else {
      $dsv2 = array(//данные
      new my_PHPExcel_Chart_DataSeriesValues('Number', 'Data!$D$2:$D$' . $xAxislength, NULL, 90
      , [], 'diamond', '0000ff', '0000ff'),
      );
      }


      $chart2 = $this->addExcelChart($dsl2, $xal2, $dsv2, 'J19', 'V35', 'температура');

      $this->ews->addChart($chart2); */
}
