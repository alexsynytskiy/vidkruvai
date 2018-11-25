<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "test_question_answer".
 *
 * @property integer $id
 * @property integer $team_id
 * @property integer $question_id
 * @property integer $answer_id
 * @property string $created_at
 * @property string $started_at
 * @property string $answered_at
 *
 * @property Question $question
 * @property Answer $answer
 * @property Team $team
 */
class TeamAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test_question_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['started_at', 'answered_at', 'created_at', 'question_id', 'team_id', 'answer_id'], 'safe'],
            [['question_id', 'team_id', 'answer_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'team_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswer()
    {
        return $this->hasOne(Answer::className(), ['id' => 'answer_id']);
    }
}
