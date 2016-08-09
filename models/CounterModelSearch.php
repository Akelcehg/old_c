<?php

namespace app\models;

use Yii;
use app\models\CounterModel;
use yii\data\ActiveDataProvider;
use yii\base\Model;

/**
 * This is the model class for table "counter_models".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $rate
 */
class CounterModelSearch extends CounterModel
{
    
       public function rules()
    {
        return [
            [['name', 'rate','type'], 'safe'],   
        ];
    }
    
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
        
        $query=CounterModel::find();
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['type'=>$this->type]);

        return $dataProvider;
    }
}
