<?php

namespace app\modules\gallery\controllers;

use Yii;
use app\modules\gallery\models\GalleryAlbums;
use app\modules\gallery\models\GalleryAlbumsSearch;
use yii\web\Controller;
use app\modules\gallery\models\Catalog;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new GalleryAlbumsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/gallery-albums/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
