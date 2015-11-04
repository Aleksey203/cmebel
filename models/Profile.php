<?php
namespace app\models;

use dektrium\user\models\Profile as BaseProfile;
use Yii;

class Profile extends BaseProfile
{
	public function rules()
	{
		return [
			'nameLength' => ['name', 'string', 'max' => 255],
			'phoneString' => ['phone', 'string', 'max' => 17],
		];
	}

	/** @inheritdoc */
	public function attributeLabels()
	{
		return [
			'name'           => Yii::t('user', 'ФИО'),
			'phone'          => Yii::t('user', 'Телефон'),
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			return true;
		}

		return false;
	}
}
