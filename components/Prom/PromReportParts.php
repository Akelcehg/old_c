<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.03.16
 * Time: 12:33
 */

namespace app\components\Prom;


use app\components\FlouTechReportGenerator;
use app\models\DateTime;
use app\models\DayData;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\Intervention;
use app\models\MomentData;
use app\models\Name;
use app\models\StaticDataGeneral;
use app\models\StaticDataHard;
use app\models\StaticDataSensor;
use Yii;
use yii\helpers\Html;

class PromReportParts
{
    public $id;
    public $date;
    public $contractHour;
    public $type = 'day';
    public $dimensionType = 'Quantity';
    public $progName="ConCore";

    public function Header()
    {
        $dateTime = DateTime::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        $programName = "ASER " . Yii::$app->params['version'];
        Yii::$app->formatter->locale = 'ru_RU';

        switch ($this->type) {
            case 'month':
                $reportType = 'МЕСЯЧНЫЙ ОТЧЕТ';
                $datetext = Yii::$app->formatter->asDate($this->date, 'LLLL YYYY');
                break;
            default:
                $reportType = 'СУТОЧНЫЙ ОТЧЕТ';
                $datetext = Yii::$app->formatter->asDate($this->date, 'dd MMMM YYYY');
                break;
        }

        return $this->renderHeader($datetext, $dateTime, $reportType, $programName);

    }

    private function renderHeader($datetext, $data, $reportType, $programName = '')
    {


        $string = '<div style="float: right; width:150px">
                    <p> Коммерческий отчет </p>
                </div>
        
        
                <div style="width: 100%;text-align: center; margin-top:20px">
                    <p> ' . $reportType . ' </p>
                    <p> за ' . $datetext . ' года</p>
                    <p>Составлен программой ' . $programName . ' по данным ' . $data->time . ' ' . $data->date . '</p>
                </div>';

        return $string;
    }

    /**
     * @return string
     */
    public function CorrectorInfo()
    {
        $name = Name::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        $staticGeneral = StaticDataGeneral::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        return $this->renderCorrectorInfo($name, $staticGeneral);
    }

    private function renderCorrectorInfo($name, $staticGeneral)
    {
        $string = '<div style=\"width: 100%;text-align: left; margin-top:10px\">';
        if($this->progName!='MConCore') {
            $string.='<span > Наименование Комплекса:' . $name->corrector_name . ' </span ><br >';
            }
        $string.='<span>Заводской номер Вычислителя:' . $staticGeneral->zavod_number . '.</span><br>
                    <span style=\"width:100%;\"><span>' . $staticGeneral->complex_name . '</span>
                    <span style=\"mar\">Т/п 1: ' . $name->tube_name . '</span></span><br>
                    <span>Версия ПО:' . $name->version . '</span><br>
                </div>';

        return $string;

    }

    public function StaticData()
    {
        $staticHard = StaticDataHard::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        $staticSensor = StaticDataSensor::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        $momentData = MomentData::find()->where(['all_id' => $this->id])->orderBy(['id' => SORT_DESC])->one();
        $handArr = [
            'Quantity' => [
                'methodName' => 'NX19 мод',
                'metodDimName' => "Счетчик",
                'contractTime' => $this->contractHour.':00',
                'pressureType' => "Абсолютное",
                'impulseOnM3' => "10.00",
            ],
            'Pressure' => [
                'methodName' => 'ГОСТ8.586-NX19 мод',
                'metodDimName' => "Перепад на СУ",
                'contractTime' =>  $this->contractHour.':00',
                'pressureType' => "Абсолютное",
                'impulseOnM3' => "10.00",
                'typeOtb' => "Угловой",
                'a0' => "10.7",
                'a0su' => "15.5",
                'a1' => "12.0",
                'a1su' => "10.5",
                'a2' => "0.0",
                'a2su' => "0.0",
                'Dvyaz' => "1.06275e-06",
                'perepNPI'=>"10.000",
                'perepVPI'=>"4001.0"


            ]
        ];

        return $this->renderStaticData($staticHard, $staticSensor, $momentData, $handArr[$this->dimensionType]);
    }

    private function renderStaticData($staticHard, $staticSensor, $momentData, $handArr)
    {


        if ($this->dimensionType == "Quantity") {

            $stringC = '
        <p>Метод расчета:' . $handArr['methodName'] . '</p>';

         if($this->progName!='MConCore') {
             $stringC .= ' <p>Наименование счетчика:' . $staticSensor->counter_name . '</p>';
         }
            $stringC .='<table width="100%">
            <tr>
                <td><p>Метод измерений:</p></td>
                <td><p>' . $handArr['metodDimName'] . '</p></td>
                <td width="50px"></td>
                <td><p>Контрактный час:</p></td>
                <td><p>' . $handArr['contractTime'] . '</p></td>
            </tr>
            <tr>
                <td><p>Плотность, кг/м3:</p></td>
                <td><p>' . round($staticHard->density, 3) . '</p></td>
                <td width="50px"></td>
                <td><p>Молярная доля СО2,%:</p></td>
                <td><p>' . round($staticHard->mol_co2, 2) . '</p></td>
            </tr>
            <tr>
                <td><p>Тип давления:</p></td>
                <td><p>' . $handArr['pressureType'] . '</p></td>
                <td width="50px"></td>
                <td><p>Молярная доля N2,%:</p></td>
                <td><p>' . round($staticHard->mol_n2, 2) . '</p></td>
            </tr>
              <tr>
                <td><p>НПИ давления , кгс/см2:</p></td>
                <td><p>' . number_format(round($staticSensor->min_mesurm_lim_p, 4), 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>ВПИ давления , кгс/см2:</p></td>
                <td><p>' . number_format(round($staticSensor->max_mesurm_lim_p, 4), 4, '.', '') . '</p></td>
            </tr>
            <tr>
                <td><p>НПИ температуры , гр.Целс:</p></td>
                <td><p>' . number_format(round($staticSensor->min_mesurm_lim_t, 4), 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>ВПИ температуры , гр.Целс</p></td>
                <td><p>' . number_format(round($staticSensor->max_mesurm_lim_t, 4), 4, '.', '') . '</p></td>
            </tr>
        </table>
        <div style="width: 100%;text-align: left; margin-top:0px">
            <span>Количесво импульсов счетчика на 1 м3:' . sprintf("%'.97s", number_format($handArr['impulseOnM3'], 4, '.', '')) . '</span><br>
            <span>Верхний предел измерений при  рабочих условиях (Qmax), м3/ч: ' . sprintf("%'.55s", number_format(round($staticSensor->max_mesurm_lim_q, 4), 4, '.', '')) . '</span><br>
            <span>Миниальный расход при рабочих условиях(Qmin), м3/ч: ' . sprintf("%'.70s", number_format(round($staticSensor->min_mesurm_lim_q, 4), 4, '.', '')) . '</span><br>
            <span>Расход при котором счетчик останавливается, м3/ч: ' . sprintf("%'.76s", number_format(round($staticSensor->qstop, 4), 2, '.', '')) . '</span><br>
            <span>При Qстоп < Qv < Qmin принимается Qv = Q min и, если нет  других аварийных признаков , накопленный объем газа добавляется к безаварийному объему </span><br>
            <span>Аварийный Объем наращиваеся за время выключения питания </span><br>
        </div>
        ';
            $string = $stringC;
        } else {
            $stringP = '
        <p>Метод расчета:' . $handArr['methodName'] . '></p>

        <table width="100%">
            <tr>
                <td><p>Метод измерений:</p></td>
                <td><p>' . $handArr['metodDimName'] . '</p></td>
                <td width="50px"></td>
                <td><p>Контрактный час:</p></td>
                <td><p>' . $handArr['contractTime'] . '</p></td>
            </tr>
            <tr>
                <td><p>Тип отбора:</p></td>
                <td><p>' . $handArr['typeOtb'] . '</p></td>
                <td width="50px"></td>
                <td><p>Отсечка, кгс/м2:</p></td>
                <td><p>' . round($staticHard->otsechka, 4) . '</p></td>
            </tr>
            <tr>
                <td><p>Плотность, кг/м3:</p></td>
                <td><p>' . round($staticHard->density, 3) . '</p></td>
                <td width="50px"></td>
                <td><p>Молярная доля СО2,%:</p></td>
                <td><p>' . round($staticHard->mol_co2, 2) . '</p></td>
            </tr>
            <tr>
                <td><p>Шероховатость Rш , мм:</p></td>
                <td><p>' . round($staticHard->sharpness, 5) . '</p></td>
                <td width="50px"></td>
                <td><p>Молярная доля N2,%:</p></td>
                <td><p>' . round($staticHard->mol_n2, 2) . '</p></td>
            </tr>
            <tr>
                <td><p>Диаметр трубы , мм:</p></td>
                <td><p>' . round($staticHard->d_tube, 3) . '</p></td>
                <td width="50px"></td>
                <td><p>Диаметр СУ , мм:</p></td>
                <td><p>' . round($staticHard->d_sug_device, 3) . '</p></td>
            </tr>
            <tr>
                <td><p>Коэф. а0 для Ктр трубы:</p></td>
                <td><p>' . $handArr['a0'] . '</p></td>
                <td width="50px"></td>
                <td><p>Коэф. а0 для Ктр СУ:</p></td>
                <td><p>' . $handArr['a0su'] . '</p></td>
            </tr>
            <tr>
                <td><p>Коэф. а1 для Ктр трубы:</p></td>
                <td><p>' . $handArr['a1'] . '</p></td>
                <td width="50px"></td>
                <td><p>Коэф. а1 для Ктр СУ:</p></td>
                <td><p>' . $handArr['a1su'] . '</p></td>
            </tr>
            <tr>
                <td><p>Коэф. а2 для Ктр трубы:</p></td>
                <td><p>' . $handArr['a2'] . '</p></td>
                <td width="50px"></td>
                <td><p>Коэф. а2 для Ктр СУ:</p></td>
                <td><p>' . $handArr['a2su'] . '</p></td>
            </tr>
            <tr>
                <td><p>Дин. вязкость, кгс*с/м2</p></td>
                <td><p>' . $handArr['Dvyaz'] . '</p></td>
                <td width="50px"></td>
                <td><p>Тип давления:</p></td>
                <td><p>' . $handArr['pressureType'] . '</p></td>
            </tr>
              <tr>
                <td><p>НПИ давления , кгс/см2:</p></td>
                <td><p>' . number_format(round($staticSensor->min_mesurm_lim_p, 4), 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>ВПИ давления , кгс/см2:</p></td>
                <td><p>' . number_format(round($staticSensor->max_mesurm_lim_p, 4), 4, '.', '') . '</p></td>
            </tr>
            <tr>
                <td><p>НПИ температуры , гр .Целс:</p></td>
                <td><p>' . number_format(round($staticSensor->min_mesurm_lim_t, 4), 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>ВПИ температуры , гр .Целс</p></td>
                <td><p>' . number_format(round($staticSensor->max_mesurm_lim_t, 4), 4, '.', '') . '</p></td>
            </tr>
            <tr>
                <td><p>НПИ перепада , кгс/см2:</p></td>
                <td><p>' . number_format($handArr['perepNPI'], 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>ВПИ перепада , кгс/см2:</p></td>
                <td><p>' . number_format($handArr['perepVPI'], 4, '.', '') . '</p></td>
            </tr>
            <tr>
                <td><p>Межконтр. интервал, год :</p></td>
                <td><p>' . number_format(round($staticHard->control_interval, 0), 4, '.', '') . '</p></td>
                <td width="50px"></td>
                <td><p>R  закругл. кромки, мм:</p></td>
                <td><p>' . number_format(round($staticHard->radius_diafr, 2), 4, '.', '') . '</p></td>
            </tr>

            <tr>
                <td colspan="2"><p>При dР < dPmin принимается dP=dPmin</p></td>
                <td width="50px"></td>
                <td colspan="2"><p>При P < Pmin принимается P=Pmin</p></td>

            </tr>



        </table>';

            $string = $stringP;
        }

        return $string;
    }

    public function TimeData()
    {


        $dateArr = explode(" ", $this->date);
        $dateDetail = explode("-", $dateArr[0]);

        $fTRG = new FlouTechReportGenerator();
        $fTRG->counter_id = $this->id;
        $fTRG->contract_hour=$this->contractHour;

        switch ($this->type) {
            case "month":
                $hourData = $fTRG->dayHourReportGenerate($dateDetail[0], $dateDetail[1], "1");
                $dayData = $fTRG->monthReportGenerate($dateDetail[0],$dateDetail[1]);
                break;
            default:
                $hourData = $fTRG->dayHourReportGenerate($dateDetail[0], $dateDetail[1], $dateDetail[2]);
                $dayData = $fTRG->dayDayReportGenerate($dateDetail[0], $dateDetail[1], $dateDetail[2]);
        }

        return $this->renderTimeData($hourData, $dayData);
    }

    private function renderHourCountData($hourData, $dayData)
    {
        $string = '    <div style="width: 100%;text-align: center; margin-top:20px">
                        <p>ЧАСОВЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
                        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                            <thead>
                             <tr><th>Дата</th><th colspan="2">Время</th><th colspan="2"> Объем,м3</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
                             <tr><th></th><th>Начало</th><th>Конец</th><th> р.у</th><th> с.у</th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
                            </thead>
                            <tbody>';
        $dd = 0;
        foreach ($hourData as $hour) {
            $dd += $hour->v_sc;
            $string .= '
                            <tr><td>' . sprintf("%'02d", $hour->day) . '.' . sprintf("%'02d", $hour->month) . '.' . $hour->year . '</td><td>' . sprintf("%'02d", $hour->hour_n) . ':' . sprintf("%'02d", $hour->minutes_n) . '</td><td>' . sprintf("%'02d", $hour->hour_k) . ':' . sprintf("%'02d", $hour->minutes_k) . '</td><td>' . number_format(round($hour->v_rc, 2), 2, '.', '') . '</td><td>' . number_format(round($hour->v_sc, 2), 2, '.', '')  . '</td><td>' . number_format(round($hour->paverage, 4), 4, '.', '') . '</td><td>' . number_format(round($hour->taverage, 2), 2, '.', '') . '</td><td>' . $hour->emergency . '</td></tr> ';
        }
        $string .= '</tbody>
                        </table>
                    </div>
                    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 210px">
                        <span>
                            <table>
                                <tr>
                                    <td width="80px"> <b> Итого:</b> </td> <td> <b> ' . number_format(round($dayData->v_rc, 2), 2, '.', ''). '</b></td><td><b>' .  number_format(round($dayData->v_sc, 2), 2, '.', ''). '</b></td>
                                </tr>

                            </table>
                        </span><br>
                    </div>';


        return $string;
    }

    private function renderHourPressureData($hourData, $dayData)
    {

        $string = ' <div style="width: 100%;text-align: center; margin-top:20px">
                    <p>ЧАСОВЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
                    <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
                        <thead>
                        <tr><th>Дата</th><th colspan="2">Время</th><th>Полный V</th><th>Ср. перепад</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
                        <tr><th></th><th>Начало</th><th>Конец</th><th> c.у. ,м3</th><th>кгс/см2 </th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
                        </thead>
                        <tbody>';
        $dd = 0;
        foreach ($hourData as $hour) {
            $dd += $hour->v_sc;

            $string .= '  <tr><td>' . sprintf("%'02d", $hour->day) . '.' . sprintf("%'02d", $hour->month) . '.' . $hour->year . '</td><td>' . sprintf("%'02d", $hour->hour_n) . ':' . sprintf("%'02d", $hour->minutes_n) . '</td><td>' . sprintf("%'02d", $hour->hour_k) . ':' . sprintf("%'02d", $hour->minutes_k) . '</td><td>' . number_format(round($hour->v_sc, 2), 2, '.', '') . '</td><td>' . number_format(round($hour->pdelta, 2), 2, '.', '') . '</td><td>' . number_format(round($hour->paverage, 4), 4, '.', '') . '</td><td>' . number_format(round($hour->taverage, 2), 2, '.', '') . '</td><td>' . $hour->emergency . '</td></tr>';
        }
        $string .= ' </tbody>
            </table>
            </div>
            <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 210px">
                    <span>
                        <table>
                            <tr>
                                <td width="80px"> <b> Итого:</b> </td> <td> <b>  ' . number_format(round($dayData->v_sc, 5), 2, '.', '') . '</b></td>
                            </tr>
            
                        </table>
                    </span><br>
            </div>';

        return $string;
    }

    private function renderHourData($hourData, $dayData)
    {
        switch ($this->dimensionType) {
            case "Pressure":
                $string = $this->renderHourPressureData($hourData, $dayData);
                break;
            default:
                $string = $this->renderHourCountData($hourData, $dayData);
        }


        return $string;
    }

    private function renderMonthData($hourData, $dayData)
    {
        switch ($this->dimensionType) {
            case "Pressure":
                $string = $this->renderMonthPressureData($hourData, $dayData);
                break;
            default:
                $string = $this->renderMonthCountData($hourData, $dayData);
        }

        return $string;
    }

    private function renderTimeData($hourData, $dayData)
    {
        switch ($this->type) {
            case "month":
                $string = $this->renderMonthData($hourData, $dayData);
                break;
            default:
                $string = $this->renderHourData($hourData, $dayData);
        }

        return $string;
    }

    private function renderMonthPressureData($hourData, $dayData)
    {

        $string = '<div style="width: 100%;text-align: center; margin-top:20px">
        <p>СУТОЧНЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ</p>
        <table style="width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none ">
            <thead>
            <tr><th>Дата</th><th>Полный V</th><th>Ср. перепад</th><th>Ср.давл.</th><th>Ср.темп.</th><th>АВ</th></tr>
            <tr><th></th><th> c.у. ,м3</th><th>кгс/см2 </th><th>кгс/см2</th><th>гр.Целс</th><th></th></tr>
            </thead>
            <tbody>';

            $Vsc=0;
            $avVsu=0;

            foreach($dayData as $hour){

                $Vsc+=$hour->v_sc;
                $avVsu+=$hour->vav_sc;



                $string .= '<tr><td>'.sprintf("%'02d",$hour->day).'.'.sprintf("%'02d",$hour->month).'.'.$hour->year.'</td><td>'.number_format(round($hour->v_sc,2),2,'.','').'</td><td>'.number_format(round($hour->pdelta,2),2,'.','').'</td><td>'.number_format(round($hour->paverage,4),4,'.','').'</td><td>'.number_format(round($hour->taverage,2),2,'.','').'</td><td>'.$hour->emergency.'</td></tr>';
            }
        $string .= '</tbody>
                    </table>
                    </div>
                    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 20px">
                            <span>
                                <table >
                                    <tr>
                                        <td width="92px"> <b> Итого:</b> </td> <td> <b> '.number_format($Vsc+$avVsu,2,'.','').'</b></td>
                                    </tr>

                                </table>
                            </span><br>
                    </div>';
        return $string;


    }

    private function renderMonthCountData($hourData, $dayData)
    {

        $string = '<div style = "width: 100%;text-align: center; margin-top:20px" >
        <p > СУТОЧНЫЕ ИЗМЕРИТЕЛЬНЫЕ ДАННЫЕ </p >
        <table style = "width: 100%; border-bottom-color: #0a0a0a;border: 1; border-bottom-style: dashed;border-top-style: dashed;border-left: none;border-right: none " >
            <thead >
            <tr ><th > Дата</th ><th colspan = "2" > Объем,м3 </th ><th > Ср . давл .</th ><th > Ср . темп .</th ><th > АВ</th ></tr >
            <tr ><th ></th ><th > р . у</th ><th > с . у</th ><th > кгс / см2</th ><th > гр . Целс</th ><th ></th ></tr >
            </thead >
            <tbody >
            ';
        $V_rc = 0;
        $V_sc = 0;
        $ta = 0;
        $avVsu = 0;
        $avV = 0;
        foreach ($dayData as $hour) {

            $V_rc += $hour->v_rc;
            $V_sc += $hour->v_sc;
            $ta += $hour->time_emerg;
            $avV += $hour->vav_rc;
            $avVsu += $hour->vav_sc;

            $string .= '
                <tr>
                    <td>' . sprintf("%'02d", $hour->day) . '.' . sprintf("%'02d", $hour->month) . '.' . $hour->year . '</td>
                    <td>' . number_format(round($hour->v_rc, 2), 2, '.', '') . '</td>
                    <td>' . number_format(round($hour->v_sc, 2), 2, '.', '') . '</td>
                    <td>' . number_format(round($hour->paverage, 4), 4, '.', '') . '</td>
                    <td>' . number_format(round($hour->taverage, 2), 2, '.', '') . '</td>
                    <td>' . $hour->emergency . '</td>
                </tr>
            ';
        }
        $string .= '</tbody>
                    </table>
                    </div>
                    <div style="width: 100%;text-align: left; margin-top:0px;margin-left: 90px">
                            <span>
                                <table>
                                    <tr>
                                        <td width="57px"><b>Итого:</b></td>
                                        <td width="118px"><b>' . number_format($V_rc+$avV, 2, '.', '') . '</b></td>
                                        <td><b>' . number_format($V_sc+$avVsu, 2, '.', '') . '</b></td>
                                    </tr>

                                </table>
                            </span><br>
                    </div>';

        return $string;

    }



    public function SummaryData()
    {

        $dateArr = explode(" ", $this->date);
        $dateDetail = explode("-", $dateArr[0]);

        $fTRG = new FlouTechReportGenerator();
        $fTRG->counter_id = $this->id;
        $fTRG->contract_hour=$this->contractHour;


        switch ($this->type) {
            case "month":
                 $dayData = $fTRG->monthReportGenerate($dateDetail[0],$dateDetail[1]);

                 $dayData=$this->monthSummary($dayData);

                break;
            default:
                 $dayData = $fTRG->dayDayReportGenerate($dateDetail[0], $dateDetail[1], $dateDetail[2]);
        }


        return $this->renderSummaryData($dayData);
    }

    private function renderSummaryData($dayData)
    {

        switch ($this->type) {
            case "month":
                $string = $this->renderMonthSummaryData($dayData);
                break;
            default:
                $string = $this->renderHourSummaryData($dayData);
        }

        return $string;


    }

    private function renderHourSummaryData($dayData)
    {

        switch ($this->dimensionType) {
            case "Pressure":
                $string = $this->renderHourPressureSummaryData($dayData);
                break;
            default:
                $string = $this->renderHourCountSummaryData($dayData);
        }

        return $string;

    }

    private function renderHourCountSummaryData($dayData){

        switch ($this->progName) {
            case "ConCore":
                $string = $this->renderHourCountSummaryConCoreData($dayData);
                break;
            default:
                $string = $this->renderHourCountSummaryMConCoreData($dayData);
        }

        return $string;

    }

    private function renderHourPressureSummaryData($dayData)
    {
        $string = '  <div style="width: 100%;text-align: left; margin-top:20px">
            <span>Безаварийный объем за сутки при c.у.,м3:'.sprintf("%'.85s",number_format(round($dayData->v_sc, 2),1,'.','')).'</span><br>
            <span>Аварийный объем за сутки при c.у.:'.sprintf("%'.108s",number_format(round($dayData->vav_sc, 1),1,'.','')).'</span><br>
            <span>Полный объем за сутки при c.у.:'.sprintf("%'.90s",number_format(round($dayData->v_sc, 1),1,'.','')).'</span><br>
            <span>Длительность АС dPотс < dP < dPmin или P < P min  за сутки , ч:мин:с:'.sprintf("%'.62s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
            <span>Общая длительность аварийніх ситуация за сутки , ч:мин:с:'.sprintf("%'.50s%'02d:%'02d:%'02d","",$dayData->time_emerg/3600,$dayData->time_emerg%3600/60,$dayData->time_emerg%3600%60).'</span><br>
            </div>';
        return $string;
        
    }


    private function renderHourCountSummaryConCoreData($dayData)
    {

        $string = '<div style="width: 100%;text-align: left; margin-top:20px">
                    <span>Безаварийный объем за сутки при р.у.,м3:'.sprintf("%'.95s",number_format(round($dayData->v_rc, 2),1,'.','')).'</span><br>
                    <span>Аварийный объем за сутки при р.у.:'.sprintf("%'.108s",number_format(round($dayData->vav_rc, 1),1,'.','')).'</span><br>
                    <span>Полный объем за сутки при р.у.:'.sprintf("%'.111s",number_format(round($dayData->v_rc, 2),1,'.','')).'</span><br>
                    <span>Безаварийный объем за сутки при c.у.,м3:'.sprintf("%'.93s",number_format(round($dayData->v_sc, 2),1,'.','')).'</span><br>
                    <span>Аварийный объем за сутки при c.у.:'.sprintf("%'.108s",number_format($dayData->vav_sc,1,'.','')).'</span><br>
                    <span>Полный объем за сутки при c.у.:'.sprintf("%'.110s",number_format(round($dayData->v_sc, 1),1,'.','')).'</span><br>
                    <span>Длительность АС Qстоп < Qv < Qmin  за сутки , ч:мин:с:'.sprintf("%'.62s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:'.sprintf("%'.52s",number_format(round($dayData->vpokaz_rc, 2),2,'.','')).'</span><br>
                    <span>Общая длительность аварийніх ситуация за сутки , ч:мин:с:'.sprintf("%'.55s%'02d:%'02d:%'02d","",$dayData->time_emerg/3600,$dayData->time_emerg%3600/60,$dayData->time_emerg%3600%60).'</span><br>
                </div>';
        return $string;


    }

    private function renderHourCountSummaryMConCoreData($dayData)
    {

        $string = '<div style="width: 100%;text-align: left; margin-top:20px">

                        <span>Аварийный объем за сутки при р.у.:'.sprintf("%'.108s",number_format(round($dayData->vav_rc, 1),1,'.','')).'</span><br>
                        <span>Аварийный объем за сутки при c.у.:'.sprintf("%'.108s",number_format($dayData->vav_sc,1,'.','')).'</span><br>
                        <span>Полный объем за сутки при c.у.:'.sprintf("%'.110s",number_format(round($dayData->v_sc, 1),1,'.','')).'</span><br>
                        <span>Длительность измирительных авар.ситуаций за сутки , ч:мин:с:'.sprintf("%s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Длительность методических авар.ситуаций за сутки , ч:мин:с:'.sprintf("%s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Длительность отключения питания за сутки , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Длит.постановки на несанкц. константы  за сутки , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Длит.работы когда расход был был < НПИ  за сутки , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:'.sprintf("%'.52s",number_format(round($dayData->vpokaz_rc, 2),2,'.','')).'</span><br>
            
                    </div>';
        return $string;


    }

    private function renderMonthSummaryData($dayData)
    {
        switch ($this->dimensionType) {
            case "Pressure":
                $string = $this->renderMonthPressureSummaryData($dayData);
                break;
            default:
                $string = $this->renderMonthCountSummaryData($dayData);
        }


        return $string;
    }

    private function renderMonthCountSummaryData($dayData)
    {

        switch ($this->progName) {
            case "ConCore":
                $string = $this->renderMonthCountSummaryConCoreData($dayData);
                break;
            default:
                $string = $this->renderMonthCountSummaryMConCoreData($dayData);
        }

        return $string;
    }

    private function renderMonthPressureSummaryData($dayData)
    {
        $string =' <div style="width: 100%;text-align: left; margin-top:20px">
                        <span>Безаварийный объем за месяц при c.у.,м3:'.sprintf("%''.85s",number_format(round($dayData->v_sc, 1),1,''.'','')).'</span><br>
                        <span>Аварийный объем за месяц  при c.у.:'.sprintf("%''.108s",number_format(number_format($dayData->vav_sc,1,'.',''),1,''.'','')).'</span><br>
                        <span>Полный объем за месяц при c.у.:'.sprintf("%''.90s",number_format($dayData->v_sc+$dayData->vav_sc,1,''.'','')).'</span><br>
                        <span>Длительность АС dPотс < dP < dPmin или P < P min  за месяц , ч:мин:с:'.sprintf("%''.62s%''02d:%''02d:%''02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Общая длительность аварийных ситуация за месяц , ч:мин:с:'.sprintf("%''.50s%''02d:%''02d:%''02d","",$dayData->time_emerg/3600,$dayData->time_emerg%3600/60,$dayData->time_emerg%3600%60).'</span><br>
                    </div>';

        return $string;
    }

    private function renderMonthCountSummaryConCoreData($dayData)
    {

        $string ='<div style="width: 100%;text-align: left; margin-top:20px">
                        <span>Безаварийный объем за месяц при р.у.,м3:'.sprintf("%'.95s",number_format($dayData->v_rc,1,'.','')).'</span><br>
                        <span>Аварийный объем за месяц при р.у.:'.sprintf("%'.108s",number_format($dayData->vav_rc,1,'.','')).'</span><br>
                        <span>Полный объем за месяц при р.у.:'.sprintf("%''.90s",number_format($dayData->v_rc+$dayData->vav_rc,1,''.'','')).'</span><br>
                        <span>Безаварийный объем за месяц при c.у.,м3:'.sprintf("%'.93s",number_format($dayData->v_sc,1,'.','')).'</span><br>
                        <span>Аварийный объем за месяци при c.у.:'.sprintf("%'.108s",number_format($dayData->vav_sc,1,'.','')).'</span><br>
                        <span>Полный объем за месяц при c.у.:'.sprintf("%''.90s",number_format($dayData->v_sc+$dayData->vav_sc,1,''.'','')).'</span><br>
                        <span>Длительность АС Qстоп < Qv < Qmin  за сутки , ч:мин:с:'.sprintf("%'.62s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                        <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:'.sprintf("%'.52s",number_format($dayData->vpokaz_rc,2,'.','')).'</span><br>
                        <span>Общая длительность аварийных ситуация за месяц , ч:мин:с:'.sprintf("%'.55s%'02d:%'02d:%'02d","",$dayData->time_emerg/3600,$dayData->time_emerg%3600/60,$dayData->time_emerg%3600%60).'</span><br>
                    </div>';
        
        return $string;


    }

    private function renderMonthCountSummaryMConCoreData($dayData)
    {


        $string ='<div style="width: 100%;text-align: left; margin-top:20px">
                    <span>Аварийный объем за месяц при р.у.:'.sprintf("%'.108s",number_format($dayData->vav_rc,1,'.','')).'</span><br>
                    <span>Аварийный объем за месяц при c.у.:'.sprintf("%'.108s",number_format($dayData->vav_sc,1,'.','')).'</span><br>
                    <span>Полный объем за сутки при c.у.:'.sprintf("%'.110s",number_format($dayData->v_sc+$dayData->vav_sc,1,'.','')).'</span><br>
                    <span>Длительность измирительных авар.ситуаций за месяц , ч:мин:с:'.sprintf("%s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Длительность методических авар.ситуаций за месяц , ч:мин:с:'.sprintf("%s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Длительность отключения питания за месяц , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Длит.постановки на несанкц. константы  за месяц , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Длит.работы когда расход был был < НПИ  за месяц , ч:мин:с:'.sprintf("%'.10s%'02d:%'02d:%'02d","",$dayData->time_emerg2/3600,$dayData->time_emerg2%3600/60,$dayData->time_emerg2%3600%60).'</span><br>
                    <span>Показания счетчика газа наконец отчетного периода (р.у.) , м3:'.sprintf("%'.52s",number_format($dayData->vpokaz_rc,2,'.','')).'</span><br>
                    </div>';

        return $string;
        


    }

    private function monthSummary($dayData)
    {
        $dayDataSum= new DayData();
        foreach($dayData as $oneDayData){

            foreach($oneDayData as $key=>$value){
                $dayDataSum->$key+=$value;
            }
        }

        return $dayDataSum;
    }

    static  function getIntervLabel($id){


        $interv=\app\models\InterventionLabel::findOne(['intervention_id'=>$id]);

        if($interv)
        { return $interv->name;}
        else{
            return Html::tag('span',"В обработке",['inlabel'=>$id]);
        }

    }

    static function getEmergLabel($id){


        $interv=\app\models\EmergencyLabel::findOne(['emergency_id'=>$id]);

        if($interv)
        { return $interv->name;}
        else{
            return Html::tag('span',"В обработке",['emlabel'=>$id]);
        }

    }

    static  function getEmergSignLabel($id){


        $interv=\app\models\EmergencySignLabel::findOne(['emergency_id'=>$id]);

        if($interv)
        { return $interv->name;}
        else{
            return Html::tag('span',"В обработке",['emlabel'=>$id]);
        }

    }

    static  function  getDiagnostLabel($id){


        $interv=\app\models\DiagnosticLabel::findOne(['diagnostic_id'=>$id]);

        if($interv)
        { return $interv->name;}
        else{
            return Html::tag('span',"В обработке",['emlabel'=>$id]);
        }

    }


    public function Emergency(){



        $dateArr = explode(" ", $this->date);
        $dateDetail = explode("-", $dateArr[0]);

        $fTRG = new FlouTechReportGenerator();
        $fTRG->counter_id = $this->id;
        $fTRG->contract_hour=$this->contractHour;

        $dt= new \DateTime($this->date);
        $dt->add(new \DateInterval('PT'.$this->contractHour.'H'));
        $dt->sub(new \DateInterval('P1D'));
        $dn= clone $dt;

        switch ($this->type) {
            case "month":
                $dn->add(new \DateInterval('P1M'));
                 $dayData = $fTRG->monthReportGenerate($dateDetail[0],$dateDetail[1]);
                $dayData=$this->monthSummary($dayData);
                break;
            default: $dn->add(new \DateInterval('P1D'));
                $dayData = $fTRG->dayDayReportGenerate($dateDetail[0], $dateDetail[1], $dateDetail[2]);

        }


        $emSit=EmergencySituation::find()
            ->where(['all_id'=>$this->id])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_ASC])->all();



        $emArr=[];

        foreach($emSit as $oneEmSit){

            if(!array_key_exists($oneEmSit->params,$emArr)){
                $emArr[$oneEmSit->params]=[
                    'params'=>$oneEmSit->params,
                    'timestamp'=>$oneEmSit->timestamp,
                    'duration'=>$oneEmSit->time,
                    'vsc'=>$oneEmSit->vsc,
                    'vrc'=>$oneEmSit->vrc,
                    'countP'=>$oneEmSit->count_p,
                    'var1'=>$oneEmSit->var1,
                ];
            }else{

                $emArr[$oneEmSit->params]['duration']+=$oneEmSit->time;
                $emArr[$oneEmSit->params]['vsc']+=$oneEmSit->vsc;
                $emArr[$oneEmSit->params]['vrc']+=$oneEmSit->vrc;
                $emArr[$oneEmSit->params]['countP']+=$oneEmSit->count_p;
                $emArr[$oneEmSit->params]['var1']+=$oneEmSit->var1;


            }

        }



        return Yii::$app->controller->renderPartial("@app/components/Prom/views/emergency",['emsit'=>$emSit,'emsign'=>$emArr,'dimtype'=>$this->dimensionType,'dayData'=>$dayData]);
    }


    public function Intervention(){

        $dt= new \DateTime($this->date);
        $dt->add(new \DateInterval('PT'.$this->contractHour.'H'));
        $dt->sub(new \DateInterval('P1D'));
        $dn= clone $dt;

        switch ($this->type) {
            case "month":
                $dn->add(new \DateInterval('P1M'));
                break;
            default: $dn->add(new \DateInterval('P1D'));
        }
        $dn->sub(new \DateInterval('PT1S'));
        $intervention=Intervention::find()
            ->where(['all_id'=>$this->id])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_ASC])->all();

        return Yii::$app->controller->renderPartial("@app/components/Prom/views/intervention",['intervention'=>$intervention]);

    }


    public function Diagnoistic(){

        $dt= new \DateTime($this->date);
        $dt->add(new \DateInterval('PT'.$this->contractHour.'H'));
        $dt->sub(new \DateInterval('P1D'));
        $dn= clone $dt;

        switch ($this->type) {
            case "month":
                $dn->add(new \DateInterval('P1M'));
                break;
            default: $dn->add(new \DateInterval('P1D'));
        }

        $diag=Diagnostic::find()
            ->where(['all_id'=>$this->id])
            ->andWhere('timestamp > :timestamp1',[':timestamp1'=>$dt->format("Y-m-d H:i:s")])
            ->andWhere('timestamp < :timestamp2',[':timestamp2'=>$dn->format("Y-m-d H:i:s")])
            ->orderBy(['id'=>SORT_ASC])->all();


        return Yii::$app->controller->renderPartial("@app/components/Prom/views/diagnostic",['diag'=>$diag]);

    }


public function EndReport(){

        return Yii::$app->controller->renderPartial("@app/components/Prom/views/end");

    }



}