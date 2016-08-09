<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_items_login_states".
 *
 * @property integer $id
 * @property string $name
 *
 * @property MenuItems[] $menuItems
 */
class MenuItemsLoginStates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_items_login_states';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuItems()
    {
        return $this->hasMany(MenuItems::className(), ['login_state_id' => 'id']);
    }
}
