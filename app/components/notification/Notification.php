<?php

namespace app\components\notification;

use app\models\definitions\DefSiteUser;
use app\models\SiteUser;
use yii\base\Component;

/**
 * Class Notification
 * @package app\components\notification
 */
class Notification extends Component
{
    /**
     * @var NotificationWriter
     */
    protected $writer;

    /**
     * Notification constructor.
     *
     * @param NotificationWriter $writer
     * @param array $config
     */
    public function __construct(NotificationWriter $writer, array $config = [])
    {
        parent::__construct($config);

        $this->writer = $writer;
    }

    /**
     * Adds notification to certain user
     *
     * @param int|SiteUser $user - userId or User object
     * @param          $category
     * @param          $type
     * @param          $link
     * @param array $msgParams
     *
     * @return bool
     */
    public function addToUser($user, $category, $type, $link = null, array $msgParams = [])
    {
        try {
            return $this->writer->addToUser($user, $category, $type, $link, $msgParams);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Adds notification to certain user's groups
     *
     * @param              $category
     * @param              $type
     * @param string|array $groups
     * @param              $link
     * @param array $msgParams
     *
     * @return bool
     */
    public function addToGroup($groups, $category, $type, $link = null, array $msgParams = [])
    {
        try {
            return $this->writer->addToGroup($groups, $category, $type, $link, $msgParams);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Adds notification to all users
     *
     * @param       $category
     * @param       $type
     * @param       $link
     * @param array $msgParams
     *
     * @return bool
     */
    public function addToAll($category, $type, $link = null, array $msgParams = [])
    {
        try {
            return $this->writer->addToGroup(DefSiteUser::getListUserRoles('keys'),
                $category, $type, $link, $msgParams);
        } catch (\Exception $e) {
            return false;
        }
    }
}