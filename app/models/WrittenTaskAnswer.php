<?php

namespace app\models;

use app\components\AppMsg;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "written_task_answer".
 *
 * @property integer $id
 * @property string $text
 * @property integer $team_id
 * @property integer $task_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $params
 *
 * @property WrittenTask $task
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
            [['created_at', 'task_id', 'team_id', 'text', 'updated_at'], 'safe'],
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
            'created_at' => AppMsg::t('Создано'),
            'updated_at' => AppMsg::t('Обновлено'),
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

    public static function getTasksList() {
        $tasks = (new Query)->from(self::tableName() . ' wt')->select(['wt.task_id id', 't.name'])
            ->innerJoin(WrittenTask::tableName() . ' t', 't.id = wt.task_id')
            ->orderBy('t.id ASC')->distinct()->all();

        return ArrayHelper::map($tasks, 'id', 'name');
    }
}
