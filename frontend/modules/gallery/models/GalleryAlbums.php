<?php

namespace app\modules\gallery\models;

use Yii;

/**
 * This is the model class for table "ndo_gallery_albums".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 */
class GalleryAlbums extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ndo_gallery_albums';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
        ];
    }

    public function getImages() {
        return $this->hasMany(\common\models\Images::className(), ['parent_id' => 'id'])->where(['module' => 'gallery',]);//->sort();
    }
}
