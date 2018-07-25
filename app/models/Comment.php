<?php

namespace app\models;

use app\components\AppMsg;
use app\components\CommentQuery;
use creocoder\nestedsets\NestedSetsBehavior;

/**
 * This is the model class for table "site_comment".
 *
 * @property integer $id
 * @property integer $channel_id
 * @property integer $site_user_id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $message
 * @property integer $rating
 * @property string $status
 * @property string $created_at
 *
 * @property CommentChannel $channel
 * @property SiteUser $siteUser
 */
class Comment extends \app\components\ActiveRecord
{
    /**
     * @var int
     */
    public $replyTo = null;
    /**
     * @var int
     */
    public $editCommentId = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site_comment';
    }

    /**
     * @return CommentQuery
     */
    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'depthAttribute' => 'depth',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['channel_id', 'site_user_id', 'message'], 'required'],
            [['channel_id', 'site_user_id'], 'integer'],
            [['message', 'status'], 'string'],
            [['created_at', 'replyTo', 'editCommentId'], 'safe'],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommentChannel::className(), 'targetAttribute' => ['channel_id' => 'id']],
            [['site_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteUser::className(), 'targetAttribute' => ['site_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => AppMsg::t('ID Канала'),
            'site_user_id' => AppMsg::t('ID Пользователя'),
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'message' => AppMsg::t('Комментарий'),
            'rating' => AppMsg::t('Рейтинг'),
            'status' => AppMsg::t('Статус'),
            'created_at' => AppMsg::t('Создан'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(CommentChannel::className(), ['id' => 'channel_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }
}
