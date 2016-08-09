<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 12.05.16
 * Time: 12:52
 */

namespace app\modules\prom\components;


use app\models\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Tabs;


class NavTabsWidget extends Widget
{

    public function run()
    {
        $type = Yii::$app->request->get('type', false);
        $user_type = Yii::$app->request->get('user_type', $type);


        $items = [


            [
                'label' => "КомБыт",
                'url' => Yii::$app->urlManager->createUrl(['/prom/correctors/tabs', 'user_type' => 'legal_entity']),
                'user_type' => 'legal_entity'
            ],
            [
                'label' => "Общедомовые узлы учета",
                'url' => Yii::$app->urlManager->createUrl(['/prom/correctors/tabs', 'user_type' => 'house_metering']),
                'user_type' => 'house_metering'
            ],
            [
                'label' => "Население",
                'url' => Yii::$app->urlManager->createUrl(['/prom/correctors/tabs', 'user_type' => 'individual']),
                'user_type' => 'individual'
            ],
            /*[
                'label' => "Метриксы",
                'url' => Yii::$app->urlManager->createUrl(['/metrix/counters', 'user_type' => 'metrix']),
                'user_type' => 'metrix'
            ]*/
        ];

        $grs = [
            'label' => "ГРС",
            'url' => Yii::$app->urlManager->createUrl(['/prom/correctors', 'type' => 'grs']),
            'user_type' => 'grs'
        ];

        $summary = [
            'label' => "Сводка",
            'url' => Yii::$app->urlManager->createUrl(['/prom/summary', 'user_type' => 'summary']),
            'user_type' => 'summary'
        ];

        $summaryRep= [
            'label' => "Отчеты",
            'url' => Yii::$app->urlManager->createUrl(['/prom/summary/summaryindex', 'user_type' => 'summaryreport']),
            'user_type' => 'summaryreport'
        ];


        $prom = [
            'label' => "Промышленность",
            'url' => Yii::$app->urlManager->createUrl(['/prom/correctors', 'type' => 'prom']),
            'user_type' => 'prom'
        ];

        $itemAdd = [];

            if(User::is('GRS')){
                $itemAdd = array_merge([$grs],$itemAdd);
                $itemAdd = array_merge([$summary],$itemAdd);
            }

        if(User::is('PromAdmin')){
            $itemAdd = array_merge($itemAdd,[$prom]);

        }

           if(User::is('summary')){
               $items = array_merge($items,[$summaryRep]);

           }

        $items = array_merge($itemAdd, $items);

        $output=[];

        foreach ($items as $item) {

            if ($item['user_type'] == $user_type) {
                $output[] = [
                    'label' => $item['label'],
                    'url' => $item['url'],
                    'active' => true
                ];

            } else {
                $output[] = [
                    'label' => $item['label'],
                    'url' => $item['url'],
                ];
            }

        }

        ?>
        <div class="row">
            <?= Tabs::widget(['items' => $output]); ?>
        </div>
        <?php
    }

}