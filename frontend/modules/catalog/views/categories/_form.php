<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\CatalogCategories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-categories-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'ordering') ?>

    <?= $form->field($model, 'disabled')->checkbox() ?>

    <?= $form->field($model, 'parent_id')->dropDownList( ArrayHelper::map( \app\modules\catalog\models\CatalogCategoriesSearch::find()->where(['<>','id',$model->id ? $model->id : 0])->all() ,'id','title')
                                                      , ['prompt'=> ' -- '.\Yii::t('app','Parent').' -- ']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
