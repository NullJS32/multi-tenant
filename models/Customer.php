<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * Модель для работы с таблицей "customer".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Order[] $orders
 */
class Customer extends ExtActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'customers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Получает запрос для связи с заказами (по отношению "один ко многим").
     *
     * @return ActiveQuery
     */
    public function getOrders(): ActiveQuery
    {
        return $this->hasMany(Order::class, ['customer_id' => 'id']);
    }
}
