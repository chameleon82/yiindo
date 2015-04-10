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
class CatalogAttributes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
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
            [['disabled'], 'required'],
            [['disabled',], 'integer'],
            [['title',], 'string', 'max' => 50]
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
            'disabled' => Yii::t('app', 'Disabled'),
        ];
    }

}
