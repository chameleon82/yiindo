<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\Collapse;
use app\modules\catalog\models\CatalogCategories;

$this->title = \Yii::t('app','Gallery');

$breadcrumbs = (array)$this->params['breadcrumbs'];
$this->params['breadcrumbs'] = [['label' => \Yii::t('app','Gallery'), 'url' => ['/gallery']]];
$this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'],$breadcrumbs);


$this->beginContent('@app/views/layouts/main.php');
?>
    <div class="catalog-default-index">
        <div class="row"><div class="col-md-3">

                <?php if (isset($this->blocks['catalogNav'])): ?>
                    <div class="row"><div class="col-md-12"><?= $this->blocks['catalogNav'] ?></div></div>
                <?
                endif;

                if (isset($this->blocks['filtersBlock'])): ?>
                    <div class="row"><div class="col-md-12"><div class="panel panel-default"><div class="panel-body"><?= $this->blocks['filtersBlock'] ?></div></div></div></div>
                <?    else: ?>
                <?    endif; ?>


            </div>
            <div class="col-md-9"><?


                echo $content;

                ?>        </div>
        </div>
    </div>
<?

$this->endContent();