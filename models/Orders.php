<?php

namespace app\models;

use Yii;


/**
 * This is the model class for table "orders".
 *
 * @property string $id
 * @property string $order_opencart_id
 * @property integer $version
 * @property string $client_id
 * @property integer $status_id
 * @property string $total
 * @property string $date_added
 * @property string $date_modified
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
	private $_products;

    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_opencart_id', 'client_id', 'total', 'date_added', 'date_modified'], 'required'],
            [['order_opencart_id', 'version', 'client_id', 'status_id'], 'integer'],
            [['total'], 'number'],
            [['date_added', 'date_modified','products'], 'safe'],
            [['order_opencart_id', 'version'], 'unique', 'targetAttribute' => ['order_opencart_id', 'version'], 'message' => 'Номер заказа из opencart в этой версии уже существет и не может быть дублирован.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'order_opencart_id' => 'номер заказа из opencart',
            'version' => 'версия',
            'client_id' => 'клиент',
            'status_id' => 'статус',
            'total' => 'итого',
            'date_added' => 'дата добавления',
            'date_modified' => 'дата изменения',
        ];
    }

	public function beforeSave($insert)
	{

		$query = "
	        SELECT sp.price, op.quantity
	        FROM order_product op
	        LEFT JOIN shop_products sp ON sp.id=op.product_id
	        WHERE op.order_id = ".$this->id."
	        "; //echo $query;

		$products = \Yii::$app->db->createCommand($query)
			->queryAll();

		$total = 0;
		foreach ($products as $k => $product) {
			$total += $product['price']*$product['quantity'];
		}
		$this->total = $total;

		$date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
		$this->date_modified = $date;

		if ($insert) {
			$this->date_added = $date;
		}

		return parent::beforeSave($insert);
	}

	public function getClient()
	{
		return $this->hasOne(Clients::className(), ['id' => 'client_id']);
	}

	public function getStatus()
	{
		return $this->hasOne(OrderStatus::className(), ['id' => 'status_id']);
	}

	public function getOrderProducts()
	{
		return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
	}

	public function getProducts()
	{
		return $this->hasMany(ShopProducts::className(), ['id' => 'product_id'])
			->via('orderProducts');
	}

	public function setProducts($_products)
	{
		$oldProducts = OrderProduct::find()->where('order_id=:order_id',[':order_id'=>$this->id])->all();
		foreach ($oldProducts as $k => $oldProduct) {
			$delete = true;
			foreach ($_products as $id => $v) {
				if ($oldProduct->id==$id) $delete = false;
			}
			if ($delete) {
				OrderProduct::deleteAll('id=:id',[':id'=>$oldProduct->id]);
			}
		}

		$saved = true;
		foreach ($_products as $id => $v) {
			if (!$saved) return $saved;
			if ($id=='new') {
				$orderProduct = new OrderProduct();
				foreach ($v as $productId => $v1) {
					$orderProduct->order_id = $this->id;
					$orderProduct->product_id = $productId;
					$orderProduct->quantity = $v1['quantity'];
				}
			} else {
				$orderProduct = OrderProduct::findOne($id);
				$orderProduct->quantity = $v['quantity'];
			}
			$saved = $orderProduct->save();
		}
		return $saved;
	}

	public function getFiles()
	{
		return $this->hasMany(OrderFiles::className(), ['order_id' => 'id']);
	}
}
