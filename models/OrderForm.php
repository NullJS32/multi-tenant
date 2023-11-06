<?php

namespace app\models;

use yii\base\Model;

/**
 * Модель для формы заказа.
 */
class OrderForm extends Model
{
    public ?int $customer_id = null;
    public ?string $order_date = null;
    public ?float $total_amount = null;

    /**
     * Правила валидации для полей формы.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['customer_id', 'order_date', 'total_amount'], 'required'],
            [['customer_id'], 'integer'],
            [['order_date'], 'date', 'format' => 'php:Y-m-d'],
            [['total_amount'], 'number'],
        ];
    }

    /**
     * Заполняет поля формы данными из существующего заказа.
     *
     * @param Order $order Заказ для редактирования.
     */
    public function edit(Order $order): void
    {
        $this->customer_id = $order->customer_id;
        $this->order_date = $order->order_date;
        $this->total_amount = $order->total_amount;
    }

    /**
     * Создает новый заказ на основе данных из формы.
     *
     * @return Order|null Возвращает новый заказ в случае успешного создания или null в случае ошибок.
     */
    public function create(): ?Order
    {
        if ($this->validate()) {
            $order = new Order();
            $order->customer_id = $this->customer_id;
            $order->order_date = $this->order_date;
            $order->total_amount = $this->total_amount;

            if ($order->save()) {
                return $order; // Возвращаем новый заказ
            }
        }

        return null; // В случае ошибок валидации или сохранения
    }
}
