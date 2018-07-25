<?php

namespace app\modules\comment\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "site_comment_vote".
 *
 * @property integer $site_user_id
 * @property integer $comment_id
 * @property integer $rating
 * @property string  $created_at
 */
class CommentVote extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_comment_vote';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['site_user_id', 'comment_id', 'rating'], 'required'],
            [['site_user_id', 'comment_id', 'rating'], 'integer'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'site_user_id' => 'Landing User ID',
            'comment_id'      => 'Comment ID',
            'rating'          => 'Rating',
            'created_at'      => 'Created At',
        ];
    }
}
