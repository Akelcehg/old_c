<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menus_label".
 *
 * @property integer $id
 * @property string $menu_item_id
 * @property integer $lang_id
 * @property string $label
 * @property string $created_at
 * @property string $updated_at
 */
class MenusLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menus_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lang_id'], 'required'],
            [['lang_id'], 'integer'],
            [['created_at', 'updated_at','label'], 'safe'],
            [['menu_item_id', 'label'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('menu', 'ID'),
            'menu_item_id' => Yii::t('menu', 'Menu Item ID'),
            'lang_id' => Yii::t('menu', 'Lang ID'),
            'label' => Yii::t('menu', 'Label'),
            'created_at' => Yii::t('menu', 'Created At'),
            'updated_at' => Yii::t('menu', 'Updated At'),
        ];
    }

    public function getLanguage()
    {
       return $this->hasOne(Language::className(),['id'=>'lang_id']);
    }
}
