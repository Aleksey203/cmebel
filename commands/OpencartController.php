<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;


class OpencartController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionOrders($message = 'hello world')
    {
	    $query = "
	        SELECT *
	        FROM clients
	        "; //echo $query;

	    $clients = \Yii::$app->db->createCommand($query)
		    ->queryAll();

	    $query = "
	        SELECT order_opencart_id
	        FROM orders
	        "; //echo $query;

	    $orders = \Yii::$app->db->createCommand($query)
		    ->queryAll();

	    $query = "
	        SELECT order_id, firstname, lastname, email, telephone, total, date_added, date_modified
	        FROM oc_order
	        WHERE TO_DAYS(NOW()) - TO_DAYS(date_added) <= 7
	        "; //echo $query;

	    $ordersOpencart = \Yii::$app->dbOpencart->createCommand($query)
		    ->queryAll();

	    foreach ($ordersOpencart as $k => $orderOpencart) {
		    foreach ($orders as $order) {
			    if ($order['order_opencart_id']==$orderOpencart['order_id']) unset($ordersOpencart[$k]);
		    }
		    $clientOpencart['name'] = trim($orderOpencart['lastname']).' '.trim($orderOpencart['firstname']);
		    $newClients[$k] = array('name' => $clientOpencart['name'], 'phone'=>trim($orderOpencart['telephone']), 'email'=>trim($orderOpencart['email']));
	    }

        foreach ($newClients as $k=>$newClient) {
	        foreach ($clients as $client) {
			    if ($client['name']==$newClient['name'] OR ($client['email']==$newClient['email'] AND $client['phone']==$newClient['phone']))
				    unset($newClients[$k]);
		    }
	    }

	    echo '<pre>';
	    print_r($newClients);
	    echo '</pre>';
	    foreach ($newClients as $k => $newClient) {
		    \Yii::$app->db->createCommand()->insert('clients', $newClient)->execute();
	    }

	    echo '<pre>';
	    print_r($ordersOpencart);
	    echo '</pre>';
	    foreach ($ordersOpencart as $k => $orderOpencart) {
		    $query = "
		        SELECT id
		        FROM clients
		        WHERE name='".trim($orderOpencart['lastname']).' '.trim($orderOpencart['firstname'])."'
		        AND email='".trim($orderOpencart['email'])."'
		        AND phone='".trim($orderOpencart['telephone'])."'
		        "; //echo $query;
		    $clientId = \Yii::$app->db->createCommand($query)->queryScalar();

		    \Yii::$app->db->createCommand()->insert('orders', [
			    'order_opencart_id' => $orderOpencart['order_id'],
			    'client_id' => intval($clientId),
			    'total' => $orderOpencart['total'],
			    'date_added' => $orderOpencart['date_added'],
			    'date_modified' => $orderOpencart['date_modified']
		    ])->execute();
	    }


	    echo "\n";
    }
}
