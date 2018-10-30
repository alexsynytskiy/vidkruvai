<?php

namespace yii\easyii\modules\news\api;

use app\models\Comment;
use app\models\CommentChannel;
use Yii;
use yii\db\Query;
use yii\easyii\components\API;
use yii\easyii\components\ApiObject;
use yii\easyii\models\Photo;
use yii\easyii\models\SeoText;
use yii\easyii\models\Tag;
use yii\easyii\modules\news\models\News as NewsModel;
use yii\helpers\Url;

/**
 * Class NewsObject
 * @package yii\easyii\modules\news\api
 *
 * @property integer $id
 * @property string $slug
 * @property string $image
 * @property string $views
 * @property string $time
 * @property string $_photos
 * @property \yii\easyii\modules\news\models\News $model
 *
 * @property string $title
 * @property string $short
 * @property string $text
 * @property Tag[] $tags
 * @property string $category
 * @property SeoText $seo
 * @property string $data
 * @property PhotoObject[] $photos
 * @property string $editLink
 * @property integer commentsCount
 */
class NewsObject extends ApiObject
{
    /**
     * @var string
     */
    public $slug;
    public $image;
    public $views;
    public $time;

    private $_photos;

    /**
     * @return string
     */
    public function getTitle()
    {
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    /**
     * @return mixed|string
     */
    public function getShort()
    {
        return LIVE_EDIT ? API::liveEdit($this->model->short, $this->editLink) : $this->model->short;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return LIVE_EDIT ? API::liveEdit($this->model->text, $this->editLink, 'div') : $this->model->text;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->model->tags;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->model->category;
    }

    /**
     * @return mixed
     */
    public function getSeo()
    {
        return $this->model->seo;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->time);
    }

    /**
     * @return array|string
     */
    public function getPhotos()
    {
        if (!$this->_photos) {
            $this->_photos = [];

            foreach (Photo::find()
                         ->where([
                             'class' => NewsModel::className(),
                             'item_id' => $this->id
                         ])
                         ->sort()->all() as $model) {
                $this->_photos[] = new PhotoObject($model);
            }
        }
        return $this->_photos;
    }

    /**
     * @return string
     */
    public function getEditLink()
    {
        return Url::to(['/admin/news/a/edit/', 'id' => $this->id, 'language' => 'uk']);
    }

    /**
     * @return string
     */
    public function getCommentsCount()
    {
        return Comment::find()
            ->alias('c')
            ->innerJoin(CommentChannel::tableName() . ' cc', 'cc.id = c.channel_id')
            ->where(['cc.slug' => $this->model->slug])
            ->count();
    }

    /**
     * @return bool
     */
    public function getRead()
    {
        $rows = (new Query())
            ->select([])
            ->from('news_user_notification')
            ->where([
                'news_id' => $this->id,
                'site_user_id' => Yii::$app->siteUser->identity->id,
            ])
            ->one();

        if ($rows) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function setRead()
    {
        Yii::$app->db->createCommand()
            ->delete('news_user_notification', [
                'news_id' => $this->id,
                'site_user_id' => Yii::$app->siteUser->identity->id,
            ])
            ->execute();

        return true;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function setUnRead()
    {
        Yii::$app->db->createCommand()
            ->delete('news_user_notification', [
                'news_id' => $this->id,
            ])
            ->execute();

        return true;
    }
}