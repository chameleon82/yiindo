<?php

namespace app\modules\catalog\models;

use Yii;

/**
 * This is the model class for table "{{%catalog_attributes}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property integer $disabled
 * @property integer $parent_id
 * @property string filter_type
 */
class CatalogAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const FILTER_TYPE_TEXT = 'TEXT';
    const FILTER_TYPE_CHECKBOX = 'CHECKBOX';
    const FILTER_TYPE_RANGE = 'RANGE';
    const FILTER_TYPE_BOOLEAN = 'BOOLEAN';

    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_BOOLEAN = 'boolean';

    public static function tableName()
    {
        return '{{%catalog_category_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disabled','category_id','title','filter_type'], 'required'],
            [['disabled','category_id','ordering'], 'integer'],
            [['title','code'], 'string', 'max' => 50],
            [['code'],'match','pattern'=>'/^[a-z]\w*$/i'],
            [['filter_type','data_type',],'string', 'max' => 25],
            [['measure'],'string','max'=>10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCategory() {
        return $this->hasOne(CatalogCategories::className(), array('id' => 'category_id'));
    }

    public static function filterList() {
        return [ self::FILTER_TYPE_TEXT => \Yii::t('catalog','Text')
               , self::FILTER_TYPE_CHECKBOX => \Yii::t('catalog','Checkbox')
               , self::FILTER_TYPE_RANGE => \Yii::t('catalog','Range')
               , self::FILTER_TYPE_BOOLEAN => \Yii::t('catalog','Boolean')
        ];
    }

    public static function datatypeList() {
        return [ self::DATA_TYPE_BOOLEAN => \Yii::t('catalog','Boolean')
               , self::DATA_TYPE_STRING => \Yii::t('catalog','String')
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
            'code' => Yii::t('app', 'Code'),
            'data_type' => Yii::t('app','Type'),
            'filter_type' => Yii::t('app','Filter Type'),
            'disabled' => Yii::t('app', 'Disabled'),
            'measure' => Yii::t('app','Measure'),
            'ordering' => Yii::t('app','Ordering'),
            'category_id' => \app\modules\catalog\Module::t('catalog','Category'),
        ];
    }

}
