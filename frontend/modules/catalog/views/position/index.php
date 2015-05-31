<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Dropdown;
use kartik\slider\Slider;
use kartik\select2\Select2;
use app\modules\catalog\models\Catalog;
use app\modules\catalog\models\CatalogAttributes;
use app\modules\catalog\models\CatalogValues;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\catalog\models\CatalogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Catalogs');

if (isset($category))
    foreach ( $category->route as $p)
        $this->params['breadcrumbs'][] = ['label' => $p->title, 'url' => ['category/index','slug'=>$p->slug]];
$this->params['breadcrumbs'][] = \Yii::t('app','All');

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
        ,'label' => \Yii::t('catalog','Title')
        ,'value' => function($data) {
            return Html::a(Html::encode($data['title']),['position/view','id' => (int)$data['id']]);
        }
    ];
    foreach ($category->getCategoryAttributes()->orderBy('ordering')->all() as $categoryAttribute):
        $attributes[] =
            [   'attribute' =>  $categoryAttribute->code ,
                'label' => $categoryAttribute->title,
                'value' => function($data) use ($categoryAttribute) {
                    switch($categoryAttribute->data_type) {
                        case CatalogAttributes::DATA_TYPE_BOOLEAN:
                            return $data[$categoryAttribute->code] == 1 ? \Yii::t('yii','Yes') : \Yii::t('yii','No');
                        default:
                            return $data[$categoryAttribute->code]. ($categoryAttribute->measure ? ' '.$categoryAttribute->measure : '');
                    }

                }
            ];
    endforeach;

    $attributes[] =  [ 'attribute' => 'cost',  'label' => \Yii::t('app','Cost'), 'format' => ['currency','RUR']];

 //   if (\Yii::$app->user->can('admin'))
   //     $attributes[] =  ['class' => 'yii\grid\ActionColumn'];

   // $this->render('/category/_nav',['catalog_category' => $category]);
    if ($category->childCategories()->count()):
        $this->render('/category/_nav',['catalog_category' => $category]);
    else:

        $this->beginBlock('filtersBlock');

        $form = \yii\bootstrap\ActiveForm::begin(['action'=>['/catalog/category/index','slug'=>$searchModel->category->slug],'method'=>'get','options'=>['class'=>'form-group-sm']]);

        echo Html::tag('h4',\Yii::t('app','Filters'));

        echo $form->field($searchModel,'title');

        foreach ($searchModel->attributeValues as $key => $value):
            switch($value->categoryAttribute->filter_type) {
                case CatalogAttributes::FILTER_TYPE_TEXT:
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value]')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\modules\catalog\models\CatalogValues::find()->filterWhere(['attribute_id'=>$value->attribute_id])->all(),'value','value'),
                        'options' => ['placeholder' => \Yii::t('app','Select a {field}',['field'=>$value->categoryAttribute->title])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label($value->categoryAttribute->title.($value->categoryAttribute->measure ? ', '.$value->categoryAttribute->measure: ''));
                    break;
                case CatalogAttributes::FILTER_TYPE_RANGE:
                    $max = (float)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->max('CEIL(CAST(value AS DECIMAL(16,4)))');
                    $min = (float)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->min('FLOOR(CAST(value AS DECIMAL(16,4)))');
                    echo '<div class="form-group">';
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value]',['template'=>'{beginLabel}{labelTitle}{endLabel}','options'=>['class'=>'list-inline']])->label($value->categoryAttribute->title. ($value->categoryAttribute->measure ? ', '.$value->categoryAttribute->measure: ''));
                    echo '<div class="row">';
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value][0]',['template'=>'{input}','options'=>['class'=>'list-inline col-md-6']])->textInput(['placeholder'=>\Yii::t('app','From').' '.$min]);
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value][1]',['template'=>'{input}','options'=>['class'=>'list-inline col-md-6']])->textInput(['placeholder'=>\Yii::t('app','To').' '.$max]);
                    echo '</div></div>';
                    break;
                /* case CatalogAttributes::FILTER_TYPE_RANGE:
                     $maxv = (float)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->max('CEIL(CAST(value AS DECIMAL(16,4)))');
                     $minv = (float)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->min('FLOOR(CAST(value AS DECIMAL(16,4)))');
                     $delta = $maxv - $minv;
                     $q = ($delta >= 0 ? strlen(floor($delta)) : -strlen(floor(1/$delta)));
                     $step = pow(10,$q -2);
                     $ticks = [ $minv ];
                     for ($x=1;$x<4;$x++) {
                         $ticks[] = round($delta / 4 * $x + $minv,-($q-2));
                     }
                     $ticks[] = $maxv;$ticks_labels=[];
                     foreach ($ticks as $k => $v)
                         $ticks_labels[$k] = ($value->categoryAttribute->measure) ? $v.' '.($value->categoryAttribute->measure) : $v;


                     $delta = (int)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->max('CEIL(CAST(value AS DECIMAL(16,4)))') -
                              (int)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->min('FLOOR(CAST(value AS DECIMAL(16,4)))')
                                ;
                     echo $form->field($searchModel, 'attributeValues['.$key.'][value]')->label($value->categoryAttribute->title)->widget(Slider::classname(), [
                         'sliderColor' => Slider::TYPE_INFO,
                         'pluginOptions'=>[
                             'range' => true,
                                'min' => (int)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->min('FLOOR(CAST(value AS DECIMAL(16,4)))'),
                                'max' => (int)CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->max('CEIL(CAST(value AS DECIMAL(16,4)))'),
                                'step' => pow(10,($delta >= 0 ? strlen(floor($delta)) : -strlen(floor(1/$delta))) -2),
                                'ticks' => $ticks,
                                'ticks_labels' => $ticks_labels,
                         ]
                     ]);
                     break;*/
                case CatalogAttributes::FILTER_TYPE_CHECKBOX:
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value]')->checkboxList(
                        ArrayHelper::map(CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->distinct('value')->all(),'value','value'),
                        [
                            'multiple' => true
                        ]
                    )->label($value->categoryAttribute->title.($value->categoryAttribute->measure ? ', '.$value->categoryAttribute->measure: ''));
                    break;
                case CatalogAttributes::FILTER_TYPE_BOOLEAN:
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value]')->checkbox(
                        //ArrayHelper::map(CatalogValues::find()->where(['attribute_id'=> $value->categoryAttribute->id])->distinct('value')->all(),'value','value'),
                    )->label($value->categoryAttribute->title.($value->categoryAttribute->measure ? ', '.$value->categoryAttribute->measure: ''));
                    break;
                default:
                    echo $form->field($searchModel, 'attributeValues['.$key.'][value]')->widget(Select2::classname(), [
                        'data' => \yii\helpers\ArrayHelper::map(\app\modules\catalog\models\CatalogValues::find()->filterWhere(['attribute_id'=>$value->attribute_id])->all(),'value','value'),
                        'options' => ['placeholder' => \Yii::t('app','Select a {field}',['field'=>$value->categoryAttribute->title])],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label($value->categoryAttribute->title.($value->categoryAttribute->measure ? ', '.$value->categoryAttribute->measure: ''));
            }
        endforeach;

        $delta = (int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->max('cost') - (int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->min('cost');
        $q = ($delta >= 0 ? strlen(floor($delta)) : -strlen(floor(1/$delta)));
        $step = pow(10,$q -2);
        $ticks = [ (float)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->min('cost')];
        for ($x=1;$x<4;$x++) {
             $ticks[] = round($delta / 4 * $x + (int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->min('cost'),-($q-2));
        }
        $ticks[] = (float)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->max('cost');
        foreach ($ticks as $key => $val) {
            $ticks_labels[$key] = $val.' р.';
        }

        echo '<div class="form-group">';
        echo $form->field($searchModel, 'cost',['template'=>'{beginLabel}{labelTitle}{endLabel}','options'=>['class'=>'list-inline']]);
        echo '<div class="row">';
        echo $form->field($searchModel, 'cost[0]',['template'=>'{input}','options'=>['class'=>'list-inline col-md-6']])->textInput(['placeholder'=>\Yii::t('app','From').' '.(int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->min('cost')]);
        echo $form->field($searchModel, 'cost[1]',['template'=>'{input}','options'=>['class'=>'list-inline col-md-6']])->textInput(['placeholder'=>\Yii::t('app','To').' '.(int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->max('cost')]);
        echo '</div></div>';

/*
        echo $form->field($searchModel, 'cost')->widget(Slider::classname(), [
            'sliderColor' => Slider::TYPE_INFO,
            'pluginOptions'=>[
                'range' => true,
                'min' => (int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->min('cost'),
                'max' => (int)$searchModel::find()->where(['category_id'=>$searchModel->category->id])->max('cost'),
                'step' => pow(10,($delta >= 0 ? strlen(floor($delta)) : -strlen(floor(1/$delta))) -2),
                'ticks' => $ticks,
                'ticks_labels' => $ticks_labels,
            ]
        ]);
*/
        echo Html::submitButton(\Yii::t('app','Search'),['class'=>'btn btn-primary btn-sm']);
        $form->end();

        $this->endBlock();

    endif;

    $this->registerCss(" .slider-tick-label { font-size:75% }");
    ?>

    <?php Pjax::begin(['id' => 'positions']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //    'filterModel' => $searchModel,
        'columns' => $attributes,
    ]); ?>
    <?php Pjax::end() ?>

</div>
