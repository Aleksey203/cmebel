<?php

namespace app\models;

use Yii;
use app\models\User;

/**
 * This is the model class for table "tasks".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $order_opencart_id
 * @property integer $serial_number
 * @property string $text
 * @property string $comment
 * @property string $date_added
 * @property string $date_start
 * @property string $date_end
 * @property string $date_closed
 */
class Tasks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'order_opencart_id', 'text',  'date_added', 'date_start', 'date_end', 'serial_number'], 'required'],
            [['user_id', 'order_opencart_id', 'serial_number'], 'integer'],
            [['text', 'comment'], 'string'],
            [['date_added', 'date_start', 'date_end', 'date_closed'], 'safe'],
            [['serial_number'], 'unique', 'targetAttribute' => ['serial_number'], 'message' => 'Такой номер задачи уже используется'],
            ['date_start', 'dateStartBeforeDateEndFnc'],

            ['serial_number', 'isNextInOrder']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уник. номер',
            'user_id' => 'Исполнитель',
            'order_opencart_id' => 'Заказ',
            'serial_number' => 'Номер',
            'text' => 'Формулировка',
            'comment' => 'Комментарий',
            'date_added' => 'Дата добавления',
            'date_start' => 'Начало',
            'date_end' => 'Конец',
            'date_closed' => 'Дата закрытия',
        ];
    }

    public function isNextInOrder($attribute, $params) {

        $currentNumberInForm = intval($this->serial_number);

        $calculatedNextNumberTask = Tasks::getNextNumberTaskOfOrder($this->order_opencart_id);

        if ($currentNumberInForm != $calculatedNextNumberTask && empty($this->id)) {
            $this->addError($attribute, 'Задайте следующий номер задачи в рамках заказа. Подсказка, след. номер задачи: '.$calculatedNextNumberTask);
        }
    }

    public function dateStartBeforeDateEndFnc($attribute, $params)
    {
        if (strtotime($this->date_start) > strtotime($this->date_end) ) {
            $this->addError($attribute, 'Дата начала должна быть меньше либо равна дате окончания');
        }
    }


    public function getUserData()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->from(User::tableName() . ' AS userData');
    }

    public function getOrderData()
    {
        return $this->hasOne(Orders::className(), ['order_opencart_id' => 'order_opencart_id']);
    }


    public function getOwnerUser() {
        return $this->userData->username;
    }


    public static function getNextNumberTaskOfOrder($idOrder) {

        $result = 1;

        $row = self::find()->where('order_opencart_id=:order_opencart_id',[':order_opencart_id'=>$idOrder])->orderBy('serial_number DESC')->one();
        if($row)
            $result = intval($row->serial_number)+1;

        return $result;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {


            $this->date_added = date('Y-m-d H:i:s',strtotime($this->date_added));

            $this->date_start = date('Y-m-d H:i:s',strtotime($this->date_start));
            $this->date_end = date('Y-m-d H:i:s',strtotime($this->date_end));
            $this->date_closed = $this->date_closed ? date('Y-m-d H:i:s',strtotime($this->date_closed)) : null ;


            return true;
        } else {
            return false;
        }
    }

}
