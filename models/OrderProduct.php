<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_product".
 *
 * @property string $id
 * @property string $order_id
 * @property string $product_id
 * @property integer $quantity
 */
class OrderProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'product_id', 'quantity'], 'required'],
            [['order_id', 'product_id', 'quantity'], 'integer'],
            [['order_id', 'product_id'], 'unique', 'targetAttribute' => ['order_id', 'product_id'], 'message' => 'Товар в этой версии заказа уже существет и не может быть дублирован.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'order_id' => 'номер заказа в CRM',
            'product_id' => 'номер товара',
            'quantity' => 'количество',
        ];
    }

	public function getProduct()
	{
		return $this->hasOne(ShopProducts::className(), ['id' => 'product_id']);
	}
}
