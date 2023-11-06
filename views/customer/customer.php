<?php

use app\assets\AppAsset;
use yii\grid\GridView;
use yii\helpers\Html;

AppAsset::register($this);

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $userId int */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = Yii::$app->user->can('admin');

$userId = $userId ?? null;

?>

<div class="customer-index">
    <h1><?= $this->title ?></h1>

    <?php if ($isAdmin) : ?>
        <p>
            <?= Html::a('Добавить заказчика', ['create', 'userId' => $userId], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?php
    $columns = [
        'id',
        'name',
        'email:email',
    ];

    if ($isAdmin) {
        $columns[] = [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{edit} {delete}',
            'buttons' => [
                'edit' => function ($url, $model, $key) use ($userId) {
                    $url = ['edit', 'id' => $model->id, 'userId' => $userId ?? null]; // Добавляем 'userId' к URL
                    return Html::a('<i class="fa fa-edit"></i>', $url, [
                        'title' => Yii::t('yii', 'Edit'),
                    ]);
                },
            ],
        ];
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => $columns,
    ]);
    ?>
</div>
