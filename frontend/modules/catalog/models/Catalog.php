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
        $cas = CatalogAttributes::find()->where(['category_id' => $this->category_id])->orderBy('id')->all(); //   foreach
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

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cost' => Yii::t('app', 'Cost'),
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