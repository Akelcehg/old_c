<?php  use yii\helpers\Html;
$dt= new \DateTime($post->created_at)
?>
            <h2>Сводка потребления газа за <?=$dt->format("Y-m-d")?></h2>

            <table class="table table-bordered table-striped">
                <tr>
                    <td>ГРС <br><sub>вышло на связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline("grs") ?></sub></td>
                    <td></td>
                    <td>

                            <?php

                            echo isset($post['grs'])? $post['grs'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("grs"),2) ?>
                    м<sup>3</sup>
                    </td>
                </tr>
                <tr>
                    <td>Промышленность <br> <sub>вышло на связь <?php echo \app\modules\prom\components\CorrectorComponent::CorrectorsOnline() ?></sub></td>
                    <td><?php echo isset($post['prom'])? $post['prom'] :round(\app\modules\prom\components\CorrectorComponent::AllCorrectorsPrevDayConsumption("prom"),2) ; ?> м<sup>3</sup></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Комбыт <br> <sub>вышло на связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("legal_entity") ?></sub></td>
                    <td><?php echo isset($post['legal_entity'])? $post['legal_entity'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("legal_entity"),2) ; ?> м<sup>3</sup></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Общедомовые узлы учета <br> <sub>вышло на связь <?php echo \app\modules\counter\components\CounterComponent::CounterOnline("house_metering") ?></sub></td>
                    <td><?php echo isset($post['house_metering'])? $post['house_metering'] :round(\app\modules\counter\components\CounterComponent::AllCountersPrevDayConsumption("house_metering"),2) ; ?> м<sup>3</sup></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Население</td>
                    <td><?php echo isset($post['individual'])? $post['individual'] :round(\app\modules\counter\components\CounterComponent::AllIndividualPrevDayConsumption(),2) ; ?> м<sup>3</sup></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Общий расход</td>
                    <td></td>
                    <td>
                        <?php echo isset($post['all'])? $post['all']:0 ?> м<sup>3</sup>
                    </td>
                </tr>
            </table>


