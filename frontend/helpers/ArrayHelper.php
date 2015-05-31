<?php

namespace app\helpers;

use Yii;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
    public static function tree($array, $id, $ref,$childEl = 'childs')
    {
      //  print_r($array);
        $result = static::index($array,'id');
    //    print_r($array);die('rt');
        $l = [];
        foreach ($result as &$element) {
            $l[$element[$id]] = &$element;
        }
        foreach ($l as $key => $val) {
            if (isset($l[$l[$key][$ref]])) {
                $parent = $l[$key][$ref];
                $l[$parent][$childEl][$l[$key][$id]] = &$l[$key];
                $l[$key]=static::remove($result,$key);
            }
        }
        unset($l);
        return $result;
    }
}
