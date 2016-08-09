<?php

use yii\db\Schema;
use yii\db\Migration;

class m160701_113849_alter_documents_add_additional_fields extends Migration
{
    public function up()
    {

        $table = \Yii::$app->db->schema->getTableSchema('survey_data', true);

        if(!isset($table)) {
            $this->execute("CREATE TABLE `survey_data` (
                              `id` int(10) unsigned NOT NULL,
                              `address_id` int(10) unsigned,
                              `install_place` VARCHAR (255),
                              `install_replace` VARCHAR (255),
                              `is_restricted_area` VARCHAR (255),
                              `device_type` VARCHAR (255),
                              `corrector_type` VARCHAR (255),
                              `interface_converter_info` VARCHAR (255),
                              `data_cable_info`  VARCHAR (255),
                              `supply_type` VARCHAR (255),
                              `gsm_signal_level` VARCHAR (255),
                              `service_company_phone` VARCHAR (255),
                              `modem_mount_type` VARCHAR (255),
                              `status` enum('ACTIVE','DISABLED'),
                              `description` text,
                              `created_at` timestamp,
                              `updated_at` timestamp
                            ) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
                            ALTER TABLE `survey_data` ADD PRIMARY KEY(`id`);
                            ALTER TABLE `survey_data` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;
                            ");
        }

    }

    public function down()
    {
        echo "m160701_113849_alter_documents_add_additional_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
