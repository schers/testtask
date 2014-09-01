<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\widgets\DateTimePicker;

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

    <?= $form->field($model, 'date_release')->widget(DateTimePicker::className(), [
            'options' => ['class' => 'form-control', 'style' => 'width: 200px;'],
            'clientOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'language' => 'ru',
                'autoclose' => true,
            ],
            'clientEvents' => [],
        ]) ?>

    <?= $form->field($model, 'date_end_activity')->widget(DateTimePicker::className(), [
            'options' => ['class' => 'form-control', 'style' => 'width: 200px;'],
            'clientOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'language' => 'ru',
                'autoclose' => true,
            ],
            'clientEvents' => [],
        ]) ?>

    <?= $form->field($model, 'sum')->textInput(['style' => 'width: 100px;']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
