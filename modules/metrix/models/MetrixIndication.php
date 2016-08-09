<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_indications".
 *
 * @property integer $id
 * @property string $counter_id
 * @property double $indications
 * @property string $created_at
 */
class MetrixIndication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_indications';
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
        return $this->hasOne(MetrixCounter::className(), ['id' => 'counter_id']);
    }
}
