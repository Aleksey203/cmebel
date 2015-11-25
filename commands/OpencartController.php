<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\helpers\ArrayHelper;
use  yii\db\Query;

//call commands sequence:
//yii opencart/shop         get nomenclatures
//yii opencart/statuses     get statuses order
//yii opencart/order        get orders, clients


class OpencartController extends Controller
{


    public function actionIndex($message = 'hello world from module')
    {
        echo $message . "\n";
    }

    //import orders from shop into CRM
    public function actionClosed() {
	    $closed = 0;
	    $closedId = 5;
	    $query = "
	        SELECT order_opencart_id
	        FROM orders
	        WHERE status_id=$closedId AND last_version=1
	        AND TO_DAYS(NOW()) - TO_DAYS(date_added) <= 366
	        GROUP BY order_opencart_id"; //echo $query;

	    $closedOrders = \Yii::$app->db->createCommand($query)
		    ->queryAll();

	    echo 'Is finded closed orders:'.count($closedOrders)."\n";

	    foreach ($closedOrders as $k => $closedOrder) {
		    echo 'Order opencart ID:'.$closedOrder['order_opencart_id']."\n";
		    \Yii::$app->dbOpencart->createCommand()
			    ->update('oc_order', ['order_status_id' => $closedId], 'order_id ='.$closedOrder['order_opencart_id'])
			    ->execute();
		    $closed++;
	    }

	    echo 'Closed orders to opencart:'.$closed."\n";
    }

    public function actionOrders()
    {
	    $query = "
	        SELECT *
	        FROM clients
	        "; //echo $query;

	    $clients = \Yii::$app->db->createCommand($query)
		    ->queryAll();

	    $query = "
	        SELECT order_opencart_id, date_added, date_modified
	        FROM orders
	        "; //echo $query;

	    $orders = \Yii::$app->db->createCommand($query)
		    ->queryAll();

	    $query = "
	        SELECT order_id, firstname, lastname, email, telephone, total, date_added, date_modified, order_status_id
	        FROM oc_order
	        WHERE TO_DAYS(NOW()) - TO_DAYS(date_added) <= ".\Yii::$app->params['orderDays']; //echo $query;

	    $ordersOpencart = \Yii::$app->dbOpencart->createCommand($query)
		    ->queryAll();

		if (!isset($ordersOpencart[0])) {
		    echo 'No orders in opencart in last '.\Yii::$app->params['orderDays'].' days';
			die();
		}


	    foreach ($ordersOpencart as $k => $orderOpencart) {

            echo '<pre>';
            print_r($orderOpencart);
            echo '</pre>';

            $query = "
	        SELECT id,order_opencart_id, date_added, date_modified, client_id
	        FROM orders
	        WHERE order_opencart_id=".$orderOpencart['order_id']."
            ORDER BY version LIMIT 1";

            $orderCRM = \Yii::$app->db->createCommand($query)
                ->queryOne();

            echo '<pre>';
            print_r($orderCRM);
            echo '</pre>';

            $clientData = [
                'name'=>trim($orderOpencart['lastname']).' '.trim($orderOpencart['firstname']),
                'phone'=>trim($orderOpencart['telephone']),
                'email'=>trim($orderOpencart['email'])
            ];

            if($orderCRM) {
                if((strtotime($orderCRM['date_modified']) <= strtotime($orderOpencart['date_modified']))) {
                    //update order
                    \Yii::$app->db->createCommand()->update(
                        'orders',
                        [
                            'total' => $orderOpencart['total'],
                            'date_added' => $orderOpencart['date_added'],
                            'date_modified' => $orderOpencart['date_modified'],
                            'status_id'=>$orderOpencart['order_status_id']
                        ],
                        "id=:id",
                        [':id'=>$orderCRM['id']]
                    )->execute();

                    //update client
                    \Yii::$app->db->createCommand()->update(
                        'clients',
                        $clientData,
                        "id=:id",
                        [':id'=>$orderCRM['client_id']]
                    )->execute();
                }
                $orderCRMId = $orderCRM['id'];
            } else {

                $clientId = (new Query())
                    ->select('id')
                    ->from('clients')
                    ->where(
                        'name=:name AND phone=:phone AND email=:email',
                        [':name'=>$clientData['name'],':phone'=>$clientData['phone'],':email'=>$clientData['email']]
                    )
                    ->scalar();

                if(!$clientId) {
                    //insert new client
                    \Yii::$app->db->createCommand()->insert('clients', $clientData)->execute();
                    //\Yii::$app->db->createCommand()->update('clients', $newClient,"id=:id",array(':id'=>$orderWithClientFromCRM['client_id']))->execute();
                    $clientId = \Yii::$app->db->getLastInsertID();
                }

                //insert new order
                \Yii::$app->db->createCommand()->insert('orders', [
                    'order_opencart_id' => $orderOpencart['order_id'],
                    'client_id' => intval($clientId),
                    'total' => $orderOpencart['total'],
                    'date_added' => $orderOpencart['date_added'],
                    'date_modified' => $orderOpencart['date_modified'],
                    'status_id'=>$orderOpencart['order_status_id']
                ])->execute();

                $orderCRMId = \Yii::$app->db->getLastInsertID();
            }

            //update data about nomenclatures of order

	        $query = "
                SELECT op.*
                FROM oc_order_product op
                INNER JOIN oc_order o ON o.order_id=op.order_id
                WHERE o.order_id =".$orderOpencart['order_id'];

	        $ordersProductOpencart = \Yii::$app->dbOpencart->createCommand($query)
		        ->queryAll();

            $findedProductInOrderCRM  = [];
            foreach ($ordersProductOpencart as $k => $orderProductOpencart) {

                $query = "
                    SELECT id
                    FROM shop_products
                    WHERE opencart_id='".trim($orderProductOpencart['product_id'])."'
                    "; //echo $query;

                $productId = \Yii::$app->db->createCommand($query)->queryScalar();

                $productInOrderCRM = (new Query())
                    ->select('id')
                    ->from('order_product')
                    ->where(
                        'order_id=:order_id AND product_id=:product_id',
                        [':order_id'=>$orderCRMId,':product_id'=>$productId]
                    )
                    ->scalar();

                if($productInOrderCRM) {

                    \Yii::$app->db->createCommand()->update('order_product', [
                            'quantity' => $orderProductOpencart['quantity']
                        ], 'order_id=:order_id AND product_id=:product_id',
                        [
                            'order_id' => intval($orderCRMId),
                            'product_id' => intval($productId),
                        ]
                    )->execute();
                    $findedProductInOrderCRM[] = $productId;//$productInOrderCRM;

                } else {

                    \Yii::$app->db->createCommand()->insert('order_product', [
                        'order_id' => $orderCRMId,
                        'product_id' => intval($productId),
                        'quantity' => $orderProductOpencart['quantity']
                    ])->execute();

                    $findedProductInOrderCRM[] = $productId;//\Yii::$app->db->getLastInsertID();

                }

            }

            \Yii::$app->db
                ->createCommand()
                ->delete('order_product',['and','order_id='.$orderCRMId,['not in','product_id',$findedProductInOrderCRM]])
                ->execute();

	    }

	    echo "\n";
    }


//    public function actionOrders()
//    {
//        $query = "
//	        SELECT *
//	        FROM clients
//	        "; //echo $query;
//
//        $clients = \Yii::$app->db->createCommand($query)
//            ->queryAll();
//
//        $query = "
//	        SELECT order_opencart_id, date_added, date_modified
//	        FROM orders
//	        "; //echo $query;
//
//        $orders = \Yii::$app->db->createCommand($query)
//            ->queryAll();
//
//        $query = "
//	        SELECT order_id, firstname, lastname, email, telephone, total, date_added, date_modified, order_status_id
//	        FROM oc_order
//	        WHERE TO_DAYS(NOW()) - TO_DAYS(date_added) <= ".\Yii::$app->params['orderDays']; //echo $query;
//
//        $ordersOpencart = \Yii::$app->dbOpencart->createCommand($query)
//            ->queryAll();
//        if (!isset($ordersOpencart[0])) {
//            echo 'No orders in opencart in last '.\Yii::$app->params['orderDays'].' days';
//            die();
//        }
//        $newClients = array();
//        foreach ($ordersOpencart as $k => $orderOpencart) {
//            foreach ($orders as $order) {
//                if ($order['order_opencart_id']==$orderOpencart['order_id'] && (strtotime($order['date_modified']) >= strtotime($orderOpencart['date_modified']))) {
//                    unset($ordersOpencart[$k]);
//                }
//            }
//            $clientOpencart['name'] = trim($orderOpencart['lastname']).' '.trim($orderOpencart['firstname']);
//            $newClients[$k] = array('name' => $clientOpencart['name'], 'phone'=>trim($orderOpencart['telephone']), 'email'=>trim($orderOpencart['email']),
//                'order_opencart_id'=>$orderOpencart['order_id']);
//        }
//
////        foreach ($newClients as $k=>$newClient) {
////	        foreach ($clients as $client) {
////			    if ($client['name']==$newClient['name'] && ($client['email']==$newClient['email'] && $client['phone']==$newClient['phone']))
////				    unset($newClients[$k]);
////		    }
////	    }
//
//        echo '<pre>';
//        print_r($newClients);
//        echo '</pre>';
//        foreach ($newClients as $k => $newClient) {
//
//            $queryGetOrderWithClientFromCRM = "
//                SELECT o.*,c.id as client_id
//                FROM orders o
//                LEFT JOIN clients c ON o.client_id=c.id
//                WHERE o.order_opencart_id=".$newClient['order_opencart_id']."
//	        ";
//
//            $orderWithClientFromCRM = \Yii::$app->db->createCommand($queryGetOrderWithClientFromCRM)->queryOne();
//
//            unset($newClient['order_opencart_id']);
//
//            if($orderWithClientFromCRM) {
//                \Yii::$app->db->createCommand()->update('clients', $newClient,"id=:id",array(':id'=>$orderWithClientFromCRM['client_id']))->execute();
//            } else {
//                \Yii::$app->db->createCommand()->insert('clients', $newClient)->execute();
//            }
//
//        }
//
//        echo '$ordersOpencart = <pre>';
//        print_r($ordersOpencart);
//        echo '</pre>';
//        foreach ($ordersOpencart as $k => $orderOpencart) {
//            $query = "
//		        SELECT id
//		        FROM clients
//		        WHERE name='".trim($orderOpencart['lastname']).' '.trim($orderOpencart['firstname'])."'
//		        AND email='".trim($orderOpencart['email'])."'
//		        AND phone='".trim($orderOpencart['telephone'])."'
//		        "; //echo $query;
//            $clientId = \Yii::$app->db->createCommand($query)->queryScalar();
//
//            $queryExistingOrderInCRM = "SELECT id
//            FROM orders o
//            WHERE o.order_opencart_id=".trim($orderOpencart['order_id']);
//
//            $existingOrderInCRM = \Yii::$app->db->createCommand($queryExistingOrderInCRM)->queryScalar();
//
//            if($existingOrderInCRM) {
//                \Yii::$app->db->createCommand()->update('orders', [
//                        'total' => $orderOpencart['total'],
//                        'status_id'=>$orderOpencart['order_status_id']
//                    ], "id=:id",
//                    [":id"=>intval($orderOpencart['order_id'])])->execute();
//            } else {
//                \Yii::$app->db->createCommand()->insert('orders', [
//                    'order_opencart_id' => $orderOpencart['order_id'],
//                    'client_id' => intval($clientId),
//                    'total' => $orderOpencart['total'],
//                    'date_added' => $orderOpencart['date_added'],
//                    'date_modified' => $orderOpencart['date_modified'],
//                    'status_id'=>$orderOpencart['order_status_id']
//                ])->execute();
//            }
//
//
//        }
//
//        $query = "
//	        SELECT op.*
//	        FROM oc_order_product op
//	        LEFT JOIN oc_order o ON o.order_id=op.order_id
//	        WHERE TO_DAYS(NOW()) - TO_DAYS(o.date_added) <= 70
//	        "; //echo $query;
//
//        $ordersProductOpencart = \Yii::$app->dbOpencart->createCommand($query)
//            ->queryAll();
//
//        $queryOrdersProductCRM =  "SELECT op.*
//            FROM shop_products sp
//            INNER JOIN order_product op ON op.product_id = sp.id
//            INNER JOIN (SELECT   id, version, order_opencart_id FROM orders o
//            GROUP BY order_opencart_id
//            ORDER BY o.version) o ON o.id = op.order_id
//        ";
//
//        $ordersProductCRM = \Yii::$app->db->createCommand($queryOrdersProductCRM) ->queryAll();
//
//        echo '<pre>';
//        print_r($ordersProductOpencart);
//        echo '</pre>';
//
//        foreach($ordersProductCRM as $currentOrderProductFromCRM) {
//
//            foreach ($ordersProductOpencart as $k => $orderProductOpencart) {
//
////            $queryGetIdLastVersionOfOrder = "
////                SELECT id FROM orders o
////                WHERE o.order_opencart_id = ".trim($orderProductOpencart['order_id'])."
////                ORDER BY o.version";
////            $idLastVersionOfOrder = \Yii::$app->db->createCommand($queryGetIdLastVersionOfOrder)
////                ->queryScalar();
////
////            $queryOrdersProductCRM = "
////                    SELECT op.*
////                    FROM shop_products sp
////                    INNER JOIN order_product op ON op.product_id = sp.id
////                    INNER JOIN orders o ON o.id = op.order_id
////                    WHERE o.id =  ".$idLastVersionOfOrder." AND sp.opencart_id=".$orderProductOpencart['product_id'];
////            $ordersProductCRM = \Yii::$app->db->createCommand($queryOrdersProductCRM) ->queryAll();//ArrayHelper::map(, 'opencart_id', 'id');
//
//                $query = "
//		        SELECT id
//		        FROM orders
//		        WHERE order_opencart_id='".trim($orderProductOpencart['order_id'])."'
//		        ORDER BY version
//		        "; //echo $query;
//                $orderId = \Yii::$app->db->createCommand($query)->queryScalar();
//
//                $query = "
//		        SELECT id
//		        FROM shop_products
//		        WHERE opencart_id='".trim($orderProductOpencart['product_id'])."'
//		        "; //echo $query;
//                $productId = \Yii::$app->db->createCommand($query)->queryScalar();
//
////            if(is_array($ordersProductCRM) && count($ordersProductCRM)>0) {
//
//                $queryCountRowsOrderProduct = "
//		        SELECT COUNT(id)
//		        FROM order_product
//		        WHERE order_id=".intval($orderId)." AND product_id=".intval($productId);
//
//                $countRowsOrderProduct = \Yii::$app->db->createCommand($queryCountRowsOrderProduct)->queryScalar();
//
//                if(intval($countRowsOrderProduct) > 0) {
//                    \Yii::$app->db->createCommand()->update('order_product', [
//                            'quantity' => $orderProductOpencart['quantity']
//                        ], 'order_id=:order_id AND product_id=:product_id',
//                        [
//                            'order_id' => intval($orderId),
//                            'product_id' => intval($productId),
//                        ]
//                    )->execute();
//                } else {
//                    \Yii::$app->db->createCommand()->insert('order_product', [
//                        'order_id' => intval($orderId),
//                        'product_id' => intval($productId),
//                        'quantity' => $orderProductOpencart['quantity']
//                    ])->execute();
//                }
//
////            } else {
////                \Yii::$app->db->createCommand()->delete('order_product',"order_id=:order_id AND product_id=:product_id",[":order_id"=>intval($orderId),':product_id'=>intval($productId)]);
////            }
//
//            }
//
//        }
//
//
//
//
//        echo "\n";
//    }
//
    //import orders from shop into CRM
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
		$insertedC=0;
		$updatedC=0;
		foreach ($shopCategoriesOpencart as $k => $categoryOpencart) {
			$isNewCategory = true;
			foreach ($shopCategories as $category) {
				if ($category['opencart_id']==$categoryOpencart['category_id']) {
					$isNewCategory = false;
					if (\Yii::$app->db->createCommand()->update('shop_categories', [
						'name' => $categoryOpencart['name'],
						'parent_id' => $categoryOpencart['parent_id'],
						'status' => $categoryOpencart['status']
					],'opencart_id = '.$categoryOpencart['category_id'])->execute()) $updatedC++;
					if ($categoryOpencart['parent_id']>0)
						$insertedCategories[$categoryOpencart['category_id']] = $categoryOpencart['parent_id'];
				}
			}
			if ($isNewCategory) {
				if (\Yii::$app->db->createCommand()->insert('shop_categories', [
					'opencart_id' => $categoryOpencart['category_id'],
					'name' => $categoryOpencart['name'],
					'parent_id' => $categoryOpencart['parent_id'],
					'status' => $categoryOpencart['status']
				])->execute()) $insertedC++;
				if ($categoryOpencart['parent_id']>0)
					$insertedCategories[$categoryOpencart['category_id']] = $categoryOpencart['parent_id'];
			}
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
		echo "Updated categories: $updatedC\n";
		echo "Inserted new categories: $insertedC\n";


		$query = "
	        SELECT id, opencart_id, image
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
	        ORDER BY p.product_id, pc.category_id
	        "; //echo $query;

		$shopProductsOpencart = \Yii::$app->dbOpencart->createCommand($query)
			->queryAll();

		$insertedP=0;
		$updatedP=0;
		foreach ($shopProductsOpencart as $k => $productOpencart) {
			$isNewProduct = true;
			$query = "
					        SELECT id
					        FROM shop_categories
					        WHERE opencart_id='".intval($productOpencart['category_id'])."'
					        "; //echo $query;
			$categoryId = \Yii::$app->db->createCommand($query)->queryScalar();

			if (!isset($productOpencart['name']) OR $productOpencart['name']==null OR strlen($productOpencart['name'])>0==false)
				$productOpencart['name'] = $productOpencart['model'];
			$array = explode('/',$productOpencart['image']);
			$image = array_pop($array);
			foreach ($shopProducts as $product) {

				if ($product['opencart_id']==$productOpencart['product_id']) {
					$isNewProduct = false;
					\Yii::$app->db->createCommand()->update('shop_products', [
						'name' => $productOpencart['name'],
						'model' => $productOpencart['model'],
						'category_id' => $categoryId,
						'price' => $productOpencart['price'],
						'quantity' => $productOpencart['quantity'],
						'date_added' => $productOpencart['date_added'],
						'date_modified' => $productOpencart['date_modified'],
						'image' => $image,
						'status' => $productOpencart['status']
					], 'opencart_id = '.$productOpencart['product_id'])->execute();

					if ($image!=$product['image']) {
						$pathOpencart = \Yii::$app->params['urlOpencart'];
						$path = \Yii::getAlias('@app/web/files/shop_products').'/'.$product['id'].'/';
						if (!is_dir($path)) mkdir($path);
						if (is_file($path.$product['image'])) unlink($path.$product['image']);
						copy($pathOpencart.'/image/'.$productOpencart['image'], $path.$image);
					}
					$updatedP++;
				}
			}
			if ($isNewProduct) {
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
		}

		echo "Updated products: $updatedP\n";
		echo "Inserted new products: $insertedP\n";
	}

    //export status order from crm into shop
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
            'TRUNCATE `clients`',
		];
		foreach ($queries as $query) {
			if (\Yii::$app->db->createCommand($query)->execute()==0)
				echo $query." is executed\n";
			else echo $query." is NOT executed\n";
		}
	}

}
