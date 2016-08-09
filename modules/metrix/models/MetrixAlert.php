<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_alerts".
 *
 * @property integer $id
 * @property string $counter_id
 * @property string $type
 * @property string $created_at
 */
class MetrixAlert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_alerts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id'], 'integer'],
            [['created_at'], 'safe'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' =>Yii::t('alerts','address'),// '№ модема',
            'metrix_id' =>Yii::t('alerts','metrix_id'),// '№ модема',
            'cause' =>Yii::t('alerts','cause'),// '№ модема',
            'serial_number' =>Yii::t('alerts','modemNumber'),// '№ модема',
            'type' => Yii::t('alerts','type'),//'Тип',
            'created_at' => Yii::t('alerts','time'),//'Дата и время',
            'device_type'=>Yii::t('alerts','deviceType'),//'Тип оборудования',
            'status' => Yii::t('alerts','status'),//'Состояние',
        ];
    }
}
