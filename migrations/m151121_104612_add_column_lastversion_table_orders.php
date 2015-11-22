<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\Orders;

class m151121_104612_add_column_lastversion_table_orders extends Migration
{
    public function up()
    {
	    $this->addColumn('orders','last_version',Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1');

	    $orders = Orders::find()->all();
	    foreach ($orders as $k => $order) {
		    $order->updateVersion();
	    }
    }

    public function down()
    {
	    $this->dropColumn('orders','last_version');
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
