<?php

namespace app\modules\catalog\controllers;

use yii\web\Controller;
use app\modules\catalog\models\Catalog;

class FlexController extends Controller
{
    public function actionIndex()
    {
        $model = Catalog::find()->all();
        return $this->render('index',[ 'model' => $model ]);
    }
}
