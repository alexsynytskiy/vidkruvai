<?php

namespace app\components\helpers;

use app\models\definitions\DefEntityAchievement;
use app\models\Level;
use app\models\SiteUser;
use app\models\Team;
use yii\web\NotFoundHttpException;

/**
 * Class EntityHelper
 * @package app\components\helpers
 */
class EntityHelper
{
    /**
     * @param int $id
     * @param string $type
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public static function getEntityRenderingInfo($id, $type)
    {
        $viewId = $id;
        $entity = null;
        $preview = false;

        if ($type === DefEntityAchievement::ENTITY_USER) {
            $id = \Yii::$app->siteUser->id;
            $entity = \Yii::$app->siteUser->identity;
        }

        if ($type === DefEntityAchievement::ENTITY_TEAM) {
            $id = \Yii::$app->siteUser->identity->team->id;
            $entity = \Yii::$app->siteUser->identity->team;
        }

        if ($viewId && $entity->id !== $viewId) {
            $entity = $type === DefEntityAchievement::ENTITY_USER ? SiteUser::findIdentity($id) : Team::findOne($id);

            if (!$entity) {
                throw new NotFoundHttpException();
            }

            $preview = true;
        }


        $result['id'] = $entity->id;
        $result['entity'] = $entity;
        $result['preview'] = $preview;

        return $result;
    }

    /**
     * @param int $entityId
     * @param string $type
     *
     * @return array
     */
    public static function getEntityLevelInfo($type = null, $entityId = null)
    {
        $entity = null;
        if ($type === DefEntityAchievement::ENTITY_TEAM) {
            $entity = Team::findOne($entityId);
        }

        if ($type === DefEntityAchievement::ENTITY_USER) {
            $entity = SiteUser::findIdentity($entityId);
        }

        $result['currentLevel'] = $entity->level->num;
        $result['currentLevelExp'] = $entity->level_experience;
        $result['currentLevelGroup'] = $entity->level->levelgroup->name;
        $result['currentLevelGroupSlug'] = $entity->level->levelgroup->slug;
        $result['currentLevelMin'] = $entity->total_experience - $entity->level_experience;

        /** @var Level $nextLevel */
        $nextLevel = $entity->level->getNextLevel($type);

        if ($nextLevel) {
            $result['currentLevelMaxExp'] = $nextLevel->required_experience;
            $result['currentLevelMaxExpProfile'] = $nextLevel->required_experience -
                $entity->level->required_experience;
            $result['currentLevelAward'] = $nextLevel->awards;
        } else {
            $result['currentLevelMaxExp'] = $entity->total_experience;
            $result['currentLevelMaxExpProfile'] = $entity->total_experience;
            $result['currentLevelExp'] = $entity->total_experience;
            $result['currentLevelAward'] = [];
        }

        return $result;
    }

    /**
     * @param int $entityId
     * @param string $type
     *
     * @return array
     */
    public static function getEntityCredentials($type, $entityId)
    {
        $entity = null;
        if ($type === DefEntityAchievement::ENTITY_TEAM) {
            $entity = Team::findOne($entityId);
        }

        if ($type === DefEntityAchievement::ENTITY_USER) {
            $entity = SiteUser::findIdentity($entityId);
        }

        $result = [
            'userPhoto' => $entity->avatar,
            'userName' => $entity->name,
            'userLevel' => $entity->level->levelgroup->name,
            'userLevelNum' => $entity->level->num,
            'levelGroupSlug' => $entity->level->levelgroup->slug,
            'id' => $entity->id,
        ];

        return $result;
    }
}
