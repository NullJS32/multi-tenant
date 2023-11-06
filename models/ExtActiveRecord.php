<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Connection;

/**
 * Расширенный класс ActiveRecord с динамической настройкой соединения с базой данных.
 */
class ExtActiveRecord extends ActiveRecord
{
    /**
     * Переопределение метода getDb для динамической настройки соединения с базой данных.
     *
     * @return Connection Соединение с базой данных.
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('userDbLocator')->getDb();
    }
}
