<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use Imagine\Image\Color;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Point;

/**
 * User model
 *
 * @property integer $id
 * @property string $title
 */
class Images extends ActiveRecord
{
    // To Do like  https://github.com/iutbay/yii2-imagecache/blob/master/ImageCache.php
    public $file;

    const SIZE_THUMB = 'thumb';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE = 'large';
    const SIZE_FULL = 'full';

    public $extensions = [
        'jpg' => 'jpeg',
        'jpeg' => 'jpeg',
        'png' => 'png',
        'gif' => 'gif',
        'bmp' => 'bmp',
    ];

    public $sizes = [
        self::SIZE_THUMB => [171, 180, 'C'],
        self::SIZE_MEDIUM => [300, 300,'P'],
        self::SIZE_LARGE => [600, 600,'P'],
        self::SIZE_FULL => [1280, 1280,'P'],
    ];

    public function bySlug($module,$pid,$slug,$loadmodel=false) {
        $this->module = $module;
        $this->parent_id = $pid;

        $regexp = '#^(.*)_('. '(?i)'.join('|', array_keys($this->sizes)) .')\.('. '(?i)'.join('|', array_keys($this->extensions)) .')$#';
        if (preg_match($regexp, $slug, $matches)) {
            $size = $matches[2]; $basename = $matches[1]; $extension = $matches[3];
        } else if (preg_match('#^(.*)\.('. '(?i)'.join('|', array_keys($this->extensions)) .')$#', $slug, $matches)) {
            $size = SIZE_FULL; $basename = $matches[1]; $extension = $matches[2];
        }
        $this->filename = $basename.'.'.$extension;
        return $this->thumb($size,false);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'created_at', 'updated_at'], 'integer'],
            [['title', 'description'], 'safe'],

            ['file','file'],
            [['filename','module'], 'required'],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return $scenarios;
    }

    public static function tableName()
    {
        return '{{%images}}';
    }

    /*
     * Get Image BaseDir;
     */
    public function getDir() {
        return Yii::getAlias('@static').'/'.$this->module.'/'. $this->parent_id .'/';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => Yii::t('app', 'File'),
        ];
    }


    /*
     * Get WebRoot directory
     */
    public function getWebroot() {
        return Yii::getAlias('@static') .DIRECTORY_SEPARATOR. 'web' .DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR. $this->parent_id .DIRECTORY_SEPARATOR;
    }

    public function thumb($size = self::SIZE_THUMB,$link = true) {
        $dstDir = $this->webroot;
        $extension = substr($this->filename,strpos($this->filename,'.')+1);
        $basename = substr($this->filename,0,strpos($this->filename,'.'));
        $srcPath = Yii::getAlias('@static').DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR.$this->parent_id.DIRECTORY_SEPARATOR.$basename.'.'.$extension;
        if (!is_file($srcPath))
            return false;

        $dstPath = $dstDir . $basename.'_'.$size.'.'.$extension;
        if (!is_file($dstPath) || (filemtime($srcPath) > filemtime($dstPath)) ) {
            $img = Image::getImagine()->open($srcPath);
            $img_size = $img->getSize();  $ratio = $img_size->getWidth()/$img_size->getHeight();
            $width = $this->sizes[$size][0];
            $height = $this->sizes[$size][1];
            if ( $this->sizes[$size][2] == 'P') {
                $height = round($width/$ratio);
            }
            $thumb = Image::thumbnail($srcPath, $width, $height, ManipulatorInterface::THUMBNAIL_OUTBOUND);
            \yii\helpers\BaseFileHelper::createDirectory($dstDir);
            $thumb->save($dstPath);
        }
        if (is_file($dstPath)) {
            if($link) {
              return Yii::getAlias('@webstatic') .'/' .$this->module.'/'.$this->parent_id.'/'.$basename.'_'.$size.'.'.$extension;
            } else {
                $thumb =  $thumb ? Image::getImagine()->open($dstPath) : $thumb;
                $thumb->show($this->extensions[$extension]);
            }
        }

       // return Yii::getAlias('@webstatic') .'/' .$this->module.'/'.$this->parent_id.'/'.$this->filename;
    }

    public function getFilenameBySize($size) {
        $extension = substr($this->filename,strpos($this->filename,'.')+1);
        $basename = substr($this->filename,0,strpos($this->filename,'.'));
        return $basename.'_'.$size.'.'.$extension;
    }

    public function beforeValidate(){
        if(!$this->filename)
            $this->filename = $this->file->baseName . '.' . $this->file->extension;
        return parent::beforeValidate();
    }

    public function beforeDelete() {
        $res = true;
        if (is_file($this->dir.$this->filename))
            $res = unlink($this->dir.$this->filename);
        foreach ($this->sizes as $size => $val):
            if (is_file($this->webroot.$this->getFilenameBySize($size)))
                unlink($this->webroot.$this->getFilenameBySize($size));
        endforeach;
        return $res;
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                \yii\helpers\BaseFileHelper::createDirectory($dir = Yii::getAlias('@static').'/'.$this->module.'/'. $this->parent_id .'/');
                $this->filename = (string)time(). '.' . $this->file->extension;
                return $this->file->saveAs( $dir . $this->filename);
            }
        } else {
            return false;
        }
    }
    public function save($runValidation = true, $attributeNames = NULL){

        parent::save($runValidation, $attributeNames);
    }

}