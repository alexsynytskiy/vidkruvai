<?php

namespace app\models;

use app\components\AppMsg;
use app\components\behaviors\AwardBehavior;
use app\models\definitions\DefTask;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $required
 * @property integer $item_id
 * @property string $item_type
 * @property string $hash
 * @property string $image
 * @property integer $status
 * @property string $starting_at
 * @property string $ending_at
 * @property string $created_at
 * @property string $updated_at
 * @property string $stateForTeam
 *
 * @property WrittenTask|Test $object
 * @property Award[] $awards
 */
class Task extends ActiveRecord
{
    const ITEMS_PER_PAGE = 10;

    public $stateForTeam = DefTask::DISABLED;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @return string
     */
    public static function junctionAwardTable()
    {
        return 'task_award';
    }

    /**
     * @return string
     */
    public static function junctionAwardAttribute()
    {
        return 'task_id';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            [
                'class' => AwardBehavior::className(),
                'junctionTable' => static::junctionAwardTable(),
                'entityAttribute' => static::junctionAwardAttribute(),
            ],
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'starting_at', 'ending_at', 'status', 'required'], 'safe'],
            [['hash'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => AppMsg::t('Назва'),
            'created_at' => AppMsg::t('Создано'),
            'updated_at' => AppMsg::t('Обновлено'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAwards()
    {
        return $this->hasMany(Award::className(), ['id' => 'award_id'])
            ->viaTable(static::junctionAwardTable(), [static::junctionAwardAttribute() => 'id']);
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getObject()
    {
        $objectClass = '';

        if ($this->item_type === DefTask::TYPE_TEST) {
            $objectClass = Test::find();
        } elseif ($this->item_type === DefTask::TYPE_WRITTEN) {
            $objectClass = WrittenTask::find();
        }

        return $objectClass->where([
            'id' => $this->item_id,
        ])->one();
    }

    /**
     * @return bool
     */
    public function getRead()
    {
        return (new Query)
            ->select([])
            ->from('tasks_user_notification')
            ->where([
                'task_id' => $this->id,
                'site_user_id' => \Yii::$app->siteUser->identity->id,
            ])
            ->exists();
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function setRead()
    {
        \Yii::$app->db->createCommand()
            ->delete('tasks_user_notification', [
                'task_id' => $this->id,
                'site_user_id' => \Yii::$app->siteUser->identity->id,
            ])
            ->execute();

        return true;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function setUnRead()
    {
        \Yii::$app->db->createCommand()
            ->delete('tasks_user_notification', [
                'task_id' => $this->id,
            ])
            ->execute();

        return true;
    }

    /**
     * @return int
     */
    public static function getUserTasksCounters()
    {
        $cnt = (new Query)
            ->select(['COUNT(*) cnt'])
            ->from('tasks_user_notification')
            ->innerJoin(static::tableName() . ' t', 'tasks_user_notification.task_id = t.id')
            ->where([
                'tasks_user_notification.site_user_id' => \Yii::$app->siteUser->identity->id,
            ])
            ->one();

        return (int)$cnt['cnt'];
    }

    /**
     * @param array $ids
     * @throws \yii\db\Exception
     */
    public static function readByIds(array $ids = [])
    {
        $uid = \Yii::$app->siteUser->identity->id;

        (new Query)
            ->createCommand()->delete('tasks_user_notification', [
                    'site_user_id' => $uid,
                    'task_id' => $ids,
                ]
            )->execute();
    }

    /**
     * @param array $ids
     * @throws \yii\db\Exception
     */
    public static function deleteByTasksIds(array $ids = [])
    {
        (new Query)
            ->createCommand()->delete('tasks_user_notification', [
                    'task_id' => $ids,
                ]
            )->execute();
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public static function readAll()
    {
        (new Query())
            ->createCommand()->delete('tasks_user_notification', [
                    'site_user_id' => \Yii::$app->siteUser->identity->id,
                ]
            )->execute();

        return true;
    }

    /**
     * @param int $taskId
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function getListAwards($taskId)
    {
        return ArrayHelper::getValue(
            static::find()
                ->with('awards')
                ->where(['id' => $taskId])
                ->one(),
            'awards',
            []
        );
    }
}
