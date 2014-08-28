<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;

/**
 * @var yii\web\View $this
 * @var app\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 25, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 128, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 25, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => 25, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => 10, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'repassword')->passwordInput(['maxlength' => 10, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'role_id')->dropDownList(User::getRoleArray(), ['style' => 'width: 200px;']) ?>

    <?= $form->field($model, 'status_id')->dropDownList(User::getStatusArray(), ['style' => 'width: 200px;']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
