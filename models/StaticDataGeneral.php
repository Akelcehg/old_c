<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StaticDataGeneral".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property string $zavod_number
 * @property string $complex_name
 * @property integer $tube_count
 */
class StaticDataGeneral extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'StaticDataGeneral';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'tube_count'], 'integer'],
            [['zavod_number', 'complex_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'all_id' => 'All ID',
            'branch_id' => 'Branch ID',
            'zavod_number' => 'Zavod Number',
            'complex_name' => 'Complex Name',
            'tube_count' => 'Tube Count',
        ];
    }
}
