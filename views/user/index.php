<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\UserSearch $searchModel
 */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            'email:email',
            'name',
            'surname',
            // 'password',
            // 'auth_key',
            'role' => [
                'attribute' => 'role_id',
                'value' => function(app\models\User $model){
                        return $model->getRole();
                    },
                'filter' => app\models\User::getRoleArray(),
            ],
            'status' => [
                'attribute' => 'status_id',
                'value' => function(app\models\User $model){
                        return $model->getStatus();
                    },
                'filter' => app\models\User::getStatusArray(),
            ],
            'create_time',
            //'update_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
