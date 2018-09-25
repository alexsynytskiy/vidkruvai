<?php

namespace app\modules\comment\models;

use app\components\AppMsg;
use app\components\CommentQuery;
use app\models\CommentChannel;
use app\models\definitions\DefComment;
use app\models\SiteUser as User;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\Query;
use yii\helpers\ArrayHelper;

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
 * @property User $landingUser
 * @property CommentVote $userVote
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @var int
     */
    public $replyTo;
    /**
     * @var bool
     */
    public $isFirstReply;

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
            [['created_at'], 'safe'],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommentChannel::className(), 'targetAttribute' => ['channel_id' => 'id']],
            [['site_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['site_user_id' => 'id']],
            [['replyTo'], 'exist', 'targetClass' => self::className(), 'targetAttribute' => ['replyTo' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'site_user_id' => 'User ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'message' => AppMsg::t('Комментарий'),
            'status' => 'Стататус',
            'created_at' => AppMsg::t('Создано'),
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
    public function getLandingUser()
    {
        return $this->hasOne(User::className(), ['id' => 'site_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVote()
    {
        return $this->hasOne(CommentVote::className(), ['comment_id' => 'id']);
    }

    /**
     * @param int $offset
     * @param       $channelId
     * @param null $userId
     * @param array $tree
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getComments($offset = 0, $channelId, $userId = null, $tree = [])
    {
        $comments = static::find();

        $comments->alias('t');

        if ($userId !== null) {
            $comments->joinWith([
                'landingUser',
                'userVote AS userVote' => function ($query) use ($userId) {
                    /** @var \yii\db\ActiveQuery $query */
                    $query->andOnCondition(['userVote.site_user_id' => $userId]);
                },
            ])
                ->leftJoin(CommentVote::tableName() . ' cm', 't.site_user_id = cm.site_user_id and cm.comment_id = t.id');
        }

        $comments->where([
            't.channel_id' => $channelId,
        ]);

        $comments->andWhere([
            'or',
            [
                't.status' => DefComment::STATUS_ACTIVE,
            ],
            [
                't.site_user_id' => $userId,
                't.status' => [
                    DefComment::STATUS_MODERATOR,
                    DefComment::STATUS_DELETED,
                ],
            ],
        ]);

        if (count($tree) > 0) {
            $comments->andWhere(['t.tree' => $tree]);
        }

        /** @var static[] $result */
        $result = $comments->orderBy('t.tree DESC, t.lft')
            ->offset($offset)
            ->all();

        /**
         * @var string $key
         * @var Comment $comment
         */
        foreach ($result as $key => $comment) {
            if (!$comment->isRoot()) {
                $parent = $comment->parents()->one();
                $resultParent = array_filter(
                    $result,
                    function ($c) use ($parent) {
                        return $c->id == $parent->id;
                    }
                );

                if (!$resultParent) {
                    //Gets all its children IDs
                    $childrenIDs = ArrayHelper::getColumn($comment->children()->all(), 'id');

                    unset($result[$key]);

                    //Remove all children
                    foreach ($result as $innerKey => $innerComment) {
                        foreach ($childrenIDs as $childrenID) {
                            if ($innerComment->id == $childrenID && isset($result[$innerKey])) {
                                unset($result[$innerKey]);
                            }
                        }
                    }
                }
            }
        }

        //Reset array indexes
        $result = array_values($result);

        if (count($result) > 1) {
            for ($i = 1; $i < count($result); $i++) {
                if ($result[$i]->depth > $result[$i - 1]->depth) {
                    $result[$i]->isFirstReply = true;
                }
            }
        }

        return $result;
    }

    /**
     * @param      $channelId
     * @param      $limit
     * @param null $selectFrom
     * @param      $userId
     *
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getTopTrees($channelId, $limit, $selectFrom = null, $userId = null)
    {
        $query = new Query;

        $query->select('t.tree')
            ->from(static::tableName() . ' t')
            ->where(['t.channel_id' => $channelId]);

        if ($selectFrom) {
            $query->andWhere('t.tree < :t', [':t' => $selectFrom]);
        }

        $query->andWhere([
            'or',
            [
                't.status' => DefComment::STATUS_ACTIVE,
            ],
            [
                't.site_user_id' => $userId,
                't.status' => [
                    DefComment::STATUS_MODERATOR,
                    DefComment::STATUS_DELETED,
                ],
            ],
        ]);

        $result = $query->groupBy('t.tree')
            ->orderBy('t.tree DESC')
            ->limit($limit)
            ->all();

        return $result ? array_column($result, 'tree') : [];
    }

    /**
     * @param int $channelId
     *
     * @return int|string
     */
    public static function getCountComments($channelId)
    {
        return (new Query)
            ->from(static::tableName())
            ->where(['channel_id' => $channelId])
            ->count();
    }

    /**
     * @return int
     */
    public static function getCommentsOnModerationCounter()
    {
        $cnt = (new Query())
            ->select(['COUNT(*) cnt'])
            ->from('site_comment')
            ->where([
                'status' => DefComment::STATUS_MODERATOR,
            ])
            ->one();

        return (int)$cnt['cnt'];
    }
}
