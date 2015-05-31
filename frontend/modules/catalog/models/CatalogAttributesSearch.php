<?php

namespace app\modules\catalog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\catalog\models\CatalogAttributes;

/**
 * CatalogAttributesSearch represents the model behind the search form about `app\modules\catalog\models\CatalogAttributes`.
 */
class CatalogAttributesSearch extends CatalogAttributes
{

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['category.title']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'disabled'], 'integer'],
            [['code', 'title','category.title','filter_type','measure','ordering'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CatalogAttributes::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'disabled' => $this->disabled,
        ]);

        $query->joinWith(['category' => function($query) { $query->from(['category' => '{{%catalog_categories}}']); }]);
        $dataProvider->sort->attributes['category.title'] = [
            'asc' => ['category.title' => SORT_ASC],
            'desc' => ['category.title' => SORT_DESC],
        ];

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'category.title', $this->getAttribute('category.title')])
            //->orderBy('ordering')
        ;


        return $dataProvider;
    }
}
