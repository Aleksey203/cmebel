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
            [['date_added', 'date_modified'], 'safe'],
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
		$date = Yii::$app->formatter->asDate('now', 'php:Y-m-d H:i:s');
		$this->date_modified = $date;

		if ($insert) {
			$this->date_added = $date;
		}

		return parent::beforeSave($insert);
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

	public function getFiles()
	{
		return $this->hasMany(OrderFiles::className(), ['order_id' => 'id']);
	}
}
