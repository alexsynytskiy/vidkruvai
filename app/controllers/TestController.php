<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Achievement;
use app\models\Category;
use app\models\definitions\DefCategory;
use app\models\definitions\DefNotification;
use app\models\definitions\DefSiteUser;
use app\models\Level;
use app\models\SiteUser;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class NewsController
 * @package app\controllers
 */
class TestController extends Controller
{
    public function actionGenerate()
    {
//        $results = \Yii::$app->db->createCommand('SELECT
//                SUBSTRING(message, 1, 35) AS subst,
//                COUNT(id) c,
//                GROUP_CONCAT(id) notif_ids
//            FROM
//                notification
//            WHERE TYPE
//                    = \'team-created\'
//            GROUP BY
//                SUBSTRING(message, 1, 35)
//            HAVING
//                c > 1
//                order by c desc LIMIT 1,200')
//            ->queryAll();
//
//        foreach ($results as $duplicates) {
//            $notificationIds = explode(',', $duplicates['notif_ids']);
//            sort($notificationIds);
//
//            array_shift($notificationIds);
//
//            $duplicateIdsString = implode(',', $notificationIds);
//
//            \Yii::$app->db->createCommand("DELETE FROM `notification_user` WHERE
//                    `n_id` IN({$duplicateIdsString})
//            ")->execute();
//
//            \Yii::$app->db->createCommand("DELETE FROM `notification` WHERE
//                    `id` IN({$duplicateIdsString})
//            ")->execute();
//        }
//
//        die('ok');

//        /** @var SiteUser[] $users */
//        $users = SiteUser::find()->where(['>', 'id', 7481])->all();
//
//        foreach ($users as $user) {
//            \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_TEAM,
//                DefNotification::TYPE_ALL_NOTIFICATION, null,
//                [
//                    'link_news' => Html::a('Зміни щодо підтвердження команд',
//                    Url::to(['/profile/news-item/zmini-sodo-pidtverdzenna-komand'])),
//                    'created_at' => date('d-M-Y H:i:s'),
//                ]);
//        }
//
//        die('ok');

//        $results = \Yii::$app->db->createCommand('
//        SELECT COUNT(s.id) schools_count, st.name, c.city, ste.name, s.number, s.name, GROUP_CONCAT(s.id) school_ids FROM `school` s LEFT JOIN city c ON c.id = s.city_id LEFT JOIN state st ON st.id = c.state_id LEFT JOIN schooltypes ste ON ste.id = s.type_id GROUP BY s.city_id, s.type_id, s.number HAVING schools_count > 1 ORDER BY schools_count DESC LIMIT 1, 3000
//        ')->queryAll();
//
//        foreach ($results as $duplicates) {
//            $schoolIds = explode(',',$duplicates['school_ids']);
//
//            sort($schoolIds);
//
//            $originId = $schoolIds[0];
//
//            array_shift($schoolIds);
//
//            $duplicateIdsString = implode(',', $schoolIds);
//
//            \Yii::$app->db->createCommand("
//                UPDATE
//                    `site_user`
//                SET
//                    `school_id` = {$originId}
//                WHERE
//                    `school_id` IN({$duplicateIdsString})
//            ")->execute();
//
//            \Yii::$app->db->createCommand("DELETE FROM `school` WHERE
//                    `id` IN({$duplicateIdsString})
//            ")->execute();
//        }
//
//        echo "<pre>";
//        print_r($results); die;

        return $this->redirect(['/']);

//        $level = new Level;
//        $level->group_id = 2;
//        $level->num = 4;
//        $level->required_experience = 500;
//        $level->base_level = 0;
//        $level->archived = 'no';
//        $level->awardIDs = [3];
//        $level->save();

//        $achievement = new Achievement;
//        $achievement->group_id = 6;
//        $achievement->name = 'Командний гравець';
//        $achievement->description = '';
//        $achievement->class_name = 'ReachedExperience';
//        $achievement->priority = 1;
//        $achievement->required_steps = 3500;
//        $achievement->archived = 'no';
//        $achievement->awardIDs = [1];
//        $a = $achievement->save();

//        $category = new Category;
//        $category->name = 'Досягнення досвіду';
//        $category->description = 'Досягнення, повязані з отриманням досвіду';
//        $category->type = DefCategory::TYPE_ACHIEVEMENT_GROUP;
//        $category->status = DefCategory::STATUS_ACTIVE;
//        $category->archived = 'no';
//
//        $node = Category::findOne(3);
//        if($node) {
//            $category->prependTo($node);
//        }
    }
}
