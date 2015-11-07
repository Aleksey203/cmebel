<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shop_categories".
 *
 * @property string $id
 * @property string $opencart_id
 * @property string $name
 * @property string $parent_id
 * @property string $sort_order
 * @property integer $status
 */
class ShopCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['opencart_id', 'parent_id', 'sort_order', 'status'], 'integer'],
            [['name','parent_id', 'sort_order', 'status'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'opencart_id' => 'ID из интернет-магазина',
            'name' => 'название',
            'parent_id' => 'id родительской категории',
            'sort_order' => 'сортировка внутри родительской категории',
            'status' => 'статус',
        ];
    }
}
