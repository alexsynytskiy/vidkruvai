<?php

namespace app\models;

use app\components\AppMsg;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "site_comment_channel".
 *
 * @property integer   $id
 * @property string    $name
 * @property string    $slug
 * @property string    $description
 * @property string    $status
 * @property integer   $site_user_id
 *
 * @property Comment[] $landingsComments
 */
class CommentChannel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'site_comment_channel';
    }

    /**
     * @return array
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'slug' => [
                'class'        => SluggableBehavior::className(),
                'attribute'    => 'name',
                'immutable'    => true,
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique'],
            [['description'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id'          => 'ID',
            'name'        => AppMsg::t('Имя'),
            'slug'        => AppMsg::t('Slug'),
            'description' => AppMsg::t('Описание'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLandingsComments() {
        return $this->hasMany(Comment::className(), ['channel_id' => 'id']);
    }
}
