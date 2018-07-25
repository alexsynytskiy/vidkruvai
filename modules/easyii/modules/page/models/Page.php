<?php
namespace yii\easyii\modules\page\models;

use omgdef\multilingual\MultilingualBehavior;
use omgdef\multilingual\MultilingualQuery;
use Yii;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\components\helpers\LanguageHelper;
use \yii\easyii\components\ActiveRecord;

/**
 * Class Page
 * @package yii\easyii\modules\page\models
 *
 * @property integer $page_id
 * @property string  $slug
 * @property string  $title
 * @property string  $text
 */
class Page extends ActiveRecord
{
    public static function tableName()
    {
        return 'easyii_pages';
    }

    /**
     * @return MultilingualQuery
     */
    public static function find() {
        return new MultilingualQuery(get_called_class());
    }
    
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'multilingual' => [
                'class'           => MultilingualBehavior::className(),
                'languages'       => LanguageHelper::getLanguages(),
                'defaultLanguage' => Yii::$app->language,
                'langForeignKey'  => 'page_id',
                'tableName'       => 'pages_i18n',
                'attributes'      => [
                    'title', 'text'
                ],
            ],
            'seoBehavior' => SeoBehavior::className(),
        ]);
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            [['title', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }
}