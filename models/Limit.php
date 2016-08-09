<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "limits".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $limit
 * @property string $month
 * @property string $year
 * @property string $created_at
 * @property string $updated_at
 */
class Limit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'limits';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id','month', 'year'], 'required'],
            [['all_id', 'limit'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['month', 'year'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('limit','Id'),//'ID',
            'all_id' =>Yii::t('limit','№ of accounting unit'),// '№ прибора учета',
            'limit' =>Yii::t('limit','Limit of gas consumption , m<sup>3</sup>'),// 'Лимит расхода газа',
            'month' =>Yii::t('limit','Month'),// 'месяц',
            'year' => Yii::t('limit','Year'),//'год',
            'created_at' =>Yii::t('limit','Created At'),// 'Created At',
            'updated_at' => Yii::t('limit','Updated At'),//'Updated At',
        ];
    }
}
