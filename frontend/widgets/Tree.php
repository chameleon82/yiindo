<?php
namespace frontend\widgets;
use yii\base\Widget;

/**
 *
 */
class Tree extends \yii\base\Widget
{

    public $data = [];

    public $childAttribute = 'childs';

    public $groupTag = 'ul';

    public $groupOptions=[];

    public $elementTag = 'li';

    public $elementOptions=[];

    public $value;

    public function init()
    {
        parent::init();

    }

    public function run() {
        echo self::printTree($this->data);
    }

    private function printTree($array,$level = 0,$parent = 0) {
        if ($this->groupOptions instanceof \Closure) {
            $func = $this->groupOptions;
            $groupOptions = $func($array,$level,$parent,$this->id);
        } else {
            $groupOptions = $this->groupOptions;
        }
        $groupOptions = array_merge(['id'=>$this->id.'-navi-'.$parent],$groupOptions);

        $result = \yii\helpers\Html::beginTag($this->groupTag, $groupOptions);

        foreach ($array as $key => $val) {
            if ($this->elementOptions instanceof \Closure) {
                $func = $this->elementOptions;
                $elementOptions = $func($val,$level,$parent,$this->id);
            } else {
                $elementOptions = $this->elementOptions;
            }

            $result .= \yii\helpers\Html::beginTag($this->elementTag,$elementOptions);
            if ($this->value instanceof \Closure) {
                $value = $this->value;
                $result.= $value($val,$level);
            } else {
                $result.= $val['title'];
            }
            $result.= \yii\helpers\Html::endTag($this->elementTag);
            $result.= $val[$this->childAttribute] ? self::printTree($val[$this->childAttribute],$level+1,$val['id']) : '';
        }
        $result.=\yii\helpers\Html::endTag($this->groupTag);
        return $result;
    }

}
