<?php

namespace app\controllers;

use Yii;
use app\models\Customer;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\CustomerForm;
use yii\web\Response;

/**
 * CustomerController отвечает за управление действиями, связанными с клиентами.
 */
class CustomerController extends Controller
{
    /**
     * @return array Массив поведений контроллера
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['error', 'login'], // Действия, для которых фильтр не применяется
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Разрешаем доступ авторизованным пользователям
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображает список заказчиков.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Customer::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('customer', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создает нового заказчика.
     *
     * @param int|null $userId Идентификатор пользователя
     * @return string|Response
     */
    public function actionCreate(?int $userId = null): string|Response
    {
        if ($userId) {
            Yii::$app->userDbLocator->switchId($userId);
        }

        $model = new CustomerForm();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            Yii::$app->session->setFlash('success', 'Customer has been created successfully.');
            return $this->redirect(['/customer/index', 'userId' => $userId]);
        }

        return $this->render('create', ['model' => $model, 'userId' => $userId]);
    }

    /**
     * Редактирует существующего заказчика.
     *
     * @param int $id Идентификатор заказчика
     * @param int|null $userId Идентификатор пользователя
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionEdit(int $id, ?int $userId = null): string|Response
    {
        if ($userId) {
            Yii::$app->userDbLocator->switchId($userId);
        }

        $customer = Customer::findOne($id);

        if (!$customer) {
            throw new NotFoundHttpException('The requested customer does not exist.');
        }

        $model = new CustomerForm();
        $model->edit($customer);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $customer->name = $model->name;
            $customer->email = $model->email;

            if ($customer->save()) {
                Yii::$app->session->setFlash('success', 'Customer has been updated successfully.');
                return $this->redirect(['/customer/index', 'userId' => $userId]);
            }
        }

        return $this->render('edit', ['model' => $model, 'userId' => $userId]);
    }

    /**
     * Удаляет заказчика.
     *
     * @param int $id Идентификатор заказчика
     * @param int|null $userId Идентификатор пользователя
     * @return Response
     */
    public function actionDelete(int $id, ?int $userId = null): Response
    {
        if ($userId) {
            Yii::$app->userDbLocator->switchId($userId);
        }

        $customer = Customer::findOne($id);

        if ($customer) {
            $customer->delete();
            Yii::$app->session->setFlash('success', 'Customer has been deleted successfully.');
        }

        return $this->redirect(['/customer/index', 'userId' => $userId]);
    }


    /**
     * Отображает список клиентов в режиме администратора.
     *
     * @param int $userId Идентификатор пользователя
     * @return string
     */
    public function actionAdminView(int $userId): string
    {
        // Переключаемся на базу данных пользователя
        Yii::$app->userDbLocator->switchId($userId);

        // Получаем клиентов конкретного пользователя
        $dataProvider = new ActiveDataProvider([
            'query' => Customer::find(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('customer', [
            'dataProvider' => $dataProvider,
            'userId' => $userId, // Передаем userId в представление
        ]);
    }
}
