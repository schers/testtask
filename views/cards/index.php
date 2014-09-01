<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\search\CardsSearch $searchModel
 * @var app\models\GenerateForm $generateFormModel
 */

$this->title = 'Бонусные карты';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/gen_form.js', [\yii\web\JqueryAsset::className()]);
$this->registerJsFile('/js/jquery.form.min.js', [\yii\web\JqueryAsset::className()]);
?>
<div class="cards-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить карту', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Генерировать карты', ['/#'], ['id' => 'open_generate', 'class' => 'btn btn-warning']) ?>
    </p>

    <div id="generate_form_container" style="display: none;">
        <?php $form = ActiveForm::begin([
                'id' => 'generate_form',
            ]); ?>
        <?= $form->field($generateFormModel, 'series')->textInput(['maxlength' => 6, 'style' => 'width: 250px;']) ?>
        <?= $form->field($generateFormModel, 'quantity')->textInput(['maxlength' => 4, 'style' => 'width: 250px;']) ?>
        <?= $form->field($generateFormModel, 'sum')->textInput(['maxlength' => 4, 'style' => 'width: 150px;']) ?>
        <?= $form->field($generateFormModel, 'period')->dropDownList(\app\models\GenerateForm::getPeriods(), ['style' => 'width: 150px;']) ?>
        <div class="form-group">
            <?= Html::submitButton('Генерировать', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'series',
            'card_num',
            'date_release',
            'date_end_activity',
            'sum' => [
                'attribute' => 'sum',
                'label' => 'Сумма (руб.)',
            ],
            'status' => [
                'attribute' => 'status',
                'value' => function(app\models\Cards $model){
                        return $model->getStatus();
                    },
                'filter' => app\models\Cards::getStatusArray(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
