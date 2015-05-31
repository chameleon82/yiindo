<?php

use \yii\helpers\Html;

$this->title = \Yii::t('app','Search Results');
$this->params['breadcrumbs'][] = ['label' => $this->title];

echo Html::beginTag('div',['class'=>'list-group']);
echo Html::tag('h1',$this->title);
if ($model->results):
foreach ($model->results as $result):
  //echo Html::tag('div',Html::tag('div',$result['url'],['class'=>'col-md-12']),['class'=>'row']);
    echo Html::a('<h4 class="list-group-item-heading">'.$result['text'].'</h4>
    <p class="list-group-item-text"></p>',$result['url']);
endforeach;
else:
  echo \Yii::t('app','Not Found');
endif;
echo Html::endTag('div');