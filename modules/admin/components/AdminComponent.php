<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 9:50
 */

namespace app\modules\admin\components;

use yii\base\Component;
use yii\data\ActiveDataProvider;

class AdminComponent extends Component implements AdminComponentInterface
{

    /**
     * @var  \app\models\Counter | false
     */
    protected $model=false;
    /**
     * @var  \app\models\CounterSearch | false
     */
    protected $searchModel=false;
    /**
     * @var ActiveDataProvider|false
     */
    protected $dataProvider=false;
    /**
     * @var int
     */
    public $paginationSize=20;


    public function getModel(){

        return $this->model;
    }

    public function getSearchModel(){

        return $this->searchModel;

    }

    public function getDataProvider(){

        return $this->dataProvider;
    }

}