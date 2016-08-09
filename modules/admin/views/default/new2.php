<?php
use yii\bootstrap\Tabs;
use \app\components\AlertsView;
?>
<div id="content">
    <div class="row" style="text-align: center">
        <h1 id="correctorAddress"><?=$counter->address->fulladdress?></h1>
    </div>
    <div class="row">



        <div>
            <div class="col-md-6">
                <?php
                echo \app\components\ChartMultiN::widget([
                    'name' => 'dayChart',
                    'height' => '100px',
                    'width' => '100%',
                    'type1' => 'dayChart',
                  //  'global' => $globalChartSettings,
                    'chartsConfig' => [
                        'bezierCurve' => 'false',
                        'scaleBeginAtZero'=>'false',
                        'animation' => 'false',
                        'showTooltips'=>'false',
                        'pointDot' => "false",

                    ],
                ]);

                ?>
                <?php

                $this->registerJs('

         $.get(app.baseUrl + \'/admin/chart/ajaxcountertochart\', {
            counter_id: '.$counter->id.',
            beginDate: "2016-02-01",
            endDate: "2016-02-29",
            type: "gas"
        }, function (json) {

            while (chartdayChart.scale.xLabels.length > 0)
            {
                chartdayChart.removeData();
                $(\'#labeldayChart\').html(\'\');
            }

            if (json.length < 2)
            {
                $(\'#no-data\').show();
            }
            else
            {
                $(\'#no-data\').hide();
            }

            for (var i = 0; json.length > i; i++)
            {
                label=json[i].label.split("-");

                chartdayChart.addData(json[i].data, label[2]);

            }
            $(\'#counterAddress\').html("-"+json[1].address);
            delete json;
        })

                ', $position = 3);

                $this->registerJs('

         $.get(app.baseUrl + \'/admin/chart/ajaxcountertotempchart\', {
            counter_id: '.$counter->id.',
            beginDate: "2016-02-01",
            endDate: "2016-02-29",
            type: "gas"
        }, function (json) {

            while (chartdayCharttemp.scale.xLabels.length > 0)
            {
                chartdayCharttemp.removeData();
                $(\'#labeldayChart\').html(\'\');
            }

            if (json.length < 2)
            {
                $(\'#no-data\').show();
            }
            else
            {
                $(\'#no-data\').hide();
            }

            for (var i = 0; json.length > i; i++)
            {
                label=json[i].label.split("-");

                chartdayCharttemp.addData(json[i].data, label[2]);

            }
            $(\'#counterAddress\').html("-"+json[1].address);
            delete json;
        })

                ', $position = 3);
                ?>
            </div>
            <div class="col-md-6" >

                <table  class='table-striped table-hover table-bordered' id='emergDataTable'>
                    <tr>
                        <td> Лицевой счет:</td>
                        <td><?=$counter->account?></td>
                    </tr>

                    <tr>
                        <td> № счетчика:</td>
                        <td><?=$counter->serial_number?></td>
                    </tr>

                    <tr>
                        <td> Расход за текуший месяц :</td>
                        <td><?=round($counter->flatindications, 3)?></td>
                    </tr>

                    <tr>
                        <td>Текущие показания счетчика:</td>
                        <td><?=$counter->getCurrentIndications()?></td>
                    </tr>

                    <tr>
                        <td>Среднесуточный расход:</td>
                        <td><?=round($counter->flatindications/29, 3)?></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    <div class="row">
    <div class="widget-body bordered">
    <?php

    echo Tabs::widget([

        'items' => [
            [
                'label' => 'Показания',
                'content' => 'Показания',
                'active' => true
            ],

            [
                'label' => 'Графики',
                'content' => $this->render('newChart'),
            ],

            [
                'label' => 'Отчеты',
                'content' => 'Отчеты',

            ],

             [
                'label' => 'Аварии',
                'content' => AlertsView::widget(['id'=>$counter->rmodule_id]),

            ],
             [
                'label' => 'Счетчик',
                'content' => 'Счетчик',

            ],
         [
                'label' => 'GSM модем',
                'content' => 'GSM модем',

            ],
        [
            'label' => 'Настройки',
            'content' => $this->render('options'),

        ],
    ],
]);
    ?>
        </div>
        </div>
</div>