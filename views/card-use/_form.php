<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zhuravljov\widgets\DateTimePicker;

/**
 * @var yii\web\View $this
 * @var app\models\CardUse $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="card-use-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date_use')->widget(DateTimePicker::className(), [
            'options' => ['class' => 'form-control', 'style' => 'width: 200px;'],
            'clientOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'language' => 'ru',
                'autoclose' => true,
            ],
            'clientEvents' => [],
        ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'style' => 'width: 500px;']) ?>

    <?= $form->field($model, 'cost')->textInput(['style' => 'width: 150px;']) ?>

    <?= $form->field($model, 'card_id', ['template' => "{input}"])->hiddenInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
