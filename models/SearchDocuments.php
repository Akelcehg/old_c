<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Documents;
use yii\db\Query;

/**
 * SearchDocuments represents the model behind the search form about `app\models\Documents`.
 */
class SearchDocuments extends Documents
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'address_id', 'how_hard', 'counter_model_id', 'status'], 'string'],
            [['description', 'type', 'created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Documents::find()
            ->joinWith('address')
            ->joinWith('counterModel');

        //$query->join('inner join', 'counter_address as ca', 'ca.id = documents.address_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        //$query->andFilterWhere(['documents.status'=>'Новый']);
        $query->andFilterWhere([
            'id' => $this->id,
            'address_id' => $this->address_id,
            'counter_model_id' => $this->counter_model_id,
            'documents.status' => $this->status,
            'how_hard' => $this->how_hard,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'documents.type', $this->type]);

        return $dataProvider;
    }
}
