<?php
namespace frontend\models;

use Yii;
use yii\web\Request;
use yii\base\Model;

class Lang extends Model
{

    //Переменная, для хранения текущего объекта языка
    static $current = null;

//Получение текущего объекта языка
    static function getCurrent()
    {
        if( self::$current === null ){
            self::$current = self::getDefaultLang();
        }
        return self::$current;
    }

//Установка текущего объекта языка и локаль пользователя
    static function setCurrent($url = null)
    {
        $language = self::getLangByUrl($url);
        self::$current = ($language === null) ? self::getDefaultLang() : $language;
        Yii::$app->language = self::$current->local;
    }

//Получения объекта языка по умолчанию
    static function getDefaultLang()
    {
        //return Lang::find()->where('`default` = :default', [':default' => 1])->one();
        return 'en';
    }

//Получения объекта языка по буквенному идентификатору
    static function getLangByUrl($url = null)
    {
        if ($url === null) {
            return null;
        } else {
            //$language = Lang::find()->where('url = :url', [':url' => $url])->one();
            $language = 'ru';
            if ( $language === null ) {
                return null;
            }else{
                return $language;
            }
        }
    }

}