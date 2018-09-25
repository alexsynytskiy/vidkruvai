<?php

namespace app\models;

use app\components\AppMsg;
use app\models\definitions\DefUserAchievement;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_achievement".
 *
 * @property integer $id
 * @property integer $achievement_id
 * @property integer $site_user_id
 * @property integer $performed_steps
 * @property integer $done
 * @property integer $is_first
 * @property string $created_at
 * @property string $done_at
 *
 * @property Achievement $achievement
 * @property SiteUser $siteUser
 */
class UserAchievement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_achievement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['achievement_id', 'site_user_id'], 'required'],
            [['achievement_id', 'site_user_id', 'performed_steps', 'done', 'is_first'], 'integer'],
            [['created_at'], 'safe'],
            [['achievement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Achievement::className(),
                'targetAttribute' => ['achievement_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'achievement_id' => AppMsg::t('Достижение'),
            'site_user_id' => AppMsg::t('Пользователь'),
            'performed_steps' => AppMsg::t('Завершено шагов'),
            'is_first' => AppMsg::t('Первое достижение группы'),
            'done' => AppMsg::t('Завершено'),
            'done_at' => AppMsg::t('Дата завершения'),
            'created_at' => AppMsg::t('Создано'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAchievement()
    {
        return $this->hasOne(Achievement::className(), ['id' => 'achievement_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(SiteUser::className(), ['id' => 'site_user_id']);
    }

    /**
     * @param string $achievementClass
     * @param int $userId
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function checkUserAchievement($achievementClass, $userId)
    {
        $achievementTable = Achievement::tableName();
        $userAchievementTable = self::tableName();

        return static::find()
            ->innerJoin($achievementTable, $achievementTable . '.`id` = ' . $userAchievementTable . '.`achievement_id`')
            ->where([
                $userAchievementTable . '.`site_user_id`' => $userId,
                $achievementTable . '.`class_name`' => $achievementClass,
                $achievementTable . '.`archived`' => Achievement::IS_NOT_ARCHIVED,
            ])
            ->one();
    }

    /**
     * @param string $achievementClass
     * @param int $achievementId
     * @param int $userId
     *
     * @return UserAchievement|array|null|ActiveRecord
     * @throws \Exception
     */
    public static function getUserAchievementByID($achievementClass, $achievementId, $userId)
    {
        $achievementTable = Achievement::tableName();
        $userAchievementTable = self::tableName();

        $achievement = static::find()
            ->innerJoin($achievementTable, $achievementTable . '.`id` = ' .
                $userAchievementTable . '.`achievement_id`')
            ->where([
                $achievementTable . '.`id`' => $achievementId,
                $userAchievementTable . '.`site_user_id`' => $userId,
                $achievementTable . '.`archived`' => Achievement::IS_NOT_ARCHIVED,
            ])
            ->one();

        if (!$achievement) {
            $achievement = static::createUserAchievement($achievementClass, $achievementId, $userId);
        }

        return $achievement;
    }

    /**
     * @param string $achievementClass
     * @param int $achievementId
     * @param int $userId
     *
     * @return static
     * @throws \Exception
     */
    public static function createUserAchievement($achievementClass, $achievementId, $userId)
    {
        if (!$achievementId) {
            throw new \Exception("Achievement {$achievementClass} is not found.");
        }

        $achievement = new static;
        $achievement->site_user_id = $userId;
        $achievement->achievement_id = $achievementId;
        $achievement->save(false);

        $achievement->refresh();

        return $achievement;
    }

    /**
     * @param int $userId
     *
     * @return int|string
     */
    public static function getPassedAchievements($userId)
    {
        return static::find()->where(['site_user_id' => $userId, 'done' => DefUserAchievement::IS_DONE])->count();
    }
}
