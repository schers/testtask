<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\Cards $model
 */

$this->title = 'Добавление карты';
$this->params['breadcrumbs'][] = ['label' => 'Бонусные карты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cards-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
