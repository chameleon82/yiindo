<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\CatalogAttributesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('catalog', 'Catalog Attributes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-attributes-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('catalog', 'Create Catalog Attribute'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'category.title',
            'code',
            'title',
            'measure',
            'ordering',
            'disabled',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
