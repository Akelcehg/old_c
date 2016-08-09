<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Name".
 *
 * @property integer $id
 * @property integer $all_id
 * @property string $tube_name
 * @property string $corrector_name
 * @property string $version
 */
class Name extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id'], 'integer'],
            [['tube_name', 'corrector_name', 'version'], 'string', 'max' => 255]
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
            'tube_name' => 'Tube Name',
            'corrector_name' => 'Corrector Name',
            'version' => 'Version',
        ];
    }
}
