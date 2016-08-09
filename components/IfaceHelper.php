<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 19.05.2016
 * Time: 20:38
 */

namespace app\components;


use Yii;
use yii\base\Component;
use yii\helpers\Html;

class IfaceHelper extends Component
{

    public static function ProgressBar($label = '', $current = '', $target = '', $percentage = 0, $progressBarStyle = '')
    {
        $string = '';
        $string .= Html::tag('span', $current . '/' . $target, ['class' => 'pull-right']);
        $string = Html::tag('span', $label . $string, ['class' => 'text']);
        $string .= Html::tag('div', Html::tag('div', '', ['class' => 'progress-bar ' . $progressBarStyle, 'style' => 'width:' . $percentage . '%']), ['class' => 'progress']);
        return Html::tag('div', $string, ['class' => 'col-xs-6 col-sm-6 col-md-12 col-lg-12']);
    }


    public static function EasyPieChart($percent = 0,$maxValue=0, $size = 0, $pieChartStyle = '', $spanStyle = '',$label='',$value=0)
    {
        Yii::$app->getView()->registerJsFile('/js/plugin/easy-pie-chart/jquery.easy-pie-chart.js');
        $span = Html::tag('span', $value, ['class' => $pieChartStyle . ' ' . $spanStyle,'style'=>'display:none']);
        $span .= Html::tag('p', $value, ['class' => $pieChartStyle . ' ' . $spanStyle,]);
        $span.=Html::tag('canvas','',['height'=>$size,'width'=>$size]);
        return Html::tag('div', $span, [
            'class' => 'easy-pie-chart  ' . $pieChartStyle,
            'data-percent' => $percent,
            'data-size' => $maxValue,
            'data-value' => $value,
            'data-pie-size' => $size
        ]).' <span class="easy-pie-title"> '.$label.' </span>';
    }

    public static function MinMax($min,$max,$minStyle,$maxStyle)
    {
       return '<ul class="smaller-stat hidden-sm pull-right">
                <li>
                    <span class="label '.$maxStyle.'"><i class="fa fa-caret-up"></i>'.round($max,1).'</span>
                </li>
                <li>
                    <span class="label '.$minStyle.'"><i class="fa fa-caret-down"></i>'.round($min,1).'</span>
                </li>
            </ul>';
    }


    public static function Sparklines($type='line',$data='',$width="70px",$height="33px",$class="sparkline txt-color-greenLight hidden-sm hidden-md pull-right")
    {
        Yii::$app->getView()->registerJsFile('/js/plugin/sparkline/jquery.sparkline.js');

        return' <div data-fill-color="transparent" data-sparkline-width="'.$width.'" data-sparkline-height="'.$height.'"
                 data-sparkline-type="'.$type.'" class="'.$class.'">
                '.$data.'
            </div>';


    }
}