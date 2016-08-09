<?php

namespace app\modules\prom\components;
use app\models\EmergencySituation;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: alks
 * Date: 11.05.2016
 * Time: 19:05
 */
class EmergencySituationWidget extends Widget
{
    public $id;
    public $year=false;
    public $month=false;

    public function run()
    {
        if(!$this->year)$this->year=date('y');
        if(!$this->month)$this->month=date('n');

        $dt=new \DateTime($this->year.'-'.$this->month.'-01');

        $emsit = EmergencySituation::find()
            ->where(['all_id' => $this->id, 'month' => $dt->format('n'), 'year' =>$dt->format('y')])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $this->renderWidget($emsit);
    }

    public function renderWidget($emsit)
    {?>
        <div id='emergencyTable'>
        <?php
        if ($emsit) {
            ?>
            <table class='table-striped table-hover table-bordered emergDataTable' >
                <thead>
                <tr>
                    <th>Время первого появления</th>
                    <th>Аварийный признак</th>
                    <th>Длительность</th>
                    <th>Объем с.у м3</th>
                    <th>Колво проявлений</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $V_sca = 0;
                $V_pmdba = 0;
                $Time = 0;
                foreach ($emsit as $hour) {
                    $V_sca += $hour->vsc;
                    $V_pmdba += $hour->var1;
                    $Time += $hour->time;
                    ?>
                    <tr>
                        <td><?= sprintf("%'02d", $hour->day) . '.' . sprintf("%'02d", $hour->month) . '.' . $hour->year . " " . sprintf("%'02d", $hour->hour) . ':' . sprintf("%'02d", $hour->minutes) . ':' . sprintf("%'02d", $hour->seconds) ?></td>
                        <td><?= $this->getEmergLabel($hour->params) ?></td>
                        <td> <?= sprintf("%'02d:%'02d:%'02d", $hour->time / 3600, $hour->time % 3600 / 60, $hour->time % 3600 % 60) ?> </td>
                        <td><?= number_format(round($hour->vsc, 2), 2, '.', '') ?></td>
                        <td><?= number_format($hour->count_p, 0, '.', '') ?></td>
                    </tr>

                <?php } ?>
                <tr class="itog">
                    <th colspan="2">Итого:</th>
                    <th><?= sprintf("%'   30s%'02d:%'02d:%'02d", "    ", $Time / 3600, $Time % 3600 / 60, $Time % 3600 % 60) ?></th>
                    <th><?= sprintf("          %s", number_format($V_sca, 1, '.', '')) ?></th>
                    <th></th>
                </tr>
                </tbody>
            </table>

        <?php }else{ echo 'нет данных';};?>
        </div>

<?php
    }


    function getEmergLabel($id)
    {


        $interv = \app\models\EmergencyLabel::findOne(['emergency_id' => $id]);

        if ($interv) {
            return $interv->name;
        } else {
            return Html::tag('span', "В обработке", ['emlabel' => $id]);
        }

    }

}
