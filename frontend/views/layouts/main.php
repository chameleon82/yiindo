<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Yiindo',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => \Yii::t('app','Home'), 'url' => ['/site/index']],
                ['label' => \Yii::t('app','Catalog'), 'url' => ['/catalog']],
                ['label' => \Yii::t('app','About'), 'url' => ['/site/about']],
                ['label' => \Yii::t('app','Contact'), 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => \Yii::t('app','Signup'), 'url' => ['/site/signup']];
                $menuItems[] = ['label' => \Yii::t('app','Login'), 'url' => ['/site/login']];
            } else {
            //    $menuItems[] = ['img'
                $menuItems[] = [
                    'label' => \Yii::t('app', 'Logout ({username})', [ 'username' => Yii::$app->user->identity->username ]),
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();


        ?>



        <div class="container">
            <?
            if (\Yii::$app->user->can('admin') && isset($this->params['adminmenu'])) {
                NavBar::begin([
                    'brandLabel' => 'Admin',
                    'options' => [
                        'class' => 'navbar',
                    ],
                ]);
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-default'],
                    'items' => $this->params['adminmenu'],
                ]);
                NavBar::end();
            }
            ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; Yiindo CMS <?= date('Y') ?></p>
        <p class="pull-right"><?= Html::a('RU', \yii\helpers\Url::current(['lang' => 'ru'])) .' | '. Html::a('EN', \yii\helpers\Url::current(['lang' => 'en'])) ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
