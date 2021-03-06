<?php
namespace app\modules\catalog\models;
use app\modules\catalog\models\Catalog;
use app\modules\catalog\models\CatalogAttributes;
use app\modules\catalog\models\CatalogValues;
use Yii;
use yii\db\ActiveQueryInterface;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\Connection;
use yii\db\QueryInterface;
use yii\di\Instance;

class CatalogDataProvider extends \yii\data\ActiveDataProvider
{
    public $category_id;

    public $categoryAttributes;

    /**
     * @inheritdoc
     */
    public function init()
    {

        if (!$this->category_id) {
            throw new InvalidConfigException('The "category_id" property must be set.');
        }

        $query = Catalog::find()->select(['id','title','cost','category_id'])->where(['category_id' => $this->category_id]);

        $this->categoryAttributes = CatalogAttributes::find()->where(['category_id' => $this->category_id])->orderBy('id')->all();

        $query = (new \yii\db\Query())->select( ['t.id','t.title','t.cost','t.category_id'])->from(['t' => $query]);

        foreach ($this->categoryAttributes as $x) {
            $query->addSelect([$x->code . '.value as '.$x->code]);
            $query->leftJoin([ $x->code => (new \yii\db\Query())->from(CatalogValues::tableName())], $x->code.'.catalog_id = t.id and '.$x->code.'.attribute_id = '.$x->id );

            $query->addSelect(['categoryAttribute['.$x->id.']' => 'categoryAttribute['.$x->id.'].value']);
            $query->leftJoin([ 'categoryAttribute['.$x->id.']' => (new \yii\db\Query())->from(CatalogValues::tableName())], ['categoryAttribute['.$x->id.'].catalog_id' => 't.id','categoryAttribute['.$x->id.'].attribute_id' => $x->id ]);
        }
        $query = (new \yii\db\Query())->select( ['t.*'])->from(['t' => $query]);

        $this->query = $query;
        parent::init();

    }

    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        $query = clone $this->query;
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->totalCount = $this->getTotalCount();
            $query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        return $query->all($this->db);

    }

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        $query = clone $this->query;
        return (int) $query->limit(-1)->offset(-1)->orderBy([])->count('*', $this->db);
    }

    /**
     * @inheritdoc
     */
    public function setSort($value)
    {
        parent::setSort($value);

        if (($sort = $this->getSort()) !== false && empty($sort->attributes)) {

            $model = new Catalog;
            foreach ($model->attributes() as $attribute) {
                $sort->attributes[$attribute] = [
                    'asc' => [$attribute => SORT_ASC],
                    'desc' => [$attribute => SORT_DESC],
                    'label' => $model->getAttributeLabel($attribute),
                ];
            }

            foreach ($this->categoryAttributes as $attribute) {
                $sort->attributes[$attribute->code] = [
                    'asc' => [$attribute->code => SORT_ASC],
                    'desc' => [$attribute->code => SORT_DESC],
                    'label' => $attribute->title,
                ];
            }
        }

    }
}
