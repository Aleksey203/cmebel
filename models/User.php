<?php
namespace app\models;

use dektrium\user\models\User as BaseUser;
use Yii;
class User extends BaseUser
{


    public static function getNameById($id) {
        $row = self::find()->andWhere('id = :userId', array('userId' => $id))->one();
        if($row)
            return $row->username;
        return '';
    }



    public static function getMenuItemsByRoleUser($isAdmin,$isGuest) {

        if($isGuest)
            return [];

        if($isAdmin) {
            return [
                ['label' => 'Заказы', 'url' => ['/orders/index']],
                ['label' => 'Задачи', 'url' => ['/tasks/index']],
                ['label' => 'Категории', 'url' => ['/shop-categories/index']],
                ['label' => 'Товары', 'url' => ['/shop-products/index']],
                ['label' => 'Номенклатура', 'url' => ['/shop-products/tree']],
                ['label' => 'Клиенты', 'url' => ['/clients/index']],
                ['label' => 'Пользователи', 'url' => ['/user/admin/index']],
                    [
                        'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ];

        } else {
            return [
                ['label' => 'Задачи', 'url' => ['/tasks-manager/index']],
                [
                    'label' => 'Выйти (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
            ];
        }



    }

}
