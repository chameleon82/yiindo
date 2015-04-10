<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\catalog\models\CatalogAttributes;
use app\modules\catalog\models\CatalogValues;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\Catalog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="catalog-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 50]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

<?     /*foreach ( $model->allCategoryAttributes as $key => $attribute) {
          $value = $model->getAttributeValues()->where(['attribute_id' => $attribute->id])->one();
          if(!$value) { $value = new CatalogValues(); $value->attribute_id =$attribute->id; }
          echo $form->field($model, 'attributeValues['.$attribute->id.'][CatalogValues][value]')->textInput(['value'=>$value->value])->label($attribute->title);
       }*/
      foreach ($model->attributeValues as $key => $value) {
        //  print_r($value->errors);
          echo $form->field($model, 'attributeValues['.$key.'][value]')->textInput(['value'=>$value->value])->label($value->categoryAttribute->title);
      }

    ?>

    <?= $form->field($model, 'cost')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
