<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "impulses".
 *
 * @property integer $id
 * @property integer $indication_id
 * @property integer $impulse
 * @property string $created_at
 */
class Impulse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'impulses';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['indication_id', 'impulse'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indication_id' => 'Indication ID',
            'impulse' => 'Impulse',
            'created_at' => 'Created At',
        ];
    }
}
