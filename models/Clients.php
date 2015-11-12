<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 */
class Clients extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 63],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 127]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'номер',
            'name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Email',
        ];
    }
}
