<?php  use yii\helpers\Html;
echo Html::hiddenInput('id',$summary['id']);
$dt= new \DateTime($summary->created_at)
?>
            <h2>Сводка потребления газа за <?=$dt->format("Y-m-d")?></h2>
            <table class="table table-bordered table-striped">
                <tr><th></th>
                    <th>Расход по типам потребителей</th>
                    <th>Откорректированное значение расхода</th>
                    <th>Общий Расход </th>
                    <th>Откорректированное значение Общего расхода</th>
                </tr>
                <tr>
                    <td>ГРС <br> <sub>вышло на связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline("grs") ?></sub></td>
                    <td></td>
                    <td></td>
                    <td>

                        <?php
                        echo isset($summary['grs'])? $summary['grs'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("grs"),2); ?>
                        м<sup>3</sup>
                    </td>
                    <td>

                            <?php

                            echo Html::input('text','grs',isset($summary['grs'])? $summary['grs'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("grs"),2),['id'=>'grs']); ?>
                    м<sup>3</sup>
                    </td>
                </tr>
                <tr>
                    <td>Промышленность <br> <sub>вышло на связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline() ?></sub></td>
                    <td><?php echo isset($summary['prom'])? $summary['prom'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"),2); ?> м<sup>3</sup></td>
                    <td><?php echo Html::input('text','prom',isset($summary['prom'])? $summary['prom'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"),2),['id'=>'prom']); ?> м<sup>3</sup></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Комбыт <br> <sub>вышло на связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("legal_entity") ?></sub></td>
                    <td><?php echo isset($summary['legal_entity'])? $summary['legal_entity'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"),2); ?> м<sup>3</sup></td>
                    <td><?php echo Html::input('text','legal_entity', isset($summary['legal_entity'])? $summary['legal_entity'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"),2),['id'=>'legal_entity']); ?> м<sup>3</sup></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Общедомовые узлы учета <br> <sub>вышло на связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("house_metering") ?></sub></td>
                    <td><?php echo isset($summary['house_metering'])? $summary['house_metering'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"),2); ?> м<sup>3</sup></td>
                    <td><?php echo Html::input('text','house_metering',isset($summary['house_metering'])? $summary['house_metering'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"),2),['id'=>'house_metering']); ?> м<sup>3</sup></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Население</td>
                    <td><?php echo isset($summary['individual'])? $summary['individual'] :round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption(),2); ?> м<sup>3</sup></td>
                    <td><?php echo Html::input('text','individual',isset($summary['individual'])? $summary['individual'] :round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption(),2),['id'=>'individual']); ?> м<sup>3</sup></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Общий расход</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <?php echo Html::input('text','all',isset($summary['all'])? $summary['all'] :'',['id'=>'all']) ?> м<sup>3</sup>
                    </td>
                </tr>
            </table>


