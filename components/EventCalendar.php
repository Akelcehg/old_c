<?php

namespace app\components;

use app\models\Language;
use yii\base\Widget;
use yii\widgets\Menu;
use yii\helpers\Html;
use Yii;
use \app\models\UserCounters;

class EventCalendar extends Menu {

    public $counterId;

    public function init() {
        $this->getView()->registerJs($this->generateEvents(), 1);
        $this->getView()->registerJsFile('/js/eventCalendar/moment/moment.min.js');
        //$this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/jquery.fullcalendar.min.js');
        $this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/fullcalendar.js');
        $this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/lang/lang-all.js');
        $this->getView()->registerJsFile('/js/eventCalendar/eventCalendar.js');
        $this->getView()->registerJsFile('/js/eventCalendar/myEventCalendar.js');
        $this->getView()->registerCssFile('/js/eventCalendar/fullcalendar/fullcalendar.css');
        $this->getView()->registerCssFile('/css/smartadmin-production-plugins.min.css');

        $this->getView()->registerJs('    $(document).ready(function() {

        $(\'#calendar\').fullCalendar({
            lang: \''.Language::getCurrent()->url.'\'
        });

    });
    ',5);




        $this->getView()->registerCssFile('/css/smartadmin-production-plugins.min.css');
        $this->getView()->registerCssFile('/js/eventCalendar/fullcalendar/fullcalendar.css');

        $this->renderWidget();
    }

    public function generateHeader() {


        return '<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2> '.Yii::t('counter','calendar').' <span> </span> </h2>';
		
    }

    public function generateContent() {

        return '<div style = "padding:15px;height:100%">
                    <div class="widget-body  no-padding" data-container="body">
			<div id="calendar"></div>	
			<!-- end content -->
                    </div>		
		</div>';
    }

    public function renderWidget() {

        echo $this->render('/layouts/partials/jarviswidget', array(
            'class' => 'jarviswidget-color-blue',
            'id'=>'calendarWrapperWidget',
            'header' => $this->generateHeader(),
            'content' => $this->generateContent()
        ));
    }

    public function timestampToSec($timestamp) {

        $timestampArray = explode(' ', $timestamp);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        $hourMinSecArray = explode(':', $timestampArray[1]);
        return mktime(0, 0, 0, $yearMounthDayArray[1], $yearMounthDayArray[2], $yearMounthDayArray[0]);
    }

    public function generateEveryDayUpdate($counter) {

        $createAtInSec = $this->timestampToSec($counter->created_at);
        $monthBeginInSec = $this->timestampToSec(date('Y-m') . '-1 00:00:00');

        $firstEventTimeInSec = 1 - fmod($monthBeginInSec - $createAtInSec, $counter->update_interval * 3600);

        $firstEventTime = $monthBeginInSec + $firstEventTimeInSec * 3600;
        $countUpdateInDay=(3600 * 24)/($counter->update_interval * 3600);
        $string = '';
        
        for ($i = 1; $i <= 31; $i++) {
            for ($j = 1; $j <= $countUpdateInDay; $j++) {
                $string.="{
			id:" . $counter->serial_number . ",
			title: '".Yii::t('counter','indications_update')."',
			start: new Date(" . date('Y', $firstEventTime) . "," . date('n', $firstEventTime) . "-1," . date('d', $firstEventTime) . "," . date('H', $firstEventTime) . "," . date('i', $firstEventTime) . "),
                        end : new Date(" . date('Y', $firstEventTime) . "," . date('n', $firstEventTime) . "-1," . date('d', $firstEventTime) . "," . date('H', $firstEventTime) . "," . date('i', $firstEventTime) . "+15),            
                        allDay: false,
                        editable: false,
			className: [\"event\", \"bg-color-blue\"],
			icon: 'fa-clock-o'
                    },";
                $firstEventTime+=$counter->update_interval  * 3600;
            }
            
        }

        return $string;
    }

    public function generateMonthUpdate($counter) {

        $timestampArray = explode(' ', $counter->month_update);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        $hourMinSecArray = explode(':', $timestampArray[1]);

        return "{
			            title: '".Yii::t('counter','monthly_update')."',
			            start: new Date(" . $yearMounthDayArray['0'] . ", " . $yearMounthDayArray['1'] . "-1," . $yearMounthDayArray['2'] . "," . $hourMinSecArray['0'] . "," . $hourMinSecArray['1'] . "),
			            end: new Date(" . $yearMounthDayArray['0'] . ", " . $yearMounthDayArray['1'] . "-1," . $yearMounthDayArray['2'] . "," . $hourMinSecArray['0'] . "," . $hourMinSecArray['1'] . "+15),
                                    description: 'long description',
			            className: [\"event\", \"bg-color-greenLight\"],
			            icon: 'fa-check'
			        },";
    }

    public function generateEvents() {

        //$counter = UserCounters::find()->where(['serial_number' => 96])->one();
        //$everyDayUpdate = $this->generateEveryDayUpdate($counter);
       // $monthUpdate = $this->generateMonthUpdate($counter);

        $string = "var myEvents=[]";
        return $string;
    }

}
