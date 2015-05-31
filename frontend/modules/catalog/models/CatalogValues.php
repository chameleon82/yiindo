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
 */
class CatalogValues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return '{{%catalog_attribute_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catalog_id', 'attribute_id'], 'integer'],
            [['catalog_id','attribute_id'], 'required'],
            [['value'], 'safe'],
  //          [['value'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'catalog_id' => Yii::t('app', 'Позиция'),
            'attribute_id' => Yii::t('app', 'Атрибут'),
            'value' => Yii::t('app', 'Значение'),
        ];
    }

    public function getCategoryAttribute() {
        return $this->hasOne( CatalogAttributes::className(), array('id' => 'attribute_id'));
    }

    public function getPosition() {
        return $this->hasOne( Catalog::className(), array('id' => 'catalog_id'));
    }


    public function getFormatValue(){
        switch($this->categoryAttribute->data_type) {
            case CatalogAttributes::DATA_TYPE_STRING:
                return $this->value;
                break;
            case CatalogAttributes::DATA_TYPE_BOOLEAN:
                return $this->value==1?Yii::t('app','Yes'):Yii::t('app','No');
                break;
        }
        return $this->value;
    }
}
