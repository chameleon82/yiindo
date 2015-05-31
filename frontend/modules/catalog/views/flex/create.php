<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\Catalog */

$this->title = Yii::t('app', 'Create Flex');

$this->params['catalog_category'] = $model->category;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Flex'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
