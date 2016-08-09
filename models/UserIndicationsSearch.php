<?php

namespace app\models;

use Yii;
use app\models\UserIndications;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\CounterQuery;


/**
 * This is the model class for table "user_indications".
 *
 * @property integer $id
 * @property integer $user_counter_id
 * @property integer $indications
 * @property string $created_at
 */
class UserIndicationsSearch extends UserIndications
{
    public $distinct=false;
    public $pagination=['pageSize' => 15,];


    public function rules() {
        return [
            
            [['user_counter_id','indications','impuls','created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = UserIndications::find()->joinWith('counter',true)->distinct($this->distinct)->orderBy(['created_at' => SORT_DESC]);
             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$this->pagination,
        ]);

        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if (CounterQuery::isRole('admin_gas')) {
          $query->andWhere(['user_counters.type'=>'gas']); 
        }
        
        if (CounterQuery::isRole('admin_water')) {
           $query->andWhere(['user_counters.type'=>'water']); 
        }

        $query->filterWhere(['user_counter_id' => $this->user_counter_id])
                ->andFilterWhere(['indications' => $this->indications])
                ->andFilterWhere(['impuls' => $this->impuls])
                ->andFilterWhere(['created_at' => $this->created_at]);
        
                
        return $dataProvider;
    }
 
    
    
}
