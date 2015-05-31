<?php

namespace app\modules\gallery\controllers;

use Yii;
use app\modules\gallery\models\GalleryAlbums;
use app\modules\gallery\models\GalleryAlbumsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Images;
use yii\web\UploadedFile;
use yii\helpers\Url;

/**
 * AlbumsController implements the CRUD actions for GalleryAlbums model.
 */
class AlbumsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionUploadimage($id) {
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $image= new Images();
            $image->parent_id = $model->id;
            $image->module = 'gallery';
            $image->file = UploadedFile::getInstance($image, 'file');
            $image->save();
        }
        return $this->redirect(['view','id' => $id]);
    }

    public function actionDeleteimage($id) {
        $image = Images::findOne(['id'=>$id,'module'=>'gallery']);
        if($image)
            $image->delete();
        if (Yii::$app->request->referrer)
            return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Lists all GalleryAlbums models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GalleryAlbumsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GalleryAlbums model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $uploadFile = new Images();
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'file' => $uploadFile,
        ]);
    }

    /**
     * Creates a new GalleryAlbums model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GalleryAlbums();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GalleryAlbums model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GalleryAlbums model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GalleryAlbums model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GalleryAlbums the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GalleryAlbums::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
