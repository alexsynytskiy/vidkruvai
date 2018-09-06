<?php

namespace yii\easyii\modules\news\models;

use app\models\SiteUser;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\easyii\components\ActiveRecord;

/**
 * Class NewsUser
 * @property integer news_id
 * @property integer site_user_id
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 *
 * @package yii\easyii\modules\news\models
 */
class NewsUser extends ActiveRecord
{
    public static function tableName()
    {
        return 'news_user_notification';
    }

    public function rules()
    {
        return [
            [['news_id', 'site_user_id'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'news_id']);
    }
}