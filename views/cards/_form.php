<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var app\models\Cards $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="cards-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'series')->textInput(['maxlength' => 6, 'style' => 'width: 250px;']) ?>

    <?= $form->field($model, 'card_num')->textInput(['maxlength' => 10, 'style' => 'width: 400px;']) ?>

    <?= $form->field($model, 'status')->dropDownList($model->getStatusArray(), ['style' => 'width: 200px;']) ?>

    <?= $form->field($model, 'date_release')->textInput(['style' => 'width: 200px;']) ?>

    <?= $form->field($model, 'date_end_activity')->textInput(['style' => 'width: 200px;']) ?>

    <?= $form->field($model, 'sum')->textInput(['style' => 'width: 100px;']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
