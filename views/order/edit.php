<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\OrderForm;

/**
 * @var $this yii\web\View
 * @var $model OrderForm
 * @var $customers array Список клиентов
 */

$this->title = 'Edit Order';
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="order-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="order-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'customer_id')->dropDownList(
            ArrayHelper::map($customers, 'id', 'name') // Список клиентов
        ) ?>

        <?= $form->field($model, 'order_date')->textInput() ?>
        <?= $form->field($model, 'total_amount')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

