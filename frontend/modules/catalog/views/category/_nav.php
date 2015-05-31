<?php
use yii\helpers\Url;
use app\modules\catalog\models\CatalogCategories;

$this->beginBlock('catalogNav');
$ids=[]; $id = $catalog_category->id;
if ( $catalog_category->route)
    foreach ($catalog_category->route as $r)
        $ids[]=$r->id;

$tree = \app\helpers\ArrayHelper::tree( \yii\helpers\ArrayHelper::toArray(CatalogCategories::find()->orderBy('ordering')->all()),'id','parent_id','childs');

echo frontend\widgets\Tree::widget([
    'data' => $tree,
    'childAttribute' => 'childs',
    'groupTag'=>'div',
    'groupOptions'=>function($data,$level,$parent) use ($ids) {
            return ($level === 0) ? ['class'=>'list-group'] :
                (in_array($parent,$ids) ? ['class'=>'submenu panel-collapse collapse in'] : ['class'=>'submenu panel-collapse collapse']);
    },
    'elementTag'=>'a',
    'elementOptions'=> function($data,$level,$parent,$widgetId) use ($id){
        return [
            'href'=> count($data['childs']) ? '#'.$widgetId.'-navi-'.$data['id'] : Url::to(['category/index','slug'=>$data['slug']]),
            'data-toggle' => count($data['childs']) ? 'collapse':'',
            'class'=>'list-group-item'.($id == $data['id'] ? ' active':''),];
    },
    'value' => function($data,$level) {
        return ($level == 0 ? mb_strtoupper($data['title'],'UTF-8') : $data['title']).(count($data['childs']) != 0 ? ' '.\yii\helpers\Html::tag('b','',['class'=>'caret']) : '');
    }
]);


$this->endBlock();


