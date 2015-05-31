<?php
namespace app\modules\catalog\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $title
 */
class Catalog extends ActiveRecord
{
    const STATUS_NOT_MODERATED  = 0;
    const STATUS_MODERATED = 1;
    const STATUS_IGNORED = 2;

    private $__attributes;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'content'], 'safe'],
            [['cost'], 'number'],
            [['title', 'cost'], 'required'],
    //        ['categoryAttributes', 'safe'],
            ['attributeValues', 'safe'],
            ['images','safe'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
     //   $scenarios['edit'] = ['categoryAttributes'];
        return $scenarios;
    }

    public static function tableName()
    {
        return '{{%catalog}}';
    }

 //   public function setCategoryAttributes($categoryAttributes)
 //   {
 //       $this->categoryAttributes = $categoryAttributes;
 //   }

    public function setAttributeValues($attributeValues)
    {
        $_att = [];
        foreach ($this->attributeValues as $key => $attribute) {
            $attribute->load( ['CatalogValues' => $attributeValues[$key] ]);
            $_att[] = $attribute;
        }
        $this->attributeValues = $_att;
    }
    /**
     * @inheritdoc
     */
    public function getCategory() {
        return $this->hasOne(CatalogCategories::className(), ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthor() {
        return $this->hasOne(\common\models\User::className(), ['id' => 'author_id']);
    }

    public function getImages() {
        return $this->hasMany(\common\models\Images::className(), ['parent_id' => 'id'])->where(['module' => 'catalog',]);//->sort();
    }

    /**
     * @inheritdoc
     */
    public function getCategoryAttributes() {
      //  return $this->hasMany(CatalogAttributes::className(), ['id' => 'attribute_id'])->viaTable(CatalogValues::tableName(),['catalog_id' => 'id']);
        return $this->hasMany(CatalogAttributes::className(), ['category_id' => 'category_id']);
    }

    /**
     * @inheritdoc
     */
    public function getAttributeValues()
    {
        $_att = [];
        $cas = CatalogAttributes::find()->where(['category_id' => $this->category_id])->orderBy('ordering')->all(); //   foreach
        foreach ($cas as $ca) {
            $att = CatalogValues::find()->where(['catalog_id' => $this->id, 'attribute_id' => $ca->id])->one();
            if (!$att) {$att = new CatalogValues(); $att->attribute_id = $ca->id;}
            $_att[] = $att;
        }
        //print_r($ca); die();
        return $_att;

        //  return $this->hasMany(CatalogValues::className(), ['catalog_id' => 'id'])->viaTable(CatalogAttributes::tableName(),['category_id' => 'category_id']);
    }
    //return $this->hasMany(CatalogValues::className(), ['catalog_id' => 'id']);

    public function getAllCategoryAttributes()
    {
        return $this->hasMany(CatalogAttributes::className(), ['category_id' => 'category_id']);
    }

    public function getValuesByAttribute($attribute_id) {
        return $this->hasMany(CatalogValues::className(),['catalog_id'=>'id']);//->where(['attribute_id' => $attribute_id]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function __get($attribute) {
        if (!isset($this->__attributes) && isset($this->id)) {
            $atts = $this->allCategoryAttributes;
            if (is_array($atts)) {
                foreach($atts as $att) {
                    $this->__attributes[$att->code] = CatalogValues::find()->where(['catalog_id'=>$this->id,'attribute_id'=>$att->id])->one();
                }
            }
        }
        if (isset($this->__attributes[$attribute])) return $this->__attributes[$attribute]->value;
        return parent::__get($attribute);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cost' => Yii::t('app', 'Cost'),
            'title' => Yii::t('catalog','Title'),
            'content' => Yii::t('app','Content'),
            'disabled' => Yii::t('app', 'Disabled'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null) {
        $transaction = $this::getDb()->beginTransaction();
        $_success = true;
        if (!$this->id) {$this->author_id = \Yii::$app->user->id;}
        $result = parent::save($runValidation,$attributeNames);
        if (!$result) $_success = false;
        foreach($this->attributeValues as $key => $attribute) {
            $attribute->catalog_id = $this->id;
            $result = $attribute->save($runValidation);
            if ($attribute->errors)
                $this->addError('attributeValues['.$key.'][value]', $attribute->errors['value'][0]);
            if (!$result) $_success = false;
        }
        !$_success ? $transaction->rollBack() : $transaction->commit();
        return $_success;
    }

}