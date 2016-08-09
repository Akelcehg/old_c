<?php
/**
 * Custom Active Data Provider with SubQueries support
 *
 *  return new CustomActiveDataProvider([
 *    'query' => $query,
 *    'queryLoop' => $dashboard->getMainQueryLoop($fields, $search),
 *    'sort' => $dashboard->getMainListSort(),
 *    'pagination' => [
 *    'pageSize' => \Yii::$app->getRequest()->getQueryParam('pageSize', 50),
 *    ],
 *    ]);
 *

 * Date: 5/7/15
 * Time: 9:59 PM
 */

namespace app\modules\api\components;



class CustomActiveDataProvider extends \yii\data\ActiveDataProvider
{
    /**
     * Key value query loop - where key its a key with which result will returned and value its a closure which will execute
     * Closure will receive array from main query and you can use column values
     * @var array
     */
    public $queryLoop = [];

    protected function prepareModels()
    {
        $dataList = parent::prepareModels();

        foreach($dataList as &$data) {
            foreach($this->queryLoop as $columnName => $query) {
                $queryResult = null;
                if($query instanceof \Closure) {
                    $queryResult = $query($data);
//
//                    if($queryResult instanceof \yii\db\Query) {
//                        $queryResult = $queryResult->one();
//
//                        if($queryResult) {
//                            $queryResult = reset($queryResult);
//                        }

//                    }

                    $data[$columnName] = $queryResult;
                }
            }
        }
        return $dataList;
    }
}