<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

use yii\bootstrap\Collapse;
use app\modules\Catalog\models\CatalogCategories;
use app\modules\Catalog\models\Catalog;

//  if (\Yii::$app->user->can('admin',[],false)) {
//      echo Html::a('Manage Flex', Url::to(['flex/index']));
//  }


$this->params['catalog_category'] = $model;

?>

<h1><?= $model->title ?></h1>

<?php

$attributes = [];

//$attributes[] =  [ 'attribute' => 'title', 'label' => 'Наиме'  ];
$attributes[] =  [   'attribute'=>'title'
                    ,'format'=>'raw'
                    ,'class' => 'yii\grid\DataColumn'
                    ,'label' => 'Наименование'
                    ,'value' => function($data) {
                          return Html::a(Html::encode($data['title']),['position/view','id' => (int)$data['id']]);
                     }
                 ];



foreach ($model->getCategoryAttributes()->all() as $categoryAttribute) {
    $attributes[] =
        [ 'attribute' => 'av_' . $categoryAttribute->id ,
            'label' => $categoryAttribute->title,
        ]
    ;
}

$attributes[] =  [ 'attribute' => 'cost', 'label' => 'Цена' , 'format' => ['currency','RUR']];
/*
//$mo = Catalog::find(1)->one();
//print_r($mo->attributeValues);
*/

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => $attributes
]);

//    echo Html::a('ghh', Url::to(['default/index', 'id' => 100]));



?>
