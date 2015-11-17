<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_files".
 *
 * @property string $id
 * @property string $name
 * @property string $order_id
 * @property string $file
 */
class OrderFiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'file'], 'required'],
            [['order_id'], 'integer'],
            [['name', 'file'], 'string', 'max' => 255],
            [['order_id', 'file'], 'unique', 'targetAttribute' => ['order_id', 'file'], 'message' => 'Файл в этой версии заказа уже существет и не может быть дублирован.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'name' => 'название файла',
            'order_id' => 'номер заказа в CRM',
            'file' => 'путь к файлу',
        ];
    }
}
