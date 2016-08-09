<?php

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 9:58
 */
namespace app\modules\admin\components;

use yii\base\Component;
use app\models\Address;
use app\models\AddressSearch;
use Yii;
use app\components\RightsComponent;
use app\models\CounterSearch;
use yii\data\ActiveDataProvider;
use app\modules\admin\components\AdminComponent;
use yii\web\HttpException;


class Correctors extends AdminComponent
{
    public $pagination=['pageSize' => 15,];


    public function Demo(){

        $ind=Indication::find()->where(['counter_id'=>'454'])->limit(90)->orderBy(['created_at'=>SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $ind,
            'pagination'=>$this->pagination,
        ]);

        $this->dataProvider=$dataProvider;


    }



}