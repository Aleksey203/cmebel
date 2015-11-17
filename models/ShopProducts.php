<?php

namespace app\models;

use Yii;
use app\models\ShopCategories;

/**
 * This is the model class for table "shop_products".
 *
 * @property string $id
 * @property string $opencart_id
 * @property string $name
 * @property string $model
 * @property string $category_id
 * @property string $price
 * @property string $quantity
 * @property string $image
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class ShopProducts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opencart_id', 'category_id', 'quantity', 'status'], 'integer'],
            [['name', 'category_id', 'price', 'quantity', 'date_added', 'date_modified'], 'required'],
            [['price'], 'number'],
            [['date_added', 'date_modified'], 'safe'],
            //[['date_added', 'date_modified'], 'date','format'=>'Y-m-d H:i:s'],
            [['name', 'model', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'opencart_id' => 'номер из интернет-магазина',
            'name' => 'название',
            'model' => 'название модели',
            'category_id' => 'категория',
            'price' => 'цена',
            'quantity' => 'количество',
            'image' => 'фото',
            'status' => 'статус',
            'date_added' => 'дата добавления',
            'date_modified' => 'дата редактирования',
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

	public function getCategory()
	{
		return $this->hasOne(ShopCategories::className(), ['id' => 'category_id']);
	}
}
