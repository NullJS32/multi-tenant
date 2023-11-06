<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\CustomerForm;

/* @var $this yii\web\View */
/* @var $model CustomerForm */

$this->title = 'Edit Customer';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['customers']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="customer-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="customer-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'email')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
