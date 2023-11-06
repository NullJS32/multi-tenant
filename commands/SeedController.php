<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\Customer;
use app\models\Order;

/**
 * Контроллер для наполнения базы данных тестовыми данными.
 */
class SeedController extends Controller
{
    /**
     * Запускает процесс наполнения базы данных тестовыми данными.
     */
    public function actionIndex(): void
    {
        $this->createUserDatabases();
        $this->seedUser1Data();
        $this->seedUser2Data();
    }

    /**
     * Создает базы данных для пользователей.
     */
    private function createUserDatabases(): void
    {
        // Создаем базы данных для пользователей 1 и 2
        $this->createUserDatabase(1);
        $this->createUserDatabase(2);
    }

    /**
     * Создает базу данных для указанного пользователя.
     *
     * @param int $userId Идентификатор пользователя
     */
    private function createUserDatabase(int $userId): void
    {
        $userDatabaseName = "user_{$userId}";

        // Создаем базу данных для этого пользователя
        $createDatabaseSql = "CREATE DATABASE {$userDatabaseName}";
        Yii::$app->db->createCommand($createDatabaseSql)->execute();

        Yii::$app->userDbLocator->switchId($userId);

        // Создаем таблицу `orders`
        Yii::$app->get('userDbLocator')->getDb()->createCommand("
            CREATE TABLE `orders` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `customer_id` INT NOT NULL,
                `order_date` DATE NOT NULL,
                `total_amount` DECIMAL(10, 2) NOT NULL
            )
        ")->execute();

        // Создаем таблицу `customers`
        Yii::$app->get('userDbLocator')->getDb()->createCommand("
            CREATE TABLE `customers` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(255) NOT NULL,
                `email` VARCHAR(255) NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ")->execute();
    }

    /**
     * Наполняет базу данных для пользователя 1 тестовыми данными.
     */
    private function seedUser1Data(): void
    {
        Yii::$app->userDbLocator->switchId(1); // Переключаемся на базу данных для user1

        // Создаем тестовых клиентов и заказы для user1
        for ($i = 1; $i <= 5; $i++) {
            $customer = new Customer();
            $customer->name = 'User1 Customer ' . $i;
            $customer->email = 'user1_customer' . $i . '@example.com';
            $customer->save();

            $order = new Order();
            $order->customer_id = $customer->id;
            $order->order_date = date('Y-m-d H:i:s');
            $order->total_amount = rand(50, 500);
            $order->save();
        }
    }

    /**
     * Наполняет базу данных для пользователя 2 тестовыми данными.
     */
    private function seedUser2Data(): void
    {
        Yii::$app->userDbLocator->switchId(2); // Переключаемся на базу данных для user2

        // Создаем тестовых клиентов и заказы для user2
        for ($i = 1; $i <= 5; $i++) {
            $customer = new Customer();
            $customer->name = 'User2 Customer ' . $i;
            $customer->email = 'user2_customer' . $i . '@example.com';
            $customer->save();

            $order = new Order();
            $order->customer_id = $customer->id;
            $order->order_date = date('Y-m-d H:i:s');
            $order->total_amount = rand(50, 500);
            $order->save();
        }
    }
}
