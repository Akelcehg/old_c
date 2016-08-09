<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menus".
 *
 * @property integer $id
 * @property string $alias
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['alias', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menu', 'ID'),
            'alias' => Yii::t('menu', 'Alias'),
            'name' => Yii::t('menu', 'Name'),
            'created_at' => Yii::t('menu', 'Created At'),
            'updated_at' => Yii::t('menu', 'Updated At'),
        ];
    }
}
