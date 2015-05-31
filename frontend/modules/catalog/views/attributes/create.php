<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\CatalogAttributes */

//$this->title = \app\modules\catalog\Module::t('catalog', 'Create Catalog Attribute');
$this->title = \Yii::t('catalog', 'Create Catalog Attribute');
//Yii::t(
$this->params['breadcrumbs'][] = ['label' => Yii::t('catalog', 'Catalog Attributes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-attributes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
