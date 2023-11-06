<?php

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\grid\GridView;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'username',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{orders}',
                'buttons' => [
                    'orders' => function ($url, $model, $key) {
                        return Html::a('Заказы', ['/order/admin-view', 'userId' => $model->id], [
                            'title' => 'Просмотр заказов',
                        ]);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{customers}',
                'buttons' => [
                    'customers' => function ($url, $model, $key) {
                        return Html::a('Заказчики', ['/customer/admin-view', 'userId' => $model->id], [
                            'title' => 'Просмотр заказчиков',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
