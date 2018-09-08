<?php

namespace app\components\notification;

use app\models\Notification as NotificationModel;
use app\models\NotificationUser;
use app\components\AppMsg;
use app\components\helpers\LanguageHelper;
use app\models\definitions\DefNotificationUser;
use app\models\SiteUser;
use Yii;
use yii\base\Component;
use yii\db\Expression;

/**
 * Class NotificationWriter
 * @package acp\components\notification
 */
class NotificationWriter extends Component
{
    /**
     * @param      $category
     * @param      $type
     * @param      $title
     * @param      $message
     * @param null $link
     *
     * @return string
     * @throws \yii\db\Exception
     */
    protected function addNotification($category, $type, $title, $message, $link = null)
    {

        Yii::$app->db->createCommand()
            ->insert(NotificationModel::tableName(), [
                'category' => $category,
                'title' => $title,
                'message' => $message,
                'target_link' => $link,
                'type' => $type,
            ])
            ->execute();

        return Yii::$app->db->getLastInsertID();
    }

    /**
     * Add notification for certain user
     *
     * @param int|SiteUser $user - userId or SiteUser object
     * @param              $category
     * @param              $type
     * @param              $link
     * @param              $msgParams
     *
     * @return bool|int
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function addToUser($user, $category, $type, $link = null, array $msgParams = [])
    {
        if ($user instanceof SiteUser) {
            $userLang = $user->language;
            $userId = $user->id;
        } elseif (is_int($user)) {
            $userIdentity = SiteUser::findOne(['id' => $user]);

            if ($userIdentity) {
                $userLang = $userIdentity->language;
            } else {
                $userLang = LanguageHelper::LANG_UK;
            }

            $userId = $user;
        } else {
            throw new \Exception('Unsupported $user value: ' . print_r($user, true));
        }

        $title = AppMsg::t(NotificationSettings::getParam($type . '.title'), $msgParams, $userLang);
        $message = AppMsg::t(NotificationSettings::getParam($type . '.message'), $msgParams, $userLang);

        $notificationId = $this->addNotification($category, $type, $title, $message, $link);

        return Yii::$app->db->createCommand()
            ->insert(NotificationUser::tableName(), [
                'n_id' => $notificationId,
                'user_id' => $userId,
                'status' => DefNotificationUser::STATUS_NEW,
            ])
            ->execute();
    }

    /**
     * Add notification for certain group or groups
     *
     * @param string|array $groups
     * @param              $category
     * @param              $type
     * @param              $link
     * @param array $msgParams
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function addToGroup($groups, $category, $type, $link = null, array $msgParams = [])
    {
        $usersLanguage = [];

        $groups = (array)$groups;
        $usersLanguage[] = LanguageHelper::LANG_UK;

        //$userLanguages is the array of user's languages in form [uk, en]
        foreach ($usersLanguage as $language) {
            $title = AppMsg::t(NotificationSettings::getParam($type . '.title'), $msgParams, $language);
            $message = AppMsg::t(NotificationSettings::getParam($type . '.message'), $msgParams, $language);

            //Creates notification in certain language
            $notificationId = $this->addNotification($category, $type, $title, $message, $link);

            if ($notificationId) {
                //Add notifications to the group of users in their preferred language
                NotificationUser::addNotificationToGroups($groups, $notificationId, DefNotificationUser::STATUS_NEW, $language);
            }
        }

        return true;
    }
}