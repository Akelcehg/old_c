<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "intervention_label".
 *
 * @property integer $id
 * @property integer $intervention_id
 * @property string $name
 */
class InterventionLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'intervention_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intervention_id', 'name'], 'required'],
            [['intervention_id'], 'integer'],
            [['name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'intervention_id' => 'Intervention ID',
            'name' => 'Name',
        ];
    }
}
