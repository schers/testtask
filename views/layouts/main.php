<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\User;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
AppAsset::register($this);

$links = [
    ['label' => 'Главная', 'url' => ['/site/index']],
    //['label' => 'О проекте', 'url' => ['/site/about']],
    //['label' => 'Контакты', 'url' => ['/site/contact']],
];

if (!Yii::$app->user->isGuest){
    if (Yii::$app->user->can(User::ROLE_ADMIN) || Yii::$app->user->can(User::ROLE_SUPERADMIN)){
        $links = array_merge($links, [
                ['label' => 'Пользователи', 'url' => ['/user/index']],
                ['label' => 'Бонусные карты', 'url' => ['/cards/index']],
            ]);
    }
    $links[] = ['label' => 'Выход (' . Yii::$app->user->identity->username . ')',
                       'url' => ['/site/logout'],
                       'linkOptions' => ['data-method' => 'post']];
} else {
    $links[] = ['label' => 'Вход', 'url' => ['/site/login']];
}
//var_dump($links);exit;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Test Starcode',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $links,
            ]);
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Test Starcode <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
