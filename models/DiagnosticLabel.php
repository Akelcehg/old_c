<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diagnostic_label".
 *
 * @property integer $id
 * @property integer $diagnostic_id
 * @property string $name
 */
class DiagnosticLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'diagnostic_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['diagnostic_id', 'name'], 'required'],
            [['diagnostic_id'], 'integer'],
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
            'diagnostic_id' => 'Diagnostic ID',
            'name' => 'Name',
        ];
    }
}
