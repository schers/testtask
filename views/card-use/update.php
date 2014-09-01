<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\CardUse $model
 */

$this->title = 'Измениение операции по карте (серия '.
    $model->card->series. ', №'.
    $model->card->card_num.')';
$this->params['breadcrumbs'][] = ['label' => 'Операции по картам', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Операция по карте ('. $model->card->serialnum . ')', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="card-use-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
