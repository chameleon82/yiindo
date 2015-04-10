<?php

namespace app\modules\catalog\controllers;

use yii\web\Controller;
use app\modules\catalog\models\Catalog;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $model = Catalog::find()->all();
        return $this->render('index',[ 'model' => $model ]);
    }
}
