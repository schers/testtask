<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var app\models\Cards $model
 *
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CardUseSearch $searchModel
 */

$this->title = 'Карта. серия:'.$model->series.' №'.$model->card_num;
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
            'creator.username',
            'last_date_use' => [
                'label' => 'Дата последнего использования',
                'value' => $model->getLastDateUse(),
            ],
        ],
    ]) ?>

    <h2><?= Html::encode('Операции по карте') ?></h2>

    <div class="card-use-index">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Добавить запись', ['card-use/create', 'cid' => $model->id], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'date_use',
                    'description:ntext',
                    'cost' => [
                        'attribute' => 'cost',
                        'label' => 'Сумма (руб.)',
                    ],

                    ['class' => 'yii\grid\ActionColumn', 'controller' => 'card-use'],
                ],
            ]); ?>

    </div>

</div>
