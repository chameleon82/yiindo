<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * ContactForm is the model behind the contact form.
 */
class Search extends Model
{
    public $search;
    private $_results;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['q'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'q' => \Yii::t('app','Search'),
        ];
    }

    public function search($q) {

       $sub = explode(' ',$q);

       /* get from catalog */
       $rows_query = (new \yii\db\Query())
                ->select(["CONCAT('catalog') AS module", 'id' => 'id', 'text' => 'title'])
                ->from( ['t' => \app\modules\catalog\models\Catalog::tableName()]);
       foreach ($sub as $word):
           $rows_query->andWhere(['or',['like','title',$word],['like','content',$word],['exists',(new \yii\db\Query())->select(['id'])->from(['v' => \app\modules\catalog\models\CatalogValues::tableName()])->where('v.catalog_id = t.id')->andWhere(['like','value',$word]) ]]);
       endforeach;

        foreach ($rows_query->all() as $row):
            $this->_results[]=['url' => Url::to(['/catalog/position/view','id'=>$row['id']]),'text' => $row['text'] ];
        endforeach;

        return $this->_results;
    }

    public function getResults(){
        return $this->_results;
    }
}