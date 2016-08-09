<?php

namespace app\modules\metrix\models;

use app\modules\metrix\models\MetrixIndication;
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
class MetrixIndicationSearch extends MetrixIndication
{
    public $distinct=false;
    public $pagination=['pageSize' => 15,];


    public function rules() {
        return [
            
            [['counter_id','indications','created_at'], 'safe'],
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
        $query = MetrixIndication::find()->joinWith('counter',true)->distinct($this->distinct);
             
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$this->pagination,
            'sort'=>[
                'defaultOrder' =>
                    [
                        'created_at' => SORT_DESC
                    ]
            ]
        ]);

        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $query->filterWhere(['counter_id' => $this->counter_id])
                ->andFilterWhere(['indications' => $this->indications])

                ->andFilterWhere(['created_at' => $this->created_at]);
        
                
        return $dataProvider;
    }
 
    
    
}
