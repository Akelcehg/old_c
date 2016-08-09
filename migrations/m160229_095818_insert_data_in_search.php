<?php

use yii\db\Schema;
use yii\db\Migration;

class m160229_095818_insert_data_in_search extends Migration
{
    public function up()
    {

        $this->execute('
 INSERT INTO search(type,search_string, counter_id)
 SELECT
 "counter" as type,
 CONCAT_WS(" ",
  (SELECT address.street FROM address WHERE address.id=counters.geo_location_id),
  (SELECT address.house FROM address WHERE address.id=counters.geo_location_id),
  counters.flat,
  counters.account,
  counters.fullname,
  (SELECT users.facility FROM users WHERE users.id=counters.user_id)
 ) as search_string,
 counters.id as counter_id
 FROM counters ;');

        $this->execute('
INSERT INTO search(type,search_string, counter_id)
SELECT
"corrector" as type,
CONCAT_WS(" ",
(SELECT address.street FROM address WHERE address.id=corrector_to_counter.geo_location_id),
(SELECT address.house FROM address WHERE address.id=corrector_to_counter.geo_location_id),
corrector_to_counter.contract,
 corrector_to_counter.company ) as search_string,
  corrector_to_counter.id as counter_id
  FROM corrector_to_counter');

    }

    public function down()
    {
        echo "m160229_095818_insert_data_in_search cannot be reverted.\n";

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
