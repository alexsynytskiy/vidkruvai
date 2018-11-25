<?php

namespace app\models;

use app\models\definitions\DefTask;

/**
 * This is the model class for table "test".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $completed_data
 *
 * @property Question[] $questions
 * @property TeamAnswer[] $teamAnswers
 * @property Task $task
 */
class Test extends \yii\db\ActiveRecord
{
    const MISSED = 'missed';
    const DISABLED = 'disabled';
    const ACTIVE = 'active';
    const ANSWERED = 'answered';

    public $active = self::DISABLED;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'test';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
            [['name', 'description', 'completed_data'], 'string'],
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
            'description' => 'Опис',
            'completed_data' => 'Інформація',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['group_id' => 'id']);
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getTeamAnswers()
    {
        return TeamAnswer::find()
            ->alias('qa')
            ->innerJoin(Question::tableName() . ' q', 'q.id = qa.question_id')
            ->innerJoin(self::tableName() . ' g', 'q.group_id = g.id')
            ->where([
                'qa.team_id' => \Yii::$app->siteUser->identity->team->id,
                'g.id' => $this->id,
            ])
            ->all();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['item_id' => 'id'])
            ->andOnCondition(['item_type' => DefTask::TYPE_TEST]);
    }

    /**
     * @return array
     */
    public static function getGroups()
    {
        $groups = self::find()->all();

        $result = [];
        /** @var Test $group */
        foreach ($groups as $group) {
            $result[$group->id] = $group->name;
        }

        return $result;
    }
}
