# Мульти-тенантное веб-приложение

## Установка и настройка

Выполните миграции для создания структуры базы данных:

```
yii migrate
```
Эта команда создаст мастер-базу данных, содержащую таблицы для управления ролями и пользователями.

В RBAC уже настроено, что user1 - администратор, а user2 - пользователь.


Создаем базы данных для тестовых пользователей и наполняем их данными:

```
yii seed
```

После выполнения этих команд можно тестировать приложение.

Данные для авторизации:

user1: 1234

user2: 1234
