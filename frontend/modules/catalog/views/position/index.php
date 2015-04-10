<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\CatalogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Catalogs');
$this->params['breadcrumbs'][] = $this->title;
$this->params['catalog_category'] = $category;
?>
<div class="catalog-index">

    <h1><?= Html::encode($category->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Add position'), ['position/create','category_id'=>$category->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?

$attributes = [];

$attributes[] =  [   'attribute'=>'title'
    ,'format'=>'raw'
    ,'class' => 'yii\grid\DataColumn'
    ,'label' => 'Наименование'
    ,'value' => function($data) {
        return Html::a(Html::encode($data['title']),['position/view','id' => (int)$data['id']]);
    }
];



foreach ($category->getCategoryAttributes()->all() as $categoryAttribute) {
    $attributes[] =
        [ 'attribute' => 'av_' . $categoryAttribute->id ,
            'label' => $categoryAttribute->title,
        ]
    ;
}

$attributes[] =  [ 'attribute' => 'cost', 'label' => 'Цена' , 'format' => ['currency','RUR']];
//$attributes[] =  ['class' => 'yii\grid\ActionColumn'];

    ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $attributes,
    ]); ?>

</div>
