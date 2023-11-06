<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;

/**
 * UserController отвечает за управление пользователями в административной панели.
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображает список пользователей.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('user', ['dataProvider' => $dataProvider]);
    }
}
