<?php

namespace app\controllers;

use app\components\Controller;
use app\models\Achievement;
use app\models\Category;
use app\models\definitions\DefCategory;
use app\models\Level;

/**
 * Class NewsController
 * @package app\controllers
 */
class TestController extends Controller
{
    public function actionGenerate()
    {
        return $this->redirect(['/']);

        $level = new Level;
        $level->group_id = 2;
        $level->num = 4;
        $level->required_experience = 500;
        $level->base_level = 0;
        $level->archived = 'no';
        $level->awardIDs = [3];
        $level->save();

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
