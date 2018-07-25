<?php
namespace yii\easyii\models;

use Yii;
use yii\easyii\components\ActiveRecord;
use yii\easyii\validators\EscapeValidator;

/**
 * Class SeoText
 * @package yii\easyii\models
 *
 * @property string  $h1
 * @property string  $title
 * @property string  $keywords
 * @property string  $description
 *
 * @property boolean $isEmpty
 */
class SeoText extends ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_seotext';
    }

    public function rules()
    {
        return [
            [['h1', 'title', 'keywords', 'description'], 'trim'],
            [['h1', 'title', 'keywords', 'description'], 'string', 'max' => 255],
            [['h1', 'title', 'keywords', 'description'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'h1'          => 'Seo H1',
            'title'       => 'Seo Заголовок',
            'keywords'    => 'Seo Keywords',
            'description' => 'Seo Description',
        ];
    }

    public function isEmpty()
    {
        return (!$this->h1 && !$this->title && !$this->keywords && !$this->description);
    }
}