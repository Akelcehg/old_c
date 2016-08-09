<?php

namespace app\models;

use Yii;
use app\models\UserIndications;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "user_indications".
 *
 * @property integer $id
 * @property integer $user_counter_id
 * @property integer $indications
 * @property string $created_at
 */
class EventLogSearch extends EventLog 
{
    public $distinct=false;
    public $pagination=['pageSize' => 15,];
    public $descriptionText;


    public function rules() {
        return [
            
            [['user_id','type','description','url','descriptionText','created_at'], 'safe'],
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
        $query = EventLog::find()->distinct($this->distinct);
             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$this->pagination,
            'sort'=>[
                'defaultOrder' =>
                    [
                'created_at' => SORT_DESC
                ]
            ]
            ]

            
        );

        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->filterWhere(['user_id' => $this->user_id])
                ->andFilterWhere(['type' => $this->type])
                ->andFilterWhere(['like','description', $this->description])
                ->andFilterWhere(['like','description', $this->descriptionText])
                ->andFilterWhere(['created_at' => $this->created_at]);
        
                
        return $dataProvider;
    }
 
    
    
}
