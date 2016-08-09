<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "users_tracking".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $time_in
 * @property string $time_out
 * @property string $created_at
 */
class UserTrackingSearch extends UserTracking
{

    public $distinct=false;
    public $pagination=['pageSize' => 15,];
    public $descriptionText;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_tracking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['time_in', 'time_out', 'created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'time_in' => 'Time In',
            'time_out' => 'Time Out',
            'created_at' => 'Created At',
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
        $query = UserTracking::find()->distinct($this->distinct);

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
            ->andFilterWhere(['>','time_in',$this->time_in])
            ->andFilterWhere(['<','time_out',$this->time_out]);


        return $dataProvider;
    }
}
