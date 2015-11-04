<?php

use yii\db\Schema;
use yii\db\Migration;

class m151104_083433_drop_columns_profile_table extends Migration
{
    public function up()
    {
	    $this->dropColumn('profile', 'gravatar_email');
	    $this->dropColumn('profile', 'gravatar_id');
	    $this->dropColumn('profile', 'location');
	    $this->dropColumn('profile', 'website');
	    $this->dropColumn('profile', 'bio');
    }

    public function down()
    {
        echo "m151104_083433_drop_columns_profile_table cannot be reverted.\n";

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
