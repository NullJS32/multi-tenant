<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\LoginForm;
use app\models\SignupForm;
use yii\web\Response;

/**
 * SiteController отвечает за управление действиями на главной странице и авторизацией.
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Отображает главную страницу.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Отображает страницу входа.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Разлогинивает пользователя.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Обрабатывает регистрацию нового пользователя.
     *
     * @throws InvalidConfigException
     * @return string|Response
     */
    public function actionSignup(): string|Response
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            // Регистрация прошла успешно
            Yii::$app->session->setFlash('success', 'Registration was successful. Please log in.');

            // Найдем пользователя по зарегистрированному логину
            $user = User::findByUsername($model->username);

            if ($user) {
                $userId = $user->id;
                $userDatabaseName = "user_{$userId}";

                // Создаем базу данных для этого пользователя
                $createDatabaseSql = "CREATE DATABASE {$userDatabaseName}";
                Yii::$app->db->createCommand($createDatabaseSql)->execute();

                Yii::$app->userDbLocator->switchId($userId);

                Yii::$app->get('userDbLocator')->getDb()->createCommand("
                    CREATE TABLE `orders` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `customer_id` INT NOT NULL,
                        `order_date` DATE NOT NULL,
                        `total_amount` DECIMAL(10, 2) NOT NULL
                    )
                ")->execute();

                Yii::$app->get('userDbLocator')->getDb()->createCommand("
                    CREATE TABLE `customers` (
                        `id` INT AUTO_INCREMENT PRIMARY KEY,
                        `name` VARCHAR(255) NOT NULL,
                        `email` VARCHAR(255) NOT NULL,
                        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    )
                ")->execute();

                Yii::$app->userDbLocator->switchId(null);
            }

            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
}
