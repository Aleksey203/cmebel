<?php

use yii\db\Schema;
use yii\db\Migration;

class m151117_132436_set_date_close_task_allow_null extends Migration
{
    public function up()
    {
        $this->alterColumn('tasks','date_closed','DATETIME NULL');
    }

    public function down()
    {
        return true;
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
