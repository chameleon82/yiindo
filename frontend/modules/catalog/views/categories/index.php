<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \app\modules\catalog\models\CatalogCategories;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\CatalogCategoriesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('catalog', 'Catalog Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-categories-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(\Yii::t('catalog', 'Create Catalog Categories'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?

    $this->render('/categories/_nav',['catalog_category' => $category]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'code',
            'title',
            'slug',
            'ordering',
            'disabled',
            // 'parent_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
