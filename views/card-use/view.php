<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var app\models\CardUse $model
 */

$this->title = 'Операция по карте ('.$model->card->serialnum.')';
$this->params['breadcrumbs'][] = ['label' => 'Операции по картам', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-use-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы действительно хотите удалить данную запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'date_use',
            'description:ntext',
            'cost',
        ],
    ]) ?>

</div>
