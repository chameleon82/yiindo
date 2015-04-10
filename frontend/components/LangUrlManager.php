<?php
namespace frontend\components;

use yii\web\UrlManager;
use frontend\models\Lang;

class LangUrlManager extends UrlManager
{
    private $currLang;

    public $langs = [ 'ru' => 'ru-RU', 'en' => 'en-US'];

    /**
     * Parses the user request.
     *
     */
    public function parseRequest($request)
    {
        $session = \Yii::$app->session;
        if ($this->enablePrettyUrl) {
            $urlInfo = $request->getUrl();
            $urlComponents = explode('/',$urlInfo);
            if ( isset($urlComponents[1]) && in_array($urlComponents[1],array_keys($this->langs))) {
                $this->currLang = $urlComponents[1];
                $request->setUrl( substr($urlInfo,strlen($urlComponents[1])+1) );
            }
        }
        if (!$this->currLang) {
            $session_lang = $session->get('lang');
            $this->currLang = isset($session_lang) ? $session_lang : substr(\Yii::$app->language,0,2) ;
        }
        if ( $session->get('lang') != $this->currLang )
            $session->set('lang',$this->currLang);
        \Yii::$app->language = $this->langs[$this->currLang];
        return parent::parseRequest($request);
    }
    /**
     * Creates a URL using the given route and query parameters.
     * Add lang param before route
     */
    public function createUrl($params)
    {
        $params = array_merge(['lang'=> $this->currLang],(array)$params);
        $lang = $params['lang'];
        if ($this->enablePrettyUrl)
            unset($params['lang']);
        $url = parent::createUrl($params);
        return $this->enablePrettyUrl ? '/'.$lang . ($url === '/' ? '' : $url ) : $url;
    }

}