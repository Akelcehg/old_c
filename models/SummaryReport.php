<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "summary_reports".
 *
 * @property integer $id
 * @property string $prom
 * @property integer $legal_entity
 * @property string $house_metering
 * @property string $individual
 * @property string $grs
 * @property string $created_at
 */
class SummaryReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'summary_reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['prom', 'legal_entity','house_metering','individual', 'grs','all'], 'number'],
            [[ 'created_at'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prom' => 'Prom',
            'legal_entity' => 'Legal Entity',
            'house_metering' => 'House Metering',
            'individual' => 'Individual',
            'grs' => 'Grs',
            'created_at' => 'Created At',
        ];
    }
}
