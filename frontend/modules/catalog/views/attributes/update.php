<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\CatalogAttributes */

$this->title = \Yii::t('catalog', 'Update Catalog Attribute') . ': ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('catalog', 'Catalog Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = \Yii::t('app', 'Update');
?>
<div class="catalog-attributes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
