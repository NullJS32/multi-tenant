<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\db\Connection;

/**
 * Компонент `UserDbLocator` предоставляет функциональность для динамического переключения баз данных
 * на основе данных текущего пользователя.
 */
class UserDbLocator extends Component
{
    /**
     * @var array Конфигурация соединения с базой данных.
     * Каждая конфигурация должна содержать ключи 'class' и 'dsn'.
     */
    public array $connection;

    /**
     * @var string Шаблон компонента базы данных. Строка '{id}' будет заменена на идентификатор пользователя.
     */
    public string $component = 'db_{id}';

    /**
     * @var int Идентификатор пользователя по умолчанию, используемый, когда пользователь не аутентифицирован.
     */
    public int $defaultId = 0;

    /** @var ?int Текущий активный идентификатор пользователя. */
    private ?int $activeId = null;

    /**
     * Инициализирует компонент, проверяет конфигурацию.
     *
     * @throws InvalidConfigException если конфигурация не удовлетворяет требованиям.
     */
    public function init()
    {
        if (!is_array($this->connection)) {
            throw new InvalidConfigException('User connection must be set as an array.');
        }
        parent::init();
    }

    /**
     * Возвращает соединение с базой данных для текущего пользователя.
     *
     * @return Connection соединение с базой данных.
     */
    public function getDb(): Connection
    {
        $dbId = $this->getDbId();
        if (!Yii::$app->has($dbId)) {
            Yii::$app->set($dbId, $this->buildConnection());
        }
        return Yii::$app->get($dbId);
    }

    /**
     * Переключает активный идентификатор пользователя.
     *
     * @param int|null $id идентификатор пользователя.
     */
    public function switchId($id): void
    {
        $this->activeId = $id;
    }

    /**
     * Возвращает идентификатор базы данных на основе шаблона компонента.
     *
     * @return string идентификатор базы данных.
     */
    private function getDbId(): string
    {
        return $this->replacePlaceholder($this->component);
    }

    /**
     * Создает соединение с базой данных на основе конфигурации.
     *
     * @return array конфигурация соединения с базой данных.
     */
    private function buildConnection(): array
    {
        return array_map([$this, 'replacePlaceholder'], $this->connection);
    }

    /**
     * Заменяет плейсхолдер '{id}' на активный идентификатор пользователя.
     *
     * @param string $value строка для замены.
     * @return string результат замены.
     */
    private function replacePlaceholder(string $value): string
    {
        return str_replace('{id}', $this->getActiveId(), $value);
    }

    /**
     * Возвращает активный идентификатор пользователя.
     * Если идентификатор не установлен, использует значение по умолчанию.
     *
     * @return int текущий активный идентификатор пользователя.
     */
    private function getActiveId(): int
    {
        if ($this->activeId === null) {
            $user = Yii::$app->get('user', false);
            if ($user && !$user->getIsGuest()) {
                $this->activeId = $user->getId();
            } else {
                $this->activeId = $this->defaultId;
            }
        }
        return $this->activeId;
    }
}
