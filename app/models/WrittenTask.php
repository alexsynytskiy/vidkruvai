<?php

namespace app\models;

use app\models\definitions\DefTask;

/**
 * This is the model class for table "written_task".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 *
 * @property WrittenTaskAnswer[] $answers
 * @property Task $task
 */
class WrittenTask extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'written_task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
            [['name', 'description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'description' => 'Завдання',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasOne(WrittenTaskAnswer::className(), ['task_id' => 'id']);
    }

    /**
     * @param int $teamId
     * @return array|null|\yii\db\ActiveRecord
     */
    public function teamAnswered($teamId)
    {
        return WrittenTaskAnswer::find()
            ->where(['team_id' => $teamId, 'task_id' => $this->id])
            ->andWhere(['is not', 'text', null])
            ->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['item_id' => 'id'])
            ->andOnCondition(['item_type' => DefTask::TYPE_WRITTEN]);
    }
}
