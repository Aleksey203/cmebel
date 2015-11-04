<?php

use yii\db\Schema;
use yii\db\Migration;

class m151104_082546_edit_profile_table_new extends Migration
{
	public function up()
	{
		$this->renameColumn('profile', 'public_email', 'phone');
	}

	public function down()
	{
		$this->renameColumn('profile', 'phone', 'public_email');

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
