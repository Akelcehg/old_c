<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "menu_items_access".
 *
 * @property integer $id
 * @property integer $menu_id
 * @property string $role_name
 */
class MenuItemsAccess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu_items_access';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['menu_id'], 'integer'],
            [['role_name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'menu_id' => 'Menu ID',
            'role_name' => 'Role Name',
        ];
    }
}
