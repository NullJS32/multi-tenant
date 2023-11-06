<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель для регистрации нового пользователя.
 */
class SignupForm extends Model
{
    public ?string $username = null;
    public ?string $password = null;

    /**
     * Правила валидации для полей формы.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Это имя пользователя уже занято.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Метки атрибутов формы.
     *
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Регистрирует нового пользователя на основе данных из формы.
     *
     * @return bool Возвращает true в случае успешной регистрации иначе false.
     */
    public function signup(): bool
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);

            if ($user->save()) {
                return true;
            }
        }

        return false;
    }
}
