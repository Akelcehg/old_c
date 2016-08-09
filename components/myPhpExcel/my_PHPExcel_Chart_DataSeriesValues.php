<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of my_PHPExcel_Chart_DataSeries
 *
 * @author alks
 */
namespace app\components\myPhpExcel;
use PHPExcel_Chart_DataSeriesValues;

class my_PHPExcel_Chart_DataSeriesValues extends PHPExcel_Chart_DataSeriesValues {
    //put your code here
    
    public $lineColor;
    public $markerColor;
    
    public function __construct(
            $dataType = self::DATASERIES_TYPE_NUMBER,
            $dataSource = null,
            $formatCode = null,
            $pointCount = 0,
            $dataValues = array(),
            $marker = null,
            $lineColor=null,
            $markerColor=null
            ) 
                {
        
        $this->lineColor=$lineColor;
        $this->markerColor=$markerColor;
        
        parent::__construct($dataType, $dataSource, $formatCode, $pointCount, $dataValues, $marker);
    }
    
}
