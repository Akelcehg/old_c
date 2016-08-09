<?php
/**
 * User: Igor S <igor.s@scopicosftware.com>
 * Date: 12/4/14
 * Time: 10:48 PM
 */

namespace app\modules\api\v1\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;
use yii\rest\Action;


class SearchAction extends Action
{

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($action) {
     *     // $action is the action object currently running
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]].
     */
    public $prepareDataProvider;

    /**
     * Except filter attributes
     * @var array
     */
    public $exceptAttributes = [];
    /**
     * You can set sort object as a Sort object from action
     * look at http://www.yiiframework.com/doc-2.0/yii-data-sort.html
     * @var Sort
     */
    public $sort = null;
    /**
     * Filter params - by default use a get params
     * @var array
     */
    public $params = [];

    /**
     * Scope which will add to query
     * @var string
     */
    public $scope = '';

    /**
     * @return ActiveDataProvider
     */
    public function run() {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $this->params = empty($this->params) ? \Yii::$app->request->get() : $this->params;
        $this->sort = $this->getSort();
        return $this->prepareDataProvider();
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider() {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /**
         * @var \yii\db\BaseActiveRecord $modelClass
         */
        $modelClass = $this->modelClass;
        $model = Yii::createObject($modelClass);
        $availableModelAttributes = array_keys($model->getAttributes(null, $this->exceptAttributes));
        $searchAttributes = array_intersect($availableModelAttributes, array_keys($this->params));

        foreach($searchAttributes as $searchAttribute){
            $model->$searchAttribute = $this->params[$searchAttribute];
        }

        $query = $modelClass::find();
        if(!empty($this->scope)) {
            $scope = $this->scope;
            $query = $query->$scope();
        }

        $pageSize = Yii::$app->request->getQueryParam('pageSize', 25);

        if($pageSize) {
            $pagination = [
                'pageSize' => $pageSize,
            ];
        }else {
            $pagination = false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => $pagination,
        ]);

//        $with = \Yii::$app->request->getQueryParam('with');
//        $query->with('type');
        foreach ($model->getAttributes() as $param => $value) {
	        //Like condition
            if(substr($value, 0, 1) == "~" || substr($value, -1) == "~") {
                if(substr($value, 0, 1) == "~")
                    $value = "%".substr($value, 1);
                if(substr($value, -1) == "~")
                    $value = substr($value, 0, -1)."%";

                $query->andFilterWhere(['LIKE', $param, $value, false]);
	        //More or Less conditions
            }else if(in_array(substr($value, 0, 1), ['>', '<'])) {
                $query->andFilterWhere([substr($value, 0, 1), $param, substr($value, 1)]);
	        //In Condition separator ","
            }else if(strpos($value, ",")) {
                $query->andFilterWhere(['in', $param, explode(",", $value)]);
            }else {
                $query->andFilterWhere([$param => $value]);
            }
        }

        if(!empty($this->sort->attributes))
            $query->orderBy($this->sort->orders);

        $groupBy = Yii::$app->request->getQueryParam('groupBy');
        if($groupBy) {
            $query->groupBy(explode(",", $groupBy));
        }


        return $dataProvider;
    }

    /**
     * Get sort object
     * @author Igor S <igor.s@scopicsoftware.com>
     *
     * @return Sort
     */
    public function getSort(){
        if(!is_null($this->sort))
            return $this->sort;
        else
            return $this->getDefaultSort();
    }

    /**
     * Get default sort object based on model attributes
     * @author Igor S <igor.s@scopicsoftware.com>
     *
     * @return Sort
     *
     */
    protected function getDefaultSort(){
        $modelClass = $this->modelClass;
        $model = Yii::createObject($modelClass);

        $sort = $this->sort;

        if(is_null($sort))
        {
            $sort = new Sort([
                'attributes' => array_keys($model->getAttributes()),
            ]);
        }

        return $sort;
    }

}