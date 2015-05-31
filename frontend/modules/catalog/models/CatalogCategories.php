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
        return $this->hasOne(self::className(), ['id' => 'parent_id'])->from(['parent'=>self::tableName()]);
    }

    public function childCategories() {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->from(['parent'=>self::tableName()]);
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
            [['title', 'slug','disabled'], 'required'],
            [['disabled', 'parent_id','ordering'], 'integer'],
            [['title', 'slug','code'], 'string', 'max' => 50],
            [['code'],'match','pattern'=>'/^[a-z]\w*$/i'],
            [['slug'],'unique']
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
            'code' => Yii::t('app','Code'),
            'slug' => Yii::t('app', 'Slug'),
            'ordering' => Yii::t('app', 'Ordering'),
            'disabled' => Yii::t('app', 'Disabled'),
            'parent_id' => Yii::t('app', 'Parent'),
        ];
    }

    public function getRoute() {
        $a = [$s = $this ];
        while ( $s ) ( $s = $s->parentCategory()->one() ) ? $a[] = $s : null;
        return array_reverse($a);
    }
}
