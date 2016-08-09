<?php

namespace app\modules\counter\components;

use yii\jui\DatePicker;
use yii\widgets\Menu;
use yii\helpers\Html;
use Yii;
use \app\models\UserCounters;

class EventCalendar extends Menu {

    public $counterId;

    public function init() {
        $this->renderWidget();
        $this->getView()->registerJs($this->generateEvents(), 1);
        $this->getView()->registerJsFile('/js/eventCalendar/moment/moment.min.js');
        //$this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/jquery.fullcalendar.min.js');
        $this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/fullcalendar.js');
        $this->getView()->registerJsFile('/js/eventCalendar/fullcalendar/lang/ru.js');
        $this->getView()->registerJsFile('/js/eventCalendar/eventCalendar.js');
        $this->getView()->registerJsFile('/js/eventCalendar/myEventCalendar.js');
        $this->getView()->registerCssFile('/js/eventCalendar/fullcalendar/fullcalendar.css');
        $this->getView()->registerCssFile('/css/smartadmin-production-plugins.min.css');


    }

    public function generateHeader() {


        return '<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
		<h2> Календарь <span> </span> </h2>';
		
    }

    public function generateContent() {

        return '<div style = "padding:15px;height:100%" data-container="body">
                    <div class="widget-body no-padding" data-container="body">
			<div id="calendar" ></div>
			<div id="calendarText" data-container="body"></div>
			<!-- end content -->
                    </div>		
		</div>
<div style="float: left;width:49%;margin-left: 15px;height: 100%">'
. $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'id'=>'synhroEditorWidget',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => 'Редактор синхронизации <span> </span>'
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(), true),
        'content' => ''
            . '<p style="margin:10px;">№<span style="float:right;margin-right:10px" id="serial_number"></span></p>'
            . '<p style="margin:10px;">Интервал(ч)<input style="float:right;margin-right:10px" type="text" id="update_interval"></p>'
            . '<p style="margin:10px;">Тип<select name="type" style="float:right;margin-right:10px" id="type">
                            <option disabled>Выберите тип</option>
                            <option value="once">По дате</option>
                            <option value="every_day">Каждый день</option>
                            </select></p>'
            . '<p style="margin:10px;margin-top:15px">Гарантированный выход'
            .DatePicker::widget(['dateFormat' => 'MM-dd','clientOptions' => ['nextText' => '>','prevText' => '<'],'options' => [ 'id' => 'month_update',"style"=>"float:right;margin-right:10px"]])
            . '</p>'
            . '<input type="button" id="submit" value="Сохранить">'
    )).'</div>';


    }

    public function renderWidget() {



        echo $this->generateContent();
        $this->getView()->registerJs("

        $('#calendar').fullCalendar({})
        ;",3);

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
			title: 'Обновление показаний',
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
			            title: 'месячное обновление',
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
