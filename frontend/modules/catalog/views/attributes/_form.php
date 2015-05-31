<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\modules\catalog\models\CatalogAttributes;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\CatalogAttributes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-attributes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <?= $form->field($model, 'data_type')->dropDownList( CatalogAttributes::datatypeList())
    ?>

    <?= $form->field($model, 'filter_type')->dropDownList( CatalogAttributes::filterList() )
    ?>

    <?= $form->field($model, 'category_id')->dropDownList( ArrayHelper::map( \app\modules\catalog\models\CatalogCategoriesSearch::find()->all() ,'id','title')
        , ['prompt'=> ' -- '.\Yii::t('app','Select Category').' -- ','disabled'=> !$model->isNewRecord ]) ?>

    <?= $form->field($model, 'measure')->textInput() ?>

    <?= $form->field($model, 'ordering')->textInput() ?>

    <?= $form->field($model, 'disabled')->checkBox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
