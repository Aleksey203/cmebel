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
	        ['total', 'default', 'value' => 0],
            [['date_added', 'date_modified','products','files'], 'safe'],
	        [['date_added', 'date_modified'], 'default', 'value' => date('Y-m-d', strtotime( 'now' ))],
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
            'order_opencart_id' => 'номер заказа',
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
		if ($_POST['new_order']==1) $products = array();
		else {
			$query = "
		        SELECT sp.price, op.quantity
		        FROM order_product op
		        LEFT JOIN shop_products sp ON sp.id=op.product_id
		        WHERE op.order_id = ".$this->id."
		        "; //echo $query;

			$products = \Yii::$app->db->createCommand($query)
				->queryAll();
		}
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

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		if ($insert) {
			$saved = true;
			foreach ($_POST['Orders']['products'] as $id => $v) {
				if (!$saved) return $saved;

				$orderProduct = new OrderProduct();

				$orderProduct->order_id = $this->id;
				$orderProduct->product_id = $id;
				$orderProduct->quantity = $v['quantity'];

				$saved = $orderProduct->save();
			}

			unset($_POST['Orders']['files']['empty']);
			$pathTemp = Yii::getAlias('@app/runtime').'/order_files/'.$this->id.'/';
			$path = Yii::getAlias('@webroot/files/orders').'/'.$this->id.'/';
			if (!is_dir($path)) mkdir($path);
		}

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
		if ($_POST['new_order']==1) return true;
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

	public function setFiles($_files)
	{
		if ($_POST['new_order']==1) return true;
		unset($_files['empty']);
		$pathTemp = Yii::getAlias('@app/runtime').'/order_files/'.$this->id.'/';
		$path = Yii::getAlias('@webroot/files/orders').'/'.$this->id.'/';
		if (!is_dir($path)) mkdir($path);

		$oldFiles = OrderFiles::find()->where('order_id=:order_id',[':order_id'=>$this->id])->all();
		foreach ($oldFiles as $k => $oldFile) {
			$delete = true;
			foreach ($_files as $id => $v) {
				if ($oldFile->file==$v AND (!is_file($pathTemp.$v))) $delete = false;
			}
			if ($delete) {
				OrderFiles::deleteAll('id=:id',[':id'=>$oldFile->id]);
				unlink($path.$oldFile->file);
			}
		}
		
		$saved = true;
		foreach ($_files as $file) {
			if (!$saved) return $saved;
			if (!is_file($pathTemp.$file)) continue;
			$orderFile = new OrderFiles();
			$orderFile->order_id = $this->id;
			$orderFile->name = $file;
			$orderFile->file = $file;
			if ($orderFile->save()) {
				if (!rename($pathTemp.$file, $path.$file)) $saved = false;
			} else {
				$saved = false;
			}
			if (is_file($pathTemp.$file)) unlink($pathTemp.$file);
		}
		return $saved;
	}

	//преобразование кирилицы в транслит
	static public function trunslit($str){
		$str = Orders::strtolower_utf8(trim(strip_tags($str)));
		$str = str_replace(
			array('ä','ö','ü','а','б','в','г','д','е','ё','ж', 'з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц', 'ч',  'ш',   'щ','ь','ы','ъ','э','ю','я','і','ї','є'),
			array('a','o','u','a','b','v','g','d','e','e','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sch','shch','','y','','e','yu','ya','i','yi','e'),
			$str);
		$str = preg_replace('~[^-a-z0-9_.]+~u', '_', $str);	//удаление лишних символов
		$str = preg_replace('~[-]+~u','-',$str);			//удаление лишних -
		$str = trim($str,'-');								//обрезка по краям -
		$str = trim($str,'_');								//обрезка по краям -
		$str = trim($str,'.');
		return $str;
	}

	//зaмена функции strtolower
	static public function strtolower_utf8($str){
		$large = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','À','Á','Â','Ã','Ä','Å','Æ','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ð','Ñ','Ò','Ó','Ô','Õ','Ö','Ø','Ù','Ú','Û','Ü','Ý','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','Є');
		$small = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','à','á','â','ã','ä','å','æ','ç','è','é','ê','ë','ì','í','î','ï','ð','ñ','ò','ó','ô','õ','ö','ø','ù','ú','û','ü','ý','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','є');
		return str_replace($large,$small,$str);
	}
}
