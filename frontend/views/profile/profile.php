<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = \Yii::t('app','Profile');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profile')];
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?>  <?= Html::a(\Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></h1>


    <div class="row">
        <div class="col-md-1">
            <?= Html::img($model->photo,['class'=>'img-thumbnail'])?>
        </div>
        <div class="col-md-11">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'first_name',
                    'last_name',
                    'email'
                ],
            ]) ?>
            </div>
    </div>
    <p>
<h1><?= \Yii::t('app','My Positions')?></h1>

    <div class="row">
        <?
        foreach ($positions as $position) {
?>
            <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
                <?=   Html::img( $position->images[0] ? $position->images[0]->thumb() : ''  )?>
                <div class="caption">
                    <h3><?=$position->title?></h3>
                    <p><?=$position->content?></p>
                    <p><?=Html::tag('span',\Yii::$app->formatter->asCurrency($position->cost),['class'=>'btn btn-default'])?>
                    <?=Html::a(\Yii::t('app','View'),['/catalog/position/view','id'=>$position->id],['class'=>'btn btn-primary','role'=>'button'])?></p>
                </div>
            </div>
        </div>
<?
        }
        ?>
    </div>
    </p>
</div>
