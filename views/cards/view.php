<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\Cards $model
 */

$this->title = 'Карта №'.$model->series.' '.$model->card_num;
$this->params['breadcrumbs'][] = ['label' => 'Бонусные карты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cards-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'series',
            'card_num',
            'date_release',
            'date_end_activity',
            'sum' => [
                'label' => 'Сумма',
                'value' => $model->sum.' '.'руб.'
            ],
            'status' => [
                'label' => 'Статус',
                'value' => $model->getStatus(),
            ],
        ],
    ]) ?>

</div>
