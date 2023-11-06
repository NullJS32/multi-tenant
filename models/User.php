<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Модель пользователя, реализующая интерфейс IdentityInterface.
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id): ?self
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null): ?self
    {
        // Реализация этого метода зависит от вашего приложения.
        return null;
    }

    /**
     * Найти пользователя по имени пользователя.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username): ?self
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        //
    }

    /**
     * Проверка пароля.
     *
     * @param string $password Пароль для проверки.
     * @return bool Если предоставленный пароль действителен для текущего пользователя.
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Получить роль пользователя.
     *
     * @return null|string Роль пользователя.
     */
    public function getRole(): ?string
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!empty($roles)) {
            return array_values($roles)[0]->name;
        }
        return null;
    }
}
