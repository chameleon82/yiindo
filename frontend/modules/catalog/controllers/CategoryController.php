<?php

namespace app\modules\catalog\controllers;

use Yii;
use yii\web\Controller;
use app\modules\catalog\models\Catalog;
use app\modules\catalog\models\CatalogCategories;
use app\modules\catalog\models\CatalogSearch;
use yii\data\ActiveDataProvider;

class CategoryController extends Controller
{
    public function actionIndex($slug)
    {
        $category = CatalogCategories::find()->where(['slug'=>$slug])->one();

        if (!$category) throw New \yii\web\NotFoundHttpException(\Yii::t('app','Page not found'));

        /*    $dataProvider = new \app\modules\catalog\components\CatalogDataProvider([
                'category_id' => $category->id,
                'pagination' => [
           //         'pageSize' => 1,
                ],
            ]);*/
        $cs = new CatalogSearch();

        // return $this->render('index',[ 'model' => $category, 'dataProvider' => $dataProvider ]);

        $searchModel = new CatalogSearch(['category_id' => $category->id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*  return $this->render('/position/index', [
              'category' => $category,
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
          ]);*/
        $options = [
            'category' => $category,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ];
        if (Yii::$app->request->isPjax)
            return $this->renderAjax('/position/index', $options);
        else
            return $this->render('/position/index', $options);

    }
}
