<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%catalog_categories}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $disabled
 * @property integer $parent_id
 */
class CatalogCategories extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%catalog_categories}}';
    }

    public function parentCategory()
    {
        return $this->hasOne(CatalogCategories::className(), ['id' => 'parent_id']);
    }

    /**
     * @inheritdoc
     */
    public function getCategoryAttributes() {
            return $this->hasMany(CatalogAttributes::className(), array('category_id' => 'id'));
    }

    /**
     * @inheritdoc
     */
    public function getCatalog() {
        return $this->hasMany( Catalog::className(), array('category_id' => 'id'));
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disabled'], 'required'],
            [['disabled', 'parent_id'], 'integer'],
            [['title', 'slug'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'slug' => Yii::t('app', 'Slug'),
            'disabled' => Yii::t('app', 'Disabled'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }

    public function getRoute() {
        $a = [$s = $this ];
        while ( $s ) ( $s = $s->parentCategory()->one() ) ? $a[] = $s : null;
        return array_reverse($a);
    }
}
