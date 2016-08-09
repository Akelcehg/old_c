<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indications".
 *
 * @property integer $id
 * @property integer $counter_id
 * @property double $indications
 * @property string $created_at
 */
class Indication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id'], 'integer'],
            [['indications'], 'number'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('indications','Id'),//'ID',
            'counter_id' => Yii::t('indications','CounterId'),//''Counter ID',
            'indications' => Yii::t('indications','Indications'),//'Indications',
            'created_at' => Yii::t('indications','CreatedAt'),//'Created At',
            'impulse'=>Yii::t('indications','Impulse'),//'Created At',
        ];
    }

    public function getCounter()
    {
        return $this->hasOne(Counter::className(), ['id' => 'counter_id']);
    }

    public function getImpulse()
    {
        return $this->hasOne(Impulse::className(), ['indication_id' => 'id']);
    }
}
