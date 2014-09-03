<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Cards $model
 */

$this->title = 'Изменение карты';
$this->params['breadcrumbs'][] = ['label' => 'Бонусные карты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Карта №'.$model->series.' '.$model->card_num, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cards-update">

    <h1><?= Html::encode($this->title) ?><small><?= ' (добавил "'. $model->creator->username .'")' ?></small></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
