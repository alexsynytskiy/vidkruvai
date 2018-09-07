<?php

namespace acp\models;

use app\components\LogHelper;
use acp\models\definitions\DefNotification;
use acp\models\definitions\DefNotificationUser;
use Yii;
use acp\components\ActiveRecord;
use acp\components\AcpMsg;

/**
 * This is the model class for table "notification_user".
 *
 * @property string       $n_id
 * @property string       $user_id
 * @property string       $status
 *
 * @property Notification $notification
 * @property User         $user
 */
class NotificationUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'notification_user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['n_id', 'user_id', 'status'], 'required'],
            [['n_id', 'user_id'], 'integer'],
            [['status'], 'string'],
            [['n_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notification::class, 'targetAttribute' => ['n_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'n_id'    => AcpMsg::t('ID уведомления'),
            'user_id' => AcpMsg::t('ID пользователя'),
            'status'  => AcpMsg::t('Статус'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotification() {
        return $this->hasOne(Notification::class, ['id' => 'n_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public static function getUserCategories($userId) {
        $categories = [];

        try {
            $categories = Yii::$app->db->createCommand('
            SELECT DISTINCT n.category FROM ' . Notification::tableName() . ' n INNER JOIN ' . static::tableName() . ' nu ON nu.n_id = n.id  AND nu.user_id = :userId ORDER BY n.category
        ')
                ->bindValue(':userId', $userId)
                ->queryColumn();
        }
        catch(\Exception $e) {
            Yii::error("Ошибка при извлечении категорий уведомлений пользователя. " . $e->getMessage(), LogHelper::CATEGORY_DB);
        }

        return $categories;
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    public static function getUserCountUnreadNotifications($userId) {
        $counters = ['total' => 0];

        try {
            $result = Yii::$app->db->createCommand("
            SELECT COUNT(*) `count`, n.category
            FROM " . Notification::tableName() . " n
            INNER JOIN " . static::tableName() . " nu ON nu.n_id = n.id WHERE nu.status = :status AND nu.user_id = :userId GROUP BY n.category")
                ->bindValues([
                    ':userId' => $userId,
                    ':status' => DefNotificationUser::STATUS_NEW,
                ])
                ->queryAll();

            foreach($result as $row) {
                $counters['total'] += $row['count'];
                $counters[$row['category']] = $row['count'];
            }

            $listNotificationCategories = DefNotification::getListCategories('keys');
            $countersCategories         = array_keys($counters);

            //If user hasn't some category yet, anyway adds it to the counters with value 0
            foreach($listNotificationCategories as $category) {
                if(!in_array($category, $countersCategories)) {
                    $counters[$category] = 0;
                }
            }
        }
        catch(\Exception $e) {
            Yii::error("Ошибка при попытке извлечь счетчики уведомлений пользователя. " . $e->getMessage(), LogHelper::CATEGORY_DB);
        }

        return $counters;
    }

    /**
     * @param int $userId
     * @param int $countLastNotifications
     *
     * @return array
     */
    public static function getUserLastNotifications($userId, $countLastNotifications = 10) {
        $notifications = [];
        try {
            $notifications = Yii::$app->db->createCommand("
                SELECT n.id, n.category, n.type, n.title, n.message, n.target_link, n.created_at
                FROM " . Notification::tableName() . " n
                INNER JOIN " . static::tableName() . " nu ON nu.n_id = n.id
                WHERE nu.status = :status
                AND nu.user_id = :userId
                ORDER BY n.created_at DESC
                LIMIT :limit
            ")
                ->bindValues([
                    ':userId' => $userId,
                    ':status' => DefNotificationUser::STATUS_NEW,
                    ':limit'  => $countLastNotifications,
                ])
                ->queryAll();
        }
        catch(\Exception $e) {
            Yii::error("Ошибка при попытке извлечь последние уведомления пользователя. " . $e->getMessage(), LogHelper::CATEGORY_DB);
        }

        return $notifications;
    }

    /**
     * @param $notificationId
     * @param $status
     * @param $userId
     *
     * @return int
     * @throws \yii\db\Exception
     */
    public static function addNotificationToUser($notificationId, $status, $userId) {
        return Yii::$app->db->createCommand("
            INSERT INTO " . NotificationUser::tableName() . "
            (n_id, user_id, status)
            (
                SELECT :notificationId, id, :status
                FROM " . User::tableName() .
            " u INNER JOIN " . NotificationUser::tableName() .
            " nu ON nu.user_id = u.id AND nu.n_id = :notificationId AND nu.status = :statusRead WHERE u.id = :userId) ON DUPLICATE KEY UPDATE status = :statusNew",
            [
                ':notificationId' => $notificationId,
                ':status'         => $status,
                ':userId'         => $userId,
                ':statusRead'     => DefNotificationUser::STATUS_READ,
                ':statusNew'      => DefNotificationUser::STATUS_NEW,
            ])
            ->execute();
    }

    /**
     * @param array $groups
     * @param       $notificationId
     * @param       $status
     * @param       $language
     * @param       $onlyRead
     *
     * @return int
     */
    public static function addNotificationToGroups(array $groups, $notificationId, $status, $language, $onlyRead = false) {
        $groupsStr = implode(', ', array_map(function ($value) {
            return Yii::$app->db->quoteValue($value);
        }, $groups));

        $onlyReadSubQuery = '';

        if($onlyRead) {
            $statusVal        = Yii::$app->db->quoteValue(DefNotificationUser::STATUS_READ);
            $onlyReadSubQuery = 'INNER JOIN ' . NotificationUser::tableName() .
                ' nu ON nu.user_id = u.id AND nu.n_id = :notificationId AND nu.status = ' . $statusVal;


        }

        return Yii::$app->db->createCommand("
                    INSERT INTO " . NotificationUser::tableName() . "
                    (n_id, user_id, status)
                    (
                        SELECT :notificationId, id, :status                        
                        FROM " . User::tableName() . " u
                        {$onlyReadSubQuery}
                        WHERE u.lang = :lang  AND u.role IN ({$groupsStr})
                    ) ON DUPLICATE KEY UPDATE status = :statusNew
                ", [
            ':notificationId' => $notificationId,
            ':status'         => $status,
            ':lang'           => $language,
            ':statusNew'      => DefNotificationUser::STATUS_NEW,
        ])
            ->execute();
    }
}
