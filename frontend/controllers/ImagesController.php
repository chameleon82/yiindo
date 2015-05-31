<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Images;
use yii\web\NotFoundHttpException;

/**
 * Images controller
 */
class ImagesController extends Controller
{

  public function actionDefault($mdl,$pid,$filename) {

      $image = new Images();
      if(!$res = $image->bySlug($mdl,$pid,$filename))
          throw new NotFoundHttpException( Yii::t('yii', 'Page not found.'));
      else
          return $res;
      ;
      return '';

      header('Content-type: image/' . $this->extensions[$info['extension']]);
      header('Content-Length: ' . filesize($info['dstPath']));
      readfile($info['dstPath']);
      exit();

  }
}
?>