<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use common\models\Images;
use \app\modules\catalog\models\CatalogAttributes;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\catalog\models\Catalog */

$this->title = $model->title;

foreach ( $model->category->route as $p)
    $this->params['breadcrumbs'][] = ['label' => $p->title, 'url' => ['category/index','slug'=>$p->slug]];
$this->params['breadcrumbs'][] = $model->title;

$this->render('/category/_nav',['catalog_category' => $model->category]);

?>
<div class="catalog-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php
        if (\Yii::$app->user->can('updatePost', ['post' => $model])) {

            $form = ActiveForm::begin(['action' => ['uploadimage','id'=>$model->id],'options' => ['enctype' => 'multipart/form-data']]);

            echo $form->field($file, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
            ]);
            ActiveForm::end();

            echo Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])
                . ' '
                . Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]);
        }
        ?>
    </p>

    <div class="row">
        <? $images = $model->images;

        echo newerton\fancybox\FancyBox::widget([
            'target' => 'a[rel=fancybox]',
            'helpers' => true,
            'mouse' => true,
            'config' => [
                'maxWidth' => '90%',
                'maxHeight' => '90%',
                'playSpeed' => 7000,
                'padding' => 0,
                'fitToView' => false,
                'width' => '70%',
                'height' => '70%',
                'autoSize' => false,
                'closeClick' => false,
                'openEffect' => 'elastic',
                'closeEffect' => 'elastic',
                'prevEffect' => 'elastic',
                'nextEffect' => 'elastic',
                'closeBtn' => false,
                'openOpacity' => true,
                'helpers' => [
                    'title' => ['type' => 'float'],
                    'buttons' => [],
                    'thumbs' => ['width' => 68, 'height' => 50],
                    'overlay' => [
                        'css' => [
                            'background' => 'rgba(0, 0, 0, 0.8)'
                        ]
                    ]
                ],
            ]
        ]);

        if ($images)
            foreach ($model->images as $image)
                //   echo Html::tag('div',Html::a(Html::img($image->thumb()),'#',['class'=>'thumbnail']),['class'=>'col-xs-6 col-md-3']);
            echo Html::tag('div',
                (\Yii::$app->user->can('updatePost', ['post' => $model]) ? Html::a(Html::tag('li','',['class' => 'glyphicon glyphicon-remove btn btn-sm btn-default','style'=>'position:absolute']) ,\yii\helpers\Url::to(['deleteimage','id'=>$image->id])) : '')
                .Html::a(Html::img($image->thumb(Images::SIZE_THUMB)), $image->thumb(Images::SIZE_FULL), ['class'=>'thumbnail','rel' => 'fancybox']),['class'=>'col-xs-6 col-md-3']);
        else
            //No images
        echo Html::tag('div',Html::a(Html::img('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjU5IiB5PSI5MCIgc3R5bGU9ImZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0O2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE3MXgxODA8L3RleHQ+PC9nPjwvc3ZnPg=='),'#',['class'=>'thumbnail']),['class'=>'col-xs-6 col-md-3']);
        //        echo Html::tag('div',Html::a(Html::img('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgdmlld0JveD0iMCAwIDE3MSAxODAiIHByZXNlcnZlQXNwZWN0UmF0aW89Im5vbmUiPjxkZWZzLz48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI0VFRUVFRSIvPjxnPjx0ZXh0IHg9IjU5IiB5PSI5MCIgc3R5bGU9ImZpbGw6I0FBQUFBQTtmb250LXdlaWdodDpib2xkO2ZvbnQtZmFtaWx5OkFyaWFsLCBIZWx2ZXRpY2EsIE9wZW4gU2Fucywgc2Fucy1zZXJpZiwgbW9ub3NwYWNlO2ZvbnQtc2l6ZToxMHB0O2RvbWluYW50LWJhc2VsaW5lOmNlbnRyYWwiPjE3MXgxODA8L3RleHQ+PC9nPjwvc3ZnPg=='),'#',['class'=>'thumbnail']),['class'=>'col-xs-6 col-md-3']);
        ?>
    </div>

    <p><?= Html::encode($model->content) ?></p>

    <?

    $attributes=['title'];
    foreach ($model->attributeValues as $value) {
        $attributes[] = [ 'label' => $value->categoryAttribute->title, 'value' => $value->formatValue ];
    }
    $attributes[] =  [ 'attribute' => 'cost', 'label' => 'Цена' , 'format' => ['currency','RUR']];

    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes
    ]);

    echo html::tag('p',\Yii::t('app','Author').' '. html::a($model->author->username, Url::to(['/profile/view','id'=>$model->author->id])) );


    ?>

</div>
