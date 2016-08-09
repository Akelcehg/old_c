<?php

use yii\db\Schema;
use yii\db\Migration;

class m150323_194552_alter_user_indications extends Migration
{
    public function up()
    {
        $table = \Yii::$app->db->schema->getTableSchema('user_indications', true);

        $this->createIndex('user_counter_id', 'user_indications', 'user_counter_id');
    }

    public function down()
    {
        echo "m150323_194550_user_indications cannot be reverted.\n";

        return false;
    }
}
