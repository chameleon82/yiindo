<?php
namespace frontend\controllers;

use frontend\models\Search;
use yii\web\Controller;

/*use Yii;
use common\models\LoginForm;
use common\models\Auth;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Search controller
 */
class SearchController extends Controller
{

    /**
     * @inheritdoc
     */

    public function actionIndex($q)
    {
        $model = new Search();
        $model->search($q);
        return $this->render('index',['model'=>$model]);
    }
}
