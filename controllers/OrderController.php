<?php

namespace app\controllers;

use app\models\Customer;
use app\models\Order;
use app\models\OrderForm;
use Yii;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * OrderController отвечает за управление действиями, связанными с заказами.
 */
class OrderController extends Controller
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
     * Отображает список заказов.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->with('customer'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('order', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создает новый заказ.
     *
     * @param int|null $userId Идентификатор пользователя
     * @return string|Response
     */
    public function actionCreate(?int $userId = null): string|Response
    {
        if ($userId){
            Yii::$app->userDbLocator->switchId($userId);
        }
        $model = new OrderForm();

        // Получаем список клиентов
        $customers = Customer::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->create()) {
            Yii::$app->session->setFlash('success', 'Order has been created successfully.');
            return $this->redirect(['/order']);
        }

        return $this->render('create', [
            'model' => $model,
            'customers' => $customers, // Передаем список клиентов в представление
        ]);
    }

    /**
     * Редактирует существующий заказ.
     *
     * @param int $id Идентификатор заказа
     * @param int|null $userId Идентификатор пользователя
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionEdit(int $id, ?int $userId = null): string|Response
    {

        if ($userId){
            Yii::$app->userDbLocator->switchId($userId);
        }

        $order = Order::findOne($id);

        if (!$order) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model = new OrderForm();

        $model->edit($order);

        // Получаем список клиентов
        $customers = Customer::find()->all();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $order->customer_id = $model->customer_id;
            $order->order_date = $model->order_date;
            $order->total_amount = $model->total_amount;

            if ($order->save()) {
                Yii::$app->session->setFlash('success', 'Order has been updated successfully.');
                return $this->redirect(['/order']);
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'customers' => $customers, // Передаем список клиентов в представление
        ]);
    }

    /**
     * Удаляет заказ.
     *
     * @param int $id Идентификатор заказа
     * @param int|null $userId Идентификатор пользователя
     * @return Response
     */
    public function actionDelete(int $id, ?int $userId = null): Response
    {
        if ($userId) {
            Yii::$app->userDbLocator->switchId($userId);
        }

        $order = Order::findOne($id);

        if ($order) {
            $order->delete();
            Yii::$app->session->setFlash('success', 'Order has been deleted successfully.');
        }

        return $this->redirect(['/customer/index', 'userId' => $userId]);
    }

    /**
     * Отображает список заказов в режиме администратора.
     *
     * @param int $userId Идентификатор пользователя
     * @return string
     */
    public function actionAdminView(int $userId): string
    {
        // Переключаемся на базу данных пользователя
        Yii::$app->userDbLocator->switchId($userId);

        // Получаем заказы конкретного пользователя
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->with('customer'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('order', [
            'dataProvider' => $dataProvider,
            'userId' => $userId, // Передаем userId в представление
        ]);
    }
}
