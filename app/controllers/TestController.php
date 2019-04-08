<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Category;
use app\models\definitions\DefCategory;
use app\models\definitions\DefSiteUser;
use app\models\Task;
use app\models\TasksUser;
use app\models\Team;
use app\models\TeamSiteUser;
use yii\helpers\VarDumper;

/**
 * Class NewsController
 * @package app\controllers
 */
class TestController extends Controller
{
    public function actionGenerate()
    {
//        $team = Team::findOne([365]);
//        $teamSiteUser = TeamSiteUser::findOne(['hash' => 'SCInAmosPMZ95ylsZaL5u2YLXHGAksbe']);
//
//        if($team && $teamSiteUser) {
//            $registrationLink = Url::to('/register', true) . '/' . $teamSiteUser->hash;
//            $teamName = $team->name;
//            $teamLead = $team->teamCaptain() ? $team->teamCaptain()->getFullName() : '';
//            $notParticipantLink = Url::to('/', true) .
//                'decline/' . DefTeamSiteUser::RESPONSE_DECLINED . '/' . $teamSiteUser->hash;
//
//            $user = SiteUser::findOne(['email' => $teamSiteUser->email]);
//
//            if ($user) {
//                \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_TEAM,
//                DefNotification::TYPE_TEAM_INVITATION, null,
//                [
//                    'team_captain' => $teamLead,
//                    'team_name' => $teamName,
//                    'accept' => Html::a('сюди', $registrationLink, ['class' => 'link-button']),
//                    'decline' => Html::a('сюди', $notParticipantLink, ['class' => 'link-button']),
//                    'created_at' => date('d-M-Y H:i:s')
//                ]);
//            }
//        }
//
//        die('ok');

//        /** @var Task[] $tasks */
//        $tasks = Task::find()
//            ->where(['id' => [32, 33]])
//            ->all();
//
//        $teams = Team::find()
//            ->where(['status' => 'active'])
//            ->andWhere(['>', 'id', 400])
//            ->andWhere(['<=', 'id', 700])
//            ->all();
//
//        foreach ($teams as $team) {
//            /** @var Team $team */
//            foreach ($tasks as $task) {
//                /** @var TeamSiteUser $teamUser */
//                foreach ($team->teamUsers as $teamUser) {
//                    if ($teamUser->user && $teamUser->user->status === DefSiteUser::STATUS_ACTIVE) {
//                        $tasksUser = new TasksUser();
//                        $tasksUser->site_user_id = $teamUser->site_user_id;
//                        $tasksUser->task_id = $task->id;
//
//                        if (!$tasksUser->save()) {
//                            $this->flash('error', \Yii::t('easyii/tasks',
//                                'Notifications not sent :' . VarDumper::export($tasksUser->getErrors())));
//                        }
//                    }
//                }
//            }
//        }
//
//        die('ok');

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
//        $users = SiteUser::find()->where(['>', 'id', 3090])->all();
//
//        foreach ($users as $user) {
//            \Yii::$app->notification->addToUser($user, DefNotification::CATEGORY_TEAM,
//                DefNotification::TYPE_ALL_NOTIFICATION, null,
//                [
//                    'link_news' => Html::a('Нові ДЕДЛАЙНИ, нові завдання та що робити, якщо ви їх не виконали?!',
//                    Url::to(['/profile/news-item/novi-dedlajni-novi-zavdanna-ta-so-robiti-akso-vi-ih-ne-vikonali'])),
//                    'created_at' => date('d.m.Y H:i:s'),
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

//        $categoriesTree = [
//            [
//                'parent' => [
//                    'name' => 'Спілкування та атмосфера',
//                    'slug' => 'speech',
//                    'description' => 'Tooltip text',
//                ],
//                'elements' => [
//                    'Базовий рівень',
//                    'Перший рівень',
//                    'Другий рівень',
//                    'Третій рівень',
//                    'Четвертий рівень',
//                ]
//            ],
//            [
//                'parent' => [
//                    'name' => 'Спорт і здоров\'я',
//                    'description' => 'Tooltip text',
//                ],
//                'elements' => [
//                    'Базовий рівень',
//                    'Перший рівень',
//                    'Другий рівень',
//                    'Третій рівень',
//                    'Четвертий рівень',
//                ]
//            ],
//            [
//                'parent' => [
//                    'name' => 'Наука та розвиток',
//                    'description' => 'Tooltip text',
//                ],
//                'elements' => [
//                    'Базовий рівень',
//                    'Перший рівень',
//                    'Другий рівень',
//                    'Третій рівень',
//                    'Четвертий рівень',
//                ]
//            ],
//            [
//                'parent' => [
//                    'name' => 'Екологія ста сталість',
//                    'description' => 'Tooltip text',
//                ],
//                'elements' => [
//                    'Базовий рівень',
//                    'Перший рівень',
//                    'Другий рівень',
//                    'Третій рівень',
//                    'Четвертий рівень',
//                ]
//            ],
//            [
//                'parent' => [
//                    'name' => 'Арт',
//                    'description' => 'Tooltip text',
//                ],
//                'elements' => [
//                    'Базовий рівень',
//                    'Перший рівень',
//                    'Другий рівень',
//                    'Третій рівень',
//                    'Четвертий рівень',
//                ]
//            ],
//        ];
//
//        foreach ($categoriesTree as $categoryBlock) {
//            $category = new Category;
//            $category->name = $categoryBlock['parent']['name'];
//            $category->description = $categoryBlock['parent']['description'];
//            $category->slug = $categoryBlock['parent']['slug'];
//            $category->type = DefCategory::TYPE_STORE_CATEGORY;
//            $category->status = DefCategory::STATUS_ACTIVE;
//            $category->archived = 'no';
//            $category->makeRoot();
//
//            foreach ($categoryBlock['elements'] as $levelName) {
//                $level = new Category;
//                $level->name = $levelName;
//                $level->description = '';
//                $level->type = DefCategory::TYPE_STORE_CATEGORY_LEVEL;
//                $level->status = DefCategory::STATUS_ACTIVE;
//                $level->archived = 'no';
//
//                if($category) {
//                    $level->prependTo($category);
//                }
//            }
//        }

//        $category = new Category;
//        $category->name = 'Досягнення досвіду';
//        $category->description = 'Досягнення, повязані з отриманням досвіду';
//        $category->type = DefCategory::TYPE_STORE_CATEGORY;
//        $category->status = DefCategory::STATUS_ACTIVE;
//        $category->archived = 'no';
//
//        $node = Category::findOne(3);
//        if($node) {
//            $category->prependTo($node);
//        }
    }
}
