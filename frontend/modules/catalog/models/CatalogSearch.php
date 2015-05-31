<?php

namespace app\modules\catalog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\catalog\models\Catalog;
use app\modules\catalog\models\CatalogAttributes;

/**
 * CatalogSearch represents the model behind the search form about `app\modules\catalog\models\Catalog`.
 */
class CatalogSearch extends Catalog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'safe'],
            [['cost'],'safe' /*
                function ($attribute, $params) {
                    $this->addError($attribute, 'The token must contain letters or digits.');
            }*/
                ],
            ['attributeValues', 'safe'],
            ['images','safe'],
        ];
          //return parent::rules();
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

//    public function setAttributeValues($attributeValues) {
//        return parent::setAttributeValues($attributeValues);
//    }

 //  public function __get($attribute) {
    //   echo $attribute. ' ';
    //   print_r(parent::$this->category_id);
     //  return 'ok';
  //     $attr = parent::__get($attribute);
  //     if ($attr) return $attr;
  //     return 'any';
 //  }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $dataProvider = new CatalogDataProvider([
            'category_id' => $this->category_id,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);


       // if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
     //       return $dataProvider;
    //    }

        $dataProvider->query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $dataProvider->query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        $costRange = is_array($this->cost) ? $this->cost : explode(',',$this->cost);
        if(count($costRange)===2)
            if ($costRange[0] && $costRange[1])
                $dataProvider->query->andFilterWhere(['between','cost',(int)$costRange[0],(int)$costRange[1]]);
            elseif ($costRange[0] && !$costRange[1])
                $dataProvider->query->andFilterWhere(['>=','cost',(int)$costRange[0]]);
            elseif (!$costRange[0] && $costRange[1])
                $dataProvider->query->andFilterWhere(['<=','cost',(int)$costRange[1]]);

        foreach ($this->attributeValues as $key => $value) {
            if ($value->value) {
                switch($value->categoryAttribute->filter_type) {
                    case CatalogAttributes::FILTER_TYPE_CHECKBOX:
                        $dataProvider->query->andFilterWhere(['in', $value->categoryAttribute->code, $value->value]);
                        break;
                    case CatalogAttributes::FILTER_TYPE_RANGE:
                        $rangeValue = is_array($value->value) ? $value->value : explode(',',$value->value);
                        if(count($rangeValue)===2)
                            if ($rangeValue[0] && $rangeValue[1])
                               $dataProvider->query->andFilterWhere(['between',$value->categoryAttribute->code,(int)$rangeValue[0],(int)$rangeValue[1]]);
                            elseif ($rangeValue[0] && !$rangeValue[1])
                                $dataProvider->query->andFilterWhere(['>=',$value->categoryAttribute->code,(int)$rangeValue[0]]);
                            elseif (!$rangeValue[0] && $rangeValue[1])
                                $dataProvider->query->andFilterWhere(['<=',$value->categoryAttribute->code,(float)$rangeValue[1]]);
                        break;
                    default: //CatalogAttributes::FILTER_TYPE_TEXT
                        $dataProvider->query->andFilterWhere(['like', $value->categoryAttribute->code, $value->value]);
                }
            }

        }

        return $dataProvider;
    }

}
