<?php

namespace app\models;


use yii\db\ActiveQuery;

/**
 * Модель для таблицы "orders".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $order_date
 * @property float $total_amount
 *
 * @property Customer $customer
 */
class Order extends ExtActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'order_date', 'total_amount'], 'required'],
            [['customer_id'], 'integer'],
            [['order_date'], 'safe'],
            [['total_amount'], 'number'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'order_date' => 'Order Date',
            'total_amount' => 'Total Amount',
        ];
    }

    /**
     * Возвращает запрос для связи с моделью Customer.
     *
     * @return ActiveQuery
     */
    public function getCustomer(): ActiveQuery
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }
}
