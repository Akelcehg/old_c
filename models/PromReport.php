<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prom_reports".
 *
 * @property integer $id
 * @property string $all_id
 * @property integer $is_valid
 * @property string $date
 * @property string $report_type
 * @property string $errors
 * @property string $created_at
 */
class PromReport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prom_reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'is_valid'], 'integer'],
            [['date', 'created_at', 'id'], 'safe'],
            [['report_type', 'errors'], 'string']
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
            'is_valid' => 'Is Valid',
            'date' => 'Date',
            'report_type' => 'Report Type',
            'errors' => 'Errors',
            'created_at' => 'Created At',
        ];
    }

    public function getDayData()
    {
        $dt = new \DateTime($this->date);

        $do=DateOptions::find()
            ->where(['all_id'=>$this->all_id])
            ->andWhere(['<','created_at',$this->date])
            ->orderBy(['id'=>SORT_DESC])
            ->one();


        $dt->add(new \DateInterval("PT".$do->contract_hour."H"));



        return $this->hasOne(DayData::className(), ['all_id' => 'all_id'])->where(['timestamp'=>$dt->format("Y-m-d H:i:s")]);

    }
}
