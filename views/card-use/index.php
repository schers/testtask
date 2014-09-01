<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CardUseSearch $searchModel
 */

$this->title = 'Операции по картам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-use-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        На данной странице вы можете просмотреть список операций по всем картам.
        Так же доступно редактирование и просмотр деталей. Добавлять записи вы не можете.
        Для того чтобы добавить запись перейдите на страницу просмотра бонусной карты.
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'date_use',
            'description:ntext',
            'cost',
            'card.serialnum',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
