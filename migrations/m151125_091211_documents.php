<?php

use yii\db\Schema;
use yii\db\Migration;

class m151125_091211_documents extends Migration
{
    public function up()
    {
        $this->createTable('documents', [
            'id' => 'pk',
            'address_id' => 'int(12)',
            'counter_model_id' => 'int(12)',
            'description' => 'text',
            'status' => "enum('Новый','Активный','В работе','Завершен')",
            'how_hard' => "enum('Легко','Средне','Трудно')",
            'type' => "enum('gas','water')",
            'user_id' => "int(1)",
            'created_at' => 'timestamp',
        ]);
    }

    public function down()
    {
        echo "m151125_091211_documents cannot be reverted.\n";

        return false;
    }
}
