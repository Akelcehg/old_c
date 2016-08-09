<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 21.07.16
 * Time: 14:05
 */

namespace app\components;


use app\models\MenuItem;
use app\models\User;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\bootstrap\Tabs;


class TabMenu extends Widget
{

    public $items=[];
    public $alias=null;


    protected function checkAccess($item)
    {

        foreach(User::getRoleArray() as $role) {
            if (in_array($role, $item->getMenuItemsAccess())) {
                return true;
            }
        }
        return false;
    }


    function run()
    {
        $menus=\app\models\Menu::find()->filterWhere(['alias'=>$this->alias])->one();
        if(!empty($menus)) {
            $this->items = MenuItem::getMenusData(0, $menus->id);
        }

        $items=[];
        foreach($this->items as $item){

            if($this->checkAccess($item)) {
                $items[] = [


                        'label' => $item->getLabel(),
                        'url' => Yii::$app->urlManager->createUrl([$item->url]),


                ];
            }

        }

        $output=[];
        if(!empty($items)) {

            foreach ($items as $item) {

                if ($item['url'] == \Yii::$app->request->url) {
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
        }
        $tabs=Tabs::widget(['items' => $output]);
       echo Html::tag('div',$tabs,['class'=>'row']);

    }


}