<?
use \yii\web\View;
use \yii\helpers\Html;
$this->registerJs("
    $(function() {
	$('ul.sf-menu').superfish({
	delay:       800,
	animation:   {opacity:'show',height:'show'},
	speed:       800,
	autoArrows:  false,
	dropShadows: false
	});

	$('.sf-menu > li').not('.current').hover(
		function(){ $(this).find('a').stop().animate({top:0},250)},
		function(){ $(this).find('a').stop().animate({top:600},400)}
	);
});
",View::POS_END);

$tree = \app\helpers\ArrayHelper::tree( \yii\helpers\ArrayHelper::toArray(\app\modules\catalog\models\CatalogCategories::find()->orderBy('ordering')->all()),'id','parent_id','childs');


?>

<div style="width:400px; height:600px; position: absolute; left:50%; margin-left: -405px; background: #F7F7F7;">
    <ul class="sf-menu">
        <li class="item-1">
            <a href="#"></a>
            <ul>
                <?
                foreach($tree as $value)
                    echo Html::tag('li',Html::a($value['title'],['/catalog/category/index','slug'=>$value['slug']]));
                ?>
            </ul>
        </li>
    </ul>

    <Div style="margin:auto; width: 248px; text-align: center; position: relative; top:45px; z-index:150; "><img src="http://xn----8sbnadlkslcbomkf6a0l.xn--p1ai/i/main-pr.png" width="248" height="54" border="0" alt="" /></Div>
    <Div style="margin:auto; width: 248px; text-align: center; position: relative; top:50px; z-index:1; ">
        <p><a href="#">Смотреть предложения продавцов</a></p>
    </div>
</Div>

<div style="width:400px; height:600px; position: absolute; left:50%; margin-left: 5px; background: #EDEDED;">
    <ul class="sf-menu">
        <li class="item-2">
            <a href="#"></a>
            <ul>
                <?
                foreach($tree as $value)
                    echo Html::tag('li',Html::a($value['title'],['/catalog/category/index','slug'=>$value['slug']]));
                ?>
            </ul>
        </li>
    </ul>
    <Div style="margin:auto; width: 248px; text-align: center; position: relative; top:45px;  z-index:150;"><img src="http://xn----8sbnadlkslcbomkf6a0l.xn--p1ai/i/main-ku.png" width="211" height="45" border="0" alt="" style="margin: auto;" />
    </Div>
    <Div style="margin:auto; width: 248px; text-align: center; position: relative; top:60px; z-index:1; ">
        <p><a href="#">Смотреть предложения покупателей</a></p>
    </div>
</div>
