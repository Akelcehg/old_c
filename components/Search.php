<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 05.04.16
 * Time: 17:41
 */

namespace app\components;




class Search
{

public static function Indexing($counter){

    $search=\app\models\Search::find()->where(['counter_id'=>$counter->id,'type'=>'counter'])->one();
    if(empty($search))
    {
        $search = new \app\models\Search();
        $search->type='counter';
    }

    $search->counter_id=$counter->id;
    $search->search_string=$counter->fulladdress.' '.$counter->account.' '.$counter->fullname;
    $search->save();

}

}