<?php
/**
 * User: Igor S <igor.skakovskiy@sferastudios.com>
 * Date: 2/20/15
 * Time: 1:32 AM
 */

namespace app\components;


use yii\base\Object;
use yii\db\Query;

class SearchQueryBuilder extends Object
{
    /**
     * Fields For additional select
     * @var array
     */
    public $selectFields = [];

    /**
     * SelectedFields Mapping
     * @var array
     */
    public $selectFieldsMapping = [];

    /**
     * Joined table aliases
     * @var array
     */
    public $joinedTablesAliases = [];

    /**
     * AND relation between fields filter keys and db keys
     * @var array
     */
    public $andFieldsMapping = [];

    /**
     * Relation between fields filter keys and filter functions
     * @var array
     */
    public $closureFilterMapping = [];

    /**
     * Default condition
     * @var string|array|Callable
     */
    public $defaultCondition;

    /**
     * Query instance which will returned
     * @\yii\db\Query
     */
    protected $_query;


    /**
     * @param \yii\db\Query $query
     * @param array $config - Yii configuration Object config
     */
    public function __construct(\yii\db\Query $query, $config = [])
    {
        $this->_query = $query;
        parent::__construct($config);
    }

    /**
     * Get Query Instance
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @return \yii\db\Query
     */
    public function getQueryInstance()
    {
        return $this->_query;
    }

    /**
     * Set Query instance
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param \yii\db\Query $query
     */
    public function setQuery(Query $query)
    {
        $this->_query = $query;
    }

    /**
     * Apply all search conditions
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @return \yii\db\Query
     */
    public function applySearchConditions(array $search = [])
    {
        $this->applyDefaultCondition($search);

        $availableFilterKeys = array_merge(array_keys($this->closureFilterMapping), array_keys($this->andFieldsMapping));
        $applyFilters = array_intersect($availableFilterKeys, array_keys($search));

        //clear empty values
        $applyFilters = array_filter($applyFilters, function($element) use ($search) {
            return $this->isNotEmpty($search[$element]);
        });

        //apply filters
        foreach($applyFilters as $applyFilter){
            if(isset($this->closureFilterMapping[$applyFilter])) {
                $element = $this->closureFilterMapping[$applyFilter];

                if ($element instanceof \Closure) {
                    $element($this, $search[$applyFilter], $search);
                } else {
                    call_user_func([$this, $element], $search[$applyFilter], $search);
                }

            }else if(isset($this->andFieldsMapping[$applyFilter])) {
                call_user_func_array([$this, 'andWhere'], [$this->andFieldsMapping[$applyFilter], $search[$applyFilter], $search]);
            }
        }

        //apply fields mapping
        $additionalSelectFields = array_intersect($this->selectFields, array_keys($this->selectFieldsMapping));
        foreach($additionalSelectFields as $additionalSelectField) {
            $fieldValue = $this->selectFieldsMapping[$additionalSelectField];
            if($fieldValue instanceof \Closure) {
                $fieldValue($this, $additionalSelectField);
            }
        }

        return $this->_query;
    }

    /**
     * Apply default condition
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param array $search
     */
    private function applyDefaultCondition(array $search)
    {
        if($this->defaultCondition) {
            if(is_string($this->defaultCondition)) {
                $this->_query->where($this->defaultCondition);
            }else if(is_array($this->defaultCondition)) {
                if(isset($this->defaultCondition['condition']) && isset($this->defaultCondition['params'])) {
                    $this->_query->where($this->defaultCondition['condition'], $this->defaultCondition['params']);
                }else {
                    $this->_query->where($this->defaultCondition);
                }
            }else if($this->defaultCondition instanceof \Closure) {
                $fn = $this->defaultCondition;
                $fn($this, $search);
            }
        }
    }

    /**
     * Check if filter value not empty
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param $element
     * @return bool
     */
    private function isNotEmpty($element)
    {
        return $element === '0' || (isset($element) && !empty($element));
    }


    /**
     * Add dynamic where
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param $param
     * @param $value
     * @return $this
     */
    public function andWhere($param, $value)
    {
        if(substr($value, 0, 1) == "~" || substr($value, -1) == "~") {
            if(substr($value, 0, 1) == "~")
                $value = "%".substr($value, 1);
            if(substr($value, -1) == "~")
                $value = substr($value, 0, -1)."%";

            $this->_query->andFilterWhere(['LIKE', $param, $value, false]);
            //More or Less conditions
        }else if(in_array(substr($value, 0, 1), ['>', '<'])) {
            $this->_query->andFilterWhere([substr($value, 0, 1), $param, substr($value, 1)]);
            //In Condition separator ","
        }else if(strpos($value, ",")) {
            $this->_query->andFilterWhere(['in', $param, explode(",", $value)]);
        }else if(strtolower($value) == 'null') {
            $this->_query->andWhere([$param => null]);
        }else  {
            $this->_query->andFilterWhere([$param => $value]);
        }

        return $this;
    }

    /**
     * Make Internal Join with Checking joinTableAliases to skip double joins
     * Use this join function in all case when you join table in SearchQueryBuilder(s)
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param $type
     * @param $table
     * @param string $on
     * @param array $params
     * @return Query
     */
    public function join($type, $table, $on = '', $params = [])
    {
        if(!in_array($table, $this->joinedTablesAliases)) {
            $this->_query->join($type, $table, $on, $params);
            $this->joinedTablesAliases[] = $table;
        }

        return $this->_query;
    }
}