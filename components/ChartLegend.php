<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Chart
 *
 * @author alks
 */

namespace app\components;

use \yii\base\Widget;
use yii\helpers\Html;
use Yii;

class ChartLegend extends Widget
{

    public $name = 'MyChart';



    public function init()
    {

        echo '<div style="dispaly:inline-block" id="chart' . $this->name . 'Legend"></div>';
    }

}
