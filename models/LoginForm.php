<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы входа (LoginForm).
 *
 * @property-read User|null $user
 */
class LoginForm extends Model
{
    public ?string $username = null;
    public ?string $password = null;
    public ?bool $rememberMe = true;

    private ?User $_user = null;

    /**
     * @return array Правила валидации данных.
     */
    public function rules(): array
    {
        return [
            // username и password обязательны для заполнения
            [['username', 'password'], 'required'],
            // rememberMe должен быть булевым значением
            ['rememberMe', 'boolean'],
            // Пароль проверяется методом validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Проверяет пароль.
     * Этот метод служит для встроенной валидации пароля.
     *
     * @param string $attribute атрибут, который в данный момент проверяется
     */
    public function validatePassword(string $attribute): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверное имя пользователя или пароль.');
            }
        }
    }

    /**
     * Авторизует пользователя с использованием предоставленного имени пользователя и пароля.
     *
     * @return bool успешно ли пользователь авторизован
     */
    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Находит пользователя по [[username]]
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
