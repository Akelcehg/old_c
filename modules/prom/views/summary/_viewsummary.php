<?php use yii\helpers\Html;


echo Html::hiddenInput('Prom', round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"), 2), ['id' => 'prom']);
echo Html::hiddenInput('legal_entity', round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"), 2), ['id' => 'legal_entity']);
echo Html::hiddenInput('house_metering', round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"), 2), ['id' => 'house_metering']);
echo Html::hiddenInput('individual', round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption(), 2), ['id' => 'individual']);

$dt= new \DateTime($summary->created_at);
?>

<h2>Сводка потребления газа за <?=$dt->format("Y-m-d")?></h2>
<table class="table table-bordered table-striped">
    <tr>
        <th></th>
        <th>Расход по типам потребителей</th>
        <th>Общий Расход</th>
    </tr>
    <tr>
        <td>ГРС <br> <sub>вышло на
                связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline("grs") ?></sub></td>
        <td></td>
        <td>

            <?php
            echo isset($summary['grs']) ? $summary['grs'] : round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("grs"), 2); ?>
            м<sup>3</sup>
        </td>
    </tr>
    <tr>
        <td>Промышленность <br> <sub>вышло на
                связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline() ?></sub></td>
        <td><?php echo isset($summary['prom']) ? $summary['prom'] : round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"), 2); ?>
            м<sup>3</sup></td>
        <td></td>

    </tr>
    <tr>
        <td>Комбыт <br> <sub>вышло на
                связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("legal_entity") ?></sub>
        </td>
        <td><?php echo isset($summary['legal_entity']) ? $summary['legal_entity'] : round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"), 2); ?>
            м<sup>3</sup></td>
        <td></td>

    </tr>
    <tr>
        <td>Общедомовые узлы учета <br> <sub>вышло на
                связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("house_metering") ?></sub>
        </td>
        <td><?php echo isset($summary['house_metering']) ? $summary['house_metering'] : round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"), 2); ?>
            м<sup>3</sup></td>
        <td></td>
    </tr>
    <tr>
        <td>Население</td>
        <td><?php echo isset($summary['individual']) ? $summary['individual'] : round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption(), 2); ?>
            м<sup>3</sup></td>
        <td></td>

    </tr>
    <tr>
        <td>Общий расход</td>
        <td></td>
        <td>
            <?php echo isset($summary['all']) ? $summary['all'] : '' ?> м<sup>3</sup>
        </td>
    </tr>
</table>


