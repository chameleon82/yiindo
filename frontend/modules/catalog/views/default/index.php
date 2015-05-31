<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Collapse;
use app\modules\Catalog\models\CatalogCategories;


//  if (\Yii::$app->user->can('admin',[],false)) {
//      echo Html::a('Manage Flex', Url::to(['flex/index']));
//  }

$this->render('/category/_nav');

?>

            <h1><?= \Yii::t('app','Catalog') ?></h1>
            <p>
            </p>

