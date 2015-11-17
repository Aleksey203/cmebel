<?php
namespace app\models;

use dektrium\user\models\User as BaseUser;

class User extends BaseUser
{


    public static function getNameById($id) {
        $row = self::find()->andWhere('id = :userId', array('userId' => $id))->one();
        if($row)
            return $row->username;
        return '';
    }

}
