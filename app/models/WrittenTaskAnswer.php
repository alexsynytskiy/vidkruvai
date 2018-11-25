<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "written_task_answer".
 *
 * @property integer $id
 * @property string $text
 * @property integer $team_id
 * @property integer $task_id
 * @property string $created_at
 * @property string $params
 *
 * @property Task $task
 * @property Team $team
 *
 */
class WrittenTaskAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'written_task_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'task_id', 'team_id', 'text'], 'safe'],
            [['task_id', 'team_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => AppMsg::t('Поле відповіді (тут ви можете завантижити текст та додати фотографії. 
            Для відправлення є лише 1 спроба, тож будьте уважні!):'),
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
    public function getTask()
    {
        return $this->hasOne(WrittenTask::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeam()
    {
        return $this->hasOne(Team::className(), ['id' => 'team_id']);
    }
}
