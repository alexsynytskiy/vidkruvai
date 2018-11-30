<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefEntityAchievement;
use app\models\definitions\DefSiteUser;
use app\models\definitions\DefTask;
use app\models\definitions\DefTeamSiteUser;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\easyii\helpers\Mail;
use yii\easyii\models\Setting;
use yii\easyii\modules\tasks\models\TasksUser;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/**
 * Class TeamSiteUser
 * @property integer $id
 * @property string $name
 * @property string $avatar
 * @property string $status
 * @property integer $level_id
 * @property integer $level_experience
 * @property integer $total_experience
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TeamSiteUser[] $teamUsers
 * @property School $school
 * @property Level $level
 *
 * @package app\models
 */
class Team extends ActiveRecord
{
    public static function tableName()
    {
        return 'team';
    }

    /**
     * @return string
     */
    public static function answersTableName()
    {
        return 'test_question_answer';
    }

    public function rules()
    {
        return [
            [['level_id'], 'integer'],
            [['name', 'status'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва команди'),
            'avatar' => AppMsg::t('Зображення'),
            'created_at' => AppMsg::t('Створено'),
            'updated_at' => AppMsg::t('Оновлено'),
            'status' => AppMsg::t('Статус верифікації'),
            'level_id' => AppMsg::t('ID Рівня'),
            'level_experience' => AppMsg::t('Досвід на поточному рівні'),
            'total_experience' => AppMsg::t('Загальний досвід'),
        ];
    }

    /**
     * @param string $type
     * @return bool
     */
    public function mailAdmin($type = 'created')
    {
        $title = $type === 'created' ? 'Створено нову команду' : 'Команду змінено';
        $template = $type === 'created' ? '@app/mail/uk/admin_team_created' : '@app/mail/uk/admin_team_updated';

        $captain = $this->teamCaptain();

        return Mail::send(
            Setting::get('admin_email'),
            AppMsg::t($title),
            $template,
            [
                'captainName' => $captain->getFullName(),
                'link' => Url::to([
                    '/admin/teams/a/edit',
                    'id' => $this->primaryKey
                ], true),
            ]
        );
    }

    /**
     * @return array|null|SiteUser
     */
    public function teamCaptain() {
        return SiteUser::find()
            ->alias('su')
            ->innerJoin(TeamSiteUser::tableName() . ' tsu', 'tsu.site_user_id = su.id')
            ->where(['tsu.team_id' => $this->id, 'tsu.role' => DefTeamSiteUser::ROLE_CAPTAIN])
            ->one();
    }

    /**
     * @return array|null|School|string
     */
    public function getSchool() {
        return $this->teamCaptain() ? $this->teamCaptain()->school : '';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamUsers()
    {
        return $this->hasMany(TeamSiteUser::className(), ['team_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(Level::className(), ['id' => 'level_id'])
            ->andOnCondition(['entity_type' => DefEntityAchievement::ENTITY_TEAM]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['id' => 'answer_id'])
            ->viaTable(static::answersTableName(), ['team_id' => 'id']);
    }

    /**
     *
     */
    public function notifyTeamAboutTasks() {
        /** @var Task[] $tasks */
        $tasks = Task::find()
            ->where(['<=', 'starting_at', new Expression('NOW()')])
            ->andWhere(['>=', 'ending_at', new Expression('NOW()')])
            ->andWhere(['status' => DefTask::STATUS_ON])
            ->orderBy('starting_at, id DESC')
            ->all();

        foreach ($tasks as $task) {
            foreach ($this->teamUsers as $teamUser) {
                if($teamUser->user && $teamUser->user->status === DefSiteUser::STATUS_ACTIVE) {
                    $tasksUser = new TasksUser();
                    $tasksUser->site_user_id = $teamUser->site_user_id;
                    $tasksUser->task_id = $task->id;

                    if(!$tasksUser->save()) {
                        $this->flash('error', \Yii::t('easyii/tasks',
                            'Notifications not sent :' . VarDumper::export($tasksUser->getErrors())));
                    }
                }
            }
        }
    }

    /**
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeNotifiesTeamAboutTasks() {
        foreach ($this->teamUsers as $teamUser) {
            if($teamUser->user && $teamUser->user->status === DefSiteUser::STATUS_ACTIVE) {
                $notifications = TasksUser::findAll(['site_user_id' => $teamUser->site_user_id]);

                foreach ($notifications as $notification) {
                    $notification->delete();
                }
            }
        }
    }
}
