<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Customer;

/**
 * Модель для формы работы с клиентами.
 */
class CustomerForm extends Model
{
    public ?string $name = null;
    public ?string $email = null;

    /**
     * Правила валидации для полей формы.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'email'], 'required', 'message' => 'Это поле обязательно для заполнения.'],
            ['email', 'email', 'message' => 'Пожалуйста, введите корректный адрес электронной почты.'],
        ];
    }

    /**
     * Создает нового клиента на основе данных формы.
     *
     * @return bool Возвращает `true`, если клиент успешно создан, иначе `false`.
     */
    public function create(): bool
    {
        if ($this->validate()) {
            $customer = new Customer();
            $customer->name = $this->name;
            $customer->email = $this->email;

            if ($customer->save()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Заполняет форму данными существующего клиента для редактирования.
     *
     * @param Customer $customer Клиент для редактирования.
     * @return bool Возвращает `true`, если данные успешно заполнены, иначе `false`.
     */
    public function edit(Customer $customer): bool
    {
        $this->name = $customer->name;
        $this->email = $customer->email;

        return true;
    }

    /**
     * Обновляет данные существующего клиента на основе данных формы.
     *
     * @param Customer $customer Клиент для обновления.
     * @return bool Возвращает `true`, если клиент успешно обновлен, иначе `false`.
     */
    public function update(Customer $customer): bool
    {
        if ($this->validate()) {
            $customer->name = $this->name;
            $customer->email = $this->email;

            if ($customer->save()) {
                return true;
            }
        }

        return false;
    }
}
