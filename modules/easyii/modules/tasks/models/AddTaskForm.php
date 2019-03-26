<?php

namespace yii\easyii\modules\tasks\models;

use app\models\Award;
use app\models\CommentChannel;
use app\models\definitions\DefSiteUser;
use app\models\definitions\DefTask;
use app\models\definitions\DefTeam;
use app\models\Task;
use app\models\TasksUser;
use app\models\Team;
use app\models\TeamSiteUser;
use app\models\Test;
use app\models\WrittenTask;
use Yii;
use yii\base\Model;

/**
 * Class AddTaskForm
 * @package yii\easyii\modules\tasks\models
 */
class AddTaskForm extends Model
{
    /**
     * @var string
     */
    public $task_name;

    public $task_short;

    public $task_description;

    public $task_type;

    public $task_required;

    public $task_status;

    public $task_image;

    public $task_starting_at;

    public $task_ending_at;
    /**
     * @var array
     */
    public $task_award;

    /**
     * @var Task
     */
    private $_task;
    /**
     * @var WrittenTask|Test
     */
    private $_typed_task;
    /**
     * @var Award
     */
    private $_award;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_name', 'task_description', 'task_type', 'task_status', 'task_required', 'task_starting_at',
                'task_ending_at'], 'required'],
            [['task_name', 'task_description', 'task_short'], 'string'],
            [['task_name', 'task_description'], 'notEmptyTaskData'],
            [['task_award', 'task_starting_at', 'task_ending_at'], 'safe'],
        ];
    }

    public function notEmptyTaskData($attribute, $params, $validator)
    {
        if (!$this->task_name && !$this->task_description) {
            $this->addError($attribute, 'Неможливо створити завдання без назви та опису');
        }
    }

    /**
     * @param int $length
     * @return string
     */
    private function hashCreator($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'task_name' => 'Назва завдання',
            'task_description' => 'Опис завдання',
            'task_short' => 'Короткий опис',
            'task_type' => 'Тип завдання',
            'task_required' => 'Обовязкове завдання?',
            'task_status' => 'Статус',
            'task_image' => 'Зображення завдання',
            'task_starting_at' => 'Початок завдання',
            'task_ending_at' => 'Кінець завдання',
            'task_award' => 'Нагороди за завданн',
        ];
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->_task;
    }

    /**
     * @param Task $task
     */
    public function setTask($task)
    {
        $this->_task = $task;
        $this->_typed_task = $task->object;
        $this->_award = $task->awards;

        $this->task_name = $this->_typed_task->name;
        $this->task_short = $this->_typed_task->short;
        $this->task_description = $this->_typed_task->description;
        $this->task_type = $this->_task->item_type;
        $this->task_required = $this->_task->required;
        $this->task_status = $this->_task->status;
        $this->task_image = $this->_task->image;
        $this->task_starting_at = $this->_task->starting_at;
        $this->task_ending_at = $this->_task->ending_at;
        $this->task_award = $this->_task->awards;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function add()
    {
        ini_set('memory_limit', '-1');

        $typedTask = null;

        if ($this->task_type === DefTask::TYPE_WRITTEN) {
            $typedTask = new WrittenTask();
            $typedTask->name = $this->task_name;
            $typedTask->short = $this->task_short;
            $typedTask->description = $this->task_description;
        }

        if ($this->task_type === DefTask::TYPE_TEST) {
            $typedTask = new Test();
        }

        $this->_typed_task = $typedTask;

        if ($this->validate() && $typedTask->validate()) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $typedTask->save(false);

                $task = new Task();
                $task->hash = $this->hashCreator(10);
                $task->item_type = $this->task_type;
                $task->item_id = $typedTask->id;
                $task->status = $this->task_status;
                $task->required = $this->task_required;
                $task->image = $this->task_image;
                $task->starting_at = date('Y-m-d H:i:s', $this->task_starting_at);
                $task->ending_at = date('Y-m-d H:i:s', $this->task_ending_at);

                $commentsChannel = new CommentChannel();
                $commentsChannel->site_user_id = 9;
                $commentsChannel->slug = $task->hash;
                $commentsChannel->status = 'ACTIVE';

                if ($task->validate()) {
                    $task->save(false);
                    $commentsChannel->save(false);
                }

                if($this->task_award) {
                    foreach ($this->task_award as $award) {
                        Yii::$app->db->createCommand('INSERT INTO `task_award`(`task_id`, `award_id`) VALUES (:taskId, :awardId)')
                            ->bindValues([
                                ':taskId' => $task->id,
                                ':awardId' => $award,
                            ])->execute();
                    }
                }

                $teams = Team::find()
                    ->where(['status' => DefTeam::STATUS_ACTIVE])
                    ->all();

                $notifications = [];

                /** @var Team $team */
                foreach ($teams as $team) {
                    /** @var TeamSiteUser $teamUser */
                    foreach ($team->teamUsers as $teamUser) {
                        if ($teamUser->user && $teamUser->user->status === DefSiteUser::STATUS_ACTIVE) {
                            $notifications[] = ['site_user_id' => $teamUser->site_user_id, 'task_id' => $task->id];
                        }
                    }
                }

                Yii::$app->db
                    ->createCommand()
                    ->batchInsert(TasksUser::tableName(), ['site_user_id','task_id'], $notifications)
                    ->execute();

                $transaction->commit();
            } catch (\Throwable $e) {
                $transaction->rollBack();

                print_r($e->getMessage()); die;
            }

            return true;
        }

        $this->addErrors($this->_typed_task->getErrors());

        return false;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function update()
    {
        $task = Task::findOne($this->_task->id);

        if ($task) {
            if ($this->validate() && $task->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $writtenTask = $task->object;
                    $writtenTask->name = $this->task_name;
                    $writtenTask->short = $this->task_short;
                    $writtenTask->description = $this->task_description;

                    if($writtenTask->validate()) {
                        $writtenTask->update(false);
                    }

                    $transaction->commit();
                } catch (\Throwable $e) {
                    $transaction->rollBack();

                    print_r($e->getMessage()); die;
                }

                return true;
            }

            $this->addErrors($this->_task->getErrors());
        }

        return false;
    }
}
