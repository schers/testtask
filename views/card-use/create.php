<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\CardUse $model
 */

$this->title = 'Добавление операции по карте: серия '.
    $model->card->series. ', №'.
    $model->card->card_num;
$this->params['breadcrumbs'][] = ['label' => 'Операции по картам', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-use-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
