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

    public function actionOrders()
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
	        WHERE TO_DAYS(NOW()) - TO_DAYS(date_added) <= ".\Yii::$app->params['orderDays']; //echo $query;

	    $ordersOpencart = \Yii::$app->dbOpencart->createCommand($query)
		    ->queryAll();
		if (!isset($ordersOpencart[0])) {
		    echo 'No orders in opencart in last '.\Yii::$app->params['orderDays'].' days';
			die();
		}
	    $newClients = array();
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

	    echo '$ordersOpencart = <pre>';
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

	    $query = "
	        SELECT op.*
	        FROM oc_order_product op
	        LEFT JOIN oc_order o ON o.order_id=op.order_id
	        WHERE TO_DAYS(NOW()) - TO_DAYS(o.date_added) <= 70
	        "; //echo $query;

	    $ordersProductOpencart = \Yii::$app->dbOpencart->createCommand($query)
		    ->queryAll();

	    echo '<pre>';
	    print_r($ordersProductOpencart);
	    echo '</pre>';
	    foreach ($ordersProductOpencart as $k => $orderProductOpencart) {
		    $query = "
		        SELECT id
		        FROM orders
		        WHERE order_opencart_id='".trim($orderProductOpencart['order_id'])."'
		        AND version=1
		        "; //echo $query;
		    $orderId = \Yii::$app->db->createCommand($query)->queryScalar();

		    $query = "
		        SELECT id
		        FROM shop_products
		        WHERE opencart_id='".trim($orderProductOpencart['product_id'])."'
		        "; //echo $query;
		    $productId = \Yii::$app->db->createCommand($query)->queryScalar();

		    \Yii::$app->db->createCommand()->insert('order_product', [
			    'order_id' => intval($orderId),
			    'product_id' => intval($productId),
			    'quantity' => $orderProductOpencart['quantity']
		    ])->execute();
	    }

	    echo "\n";
    }

	public function actionShop()
	{
		$query = "
	        SELECT opencart_id
	        FROM shop_categories
	        "; //echo $query;

		$shopCategories = \Yii::$app->db->createCommand($query)
			->queryAll();

		$query = "
	        SELECT c.*, cd.name
	        FROM oc_category c
	        LEFT JOIN oc_category_description cd ON cd.category_id=c.category_id
	        WHERE cd.language_id=1
	        "; //echo $query;

		$shopCategoriesOpencart = \Yii::$app->dbOpencart->createCommand($query)
			->queryAll();

		foreach ($shopCategoriesOpencart as $k => $categoryOpencart) {
			foreach ($shopCategories as $category) {
				if ($category['opencart_id']==$categoryOpencart['category_id']) unset($shopCategoriesOpencart[$k]);
			}
		}
		/*echo '<pre>';
		print_r($shopCategoriesOpencart);
		echo '</pre>';*/
		$insertedC=0;
		foreach ($shopCategoriesOpencart as $k => $categoryOpencart) {
			if (\Yii::$app->db->createCommand()->insert('shop_categories', [
				'opencart_id' => $categoryOpencart['category_id'],
				'name' => $categoryOpencart['name'],
				'parent_id' => $categoryOpencart['parent_id'],
				'status' => $categoryOpencart['status']
			])->execute()) $insertedC++;
			if ($categoryOpencart['parent_id']>0)
				$insertedCategories[$categoryOpencart['category_id']] = $categoryOpencart['parent_id'];
		}

		if (isset($insertedCategories)) {
			foreach ($insertedCategories as $insertedCategory => $parentId) {
				$query = "
		        SELECT id
		        FROM shop_categories
		        WHERE opencart_id='".intval($parentId)."'
		        "; //echo $query;
				$newParentId = \Yii::$app->db->createCommand($query)->queryScalar();
				\Yii::$app->db->createCommand()
					->update('shop_categories', ['parent_id' => $newParentId], 'opencart_id ='.$insertedCategory)
					->execute();
		    }

		}
		echo "Inserted new categories: $insertedC\n";


		$query = "
	        SELECT opencart_id
	        FROM shop_products
	        "; //echo $query;

		$shopProducts = \Yii::$app->db->createCommand($query)
			->queryAll();

		$query = "
	        SELECT p.*, pd.name, pc.category_id
	        FROM oc_product p
	        LEFT JOIN oc_product_description pd ON pd.product_id=p.product_id
	        LEFT JOIN oc_product_to_category pc ON pc.product_id=p.product_id
	        WHERE pd.language_id=1
	        GROUP BY p.product_id
	        ORDER BY pc.category_id
	        "; //echo $query;

		$shopProductsOpencart = \Yii::$app->dbOpencart->createCommand($query)
			->queryAll();

		foreach ($shopProductsOpencart as $k => $productOpencart) {
			foreach ($shopProducts as $product) {
				if ($product['opencart_id']==$productOpencart['product_id']) unset($shopProductsOpencart[$k]);
			}
		}
		$insertedP=0;
		foreach ($shopProductsOpencart as $k => $productOpencart) {
			$query = "
		        SELECT id
		        FROM shop_categories
		        WHERE opencart_id='".intval($productOpencart['category_id'])."'
		        "; //echo $query;
			$categoryId = \Yii::$app->db->createCommand($query)->queryScalar();

			if (!isset($productOpencart['name']) OR $productOpencart['name']==null OR strlen($productOpencart['name'])>0==false)
				$productOpencart['name'] = $productOpencart['model'];
			$array = explode('/',$productOpencart['image']);
			echo '<pre>';
			print_r($array);
			echo '</pre>';
			$image = array_pop($array);
			\Yii::$app->db->createCommand()->insert('shop_products', [
				'opencart_id' => $productOpencart['product_id'],
				'name' => $productOpencart['name'],
				'model' => $productOpencart['model'],
				'category_id' => $categoryId,
				'price' => $productOpencart['price'],
				'quantity' => $productOpencart['quantity'],
				'date_added' => $productOpencart['date_added'],
				'date_modified' => $productOpencart['date_modified'],
				'image' => $image,
				'status' => $productOpencart['status']
			])->execute();
			if ($image) {
				$query = "
			        SELECT id
			        FROM shop_products
			        ORDER BY id DESC LIMIT 1
			        "; //echo $query;
				$productId = \Yii::$app->db->createCommand($query)->queryScalar();
				$pathOpencart = \Yii::$app->params['urlOpencart'];
				$path = \Yii::getAlias('@app/web/files/shop_products').'/'.$productId.'/';
				if (!is_dir($path)) mkdir($path);
				copy($pathOpencart.'/image/'.$productOpencart['image'], $path.$image);
			}
			$insertedP++;
		}
		echo "Inserted new products: $insertedP\n";
	}

	public function actionStatuses() {
		$query = "
	        SELECT order_status_id id, name
	        FROM oc_order_status
	        WHERE language_id=1
	        "; //echo $query;

		$statusesOpencart = \Yii::$app->dbOpencart->createCommand($query)
			->queryAll();
		$insertedS=0;
		foreach ($statusesOpencart as $k => $statusOpencart) {
			$query = "
		        SELECT id
		        FROM order_status
		        WHERE id='".intval($statusOpencart['id'])."'
		        "; //echo $query;
			$statusId = \Yii::$app->db->createCommand($query)->queryScalar();
			if ($statusId>0===false) {
				\Yii::$app->db->createCommand()->insert('order_status', [
					'id' => $statusOpencart['id'],
					'name' => $statusOpencart['name']
				])->execute();
				$insertedS++;
			}
		}
		echo "Inserted new statuses: $insertedS\n";
	}
	public function actionClear()
	{
		$queries = [
			'TRUNCATE `orders`',
			'TRUNCATE `order_files`',
			'TRUNCATE `order_product`',
			'TRUNCATE `shop_categories`',
			'TRUNCATE `shop_products`',
			'TRUNCATE `tasks`',
			'TRUNCATE `task_files`',
		];
		foreach ($queries as $query) {
			if (\Yii::$app->db->createCommand($query)->execute()==0)
				echo $query." is executed\n";
			else echo $query." is NOT executed\n";
		}
	}
}
