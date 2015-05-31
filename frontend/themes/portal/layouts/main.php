<?php
 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\themes\portal\assets\AppAsset;
use frontend\widgets\Alert;
use yii\widgets\ActiveForm;
use frontend\models\Search;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
$this->registerCssFile('/css/site.css');

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
        //'<li><span class="glyphicon glyphicon-align-left" aria-hidden="true"></span></li>',
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => \Yii::t('app','Signup'), 'url' => ['/site/signup']];
        $menuItems[] = ['label' => \Yii::t('app','Login'), 'url' => ['/site/login']];
    } else {
        $menuItems[] = Html::tag('li',Html::a('', Url::to(['/profile']),['class'=>'glyphicon glyphicon-user','aria-hidden'=>'true','title' => \Yii::t('app', '({username})', [ 'username' => Yii::$app->user->identity->username ])]));
        $menuItems[] = Html::tag('li',Html::a('', Url::to(['/site/logout']),['data-method' => 'post','class'=>'glyphicon glyphicon-log-out','aria-hidden'=>'true','title' => \Yii::t('app', 'Logout ({username})', [ 'username' => Yii::$app->user->identity->username ])]));
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();


    ?>



    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?$form = ActiveForm::begin(['action'=>Url::to(['/search/index']),'method'=>'GET']); ?>
                <div class="form-group has-feedback">
                    <? echo Html::textInput('q','',['class'=>'form-control','placeholder' => \Yii::t('app','Search')]);
                    echo Html::tag('span','',['class'=>'glyphicon glyphicon-search form-control-feedback']);
                    ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>


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
        <p class="pull-right"><?= Html::a('RU', Url::current(['lang' => 'ru'])) .' | '. Html::a('EN', Url::current(['lang' => 'en'])) ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
