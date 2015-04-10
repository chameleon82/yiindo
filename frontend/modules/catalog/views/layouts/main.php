<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Collapse;
use app\modules\Catalog\models\CatalogCategories;

$this->title = 'YiiNDO';
$this->params['adminmenu'][] = ['label' => \app\modules\catalog\Module::t('catalog','Manage Flex'), 'url' => ['flex/index']];

$breadcrumbs = (array)$this->params['breadcrumbs'];
$this->params['breadcrumbs'] = [['label' => \Yii::t('app','Catalog'), 'url' => ['/catalog']]];

if (isset($this->params['catalog_category']))
    foreach ( $this->params['catalog_category']->route as $p)
        $this->params['breadcrumbs'][] = ['label' => $p->title, 'url' => ['category/index','slug'=>$p->slug]];

$this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'],$breadcrumbs);


$this->beginContent('@app/views/layouts/main.php');
?>
<div class="catalog-default-index">
    <div class="row"><div class="col-md-3">
            <?

            $ids=[]; $id = $this->params['catalog_category']->id;
            if ( $this->params['catalog_category']->route)
                foreach ($this->params['catalog_category']->route as $r)
                    $ids[]=$r->id;
            function collapse_tree($parent,$ids,$id) {

                $out = ( $parent == null ? '<div class="list-group" id="navigation">' : '<div class="submenu panel-collapse collapse'.(in_array($parent,$ids)).'" id="navigation-'.$parent.'">' ) ;

                foreach (CatalogCategories::find()->where(['parent_id'=>$parent])->all() as $cat) {
                    $cnt = CatalogCategories::find()->where(['parent_id'=>$cat->id])->count();
                    $out.= $cnt ? ' <a data-toggle="collapse" href="#navigation-'.$cat->id.'" class="list-group-item'.( $cat->id == $id ? ' active':"").'">'.$cat->title.' <b class="caret"></b></a>'
                        : ' <a href="'.Url::to(['category/index','slug'=>$cat->slug]).'" class="list-group-item'.( $cat->id == $id ? ' active':"").'">'.$cat->title.'</a>';
                    $out.= collapse_tree($cat->id,$ids,$id);
                }
                $out.='</div>';
                return $out;
            }

            echo collapse_tree(null,$ids,$id);

            ?>        </div>
        <div class="col-md-9"><?


            echo $content;

            ?>        </div>
    </div>
    </div>
<?

$this->endContent();