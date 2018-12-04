<?php

namespace yii\easyii\modules\statistics\controllers;

use app\models\City;
use app\models\School;
use app\models\SchoolType;
use app\models\SiteUser;
use app\models\State;
use Yii;
use yii\easyii\components\Controller;

/**
 * Class AController
 * @package yii\easyii\modules\statistics\controllers
 */
class AController extends Controller
{
    public function actionIndex()
    {
        $stateStatistics = SiteUser::find()
            ->alias('su')
            ->select(['count(su.id) count', 'CONCAT(st.name, " обл.") name'])
            ->innerJoin(School::tableName() . ' s', 's.id = su.school_id')
            ->innerJoin(City::tableName() . ' c', 'c.id = s.city_id')
            ->innerJoin(State::tableName() . ' st', 'st.id = c.state_id')
            ->groupBy('st.id')
            ->orderBy('count DESC')
            ->asArray()
            ->all();

        $schoolStatistics = SiteUser::find()
            ->alias('su')
            ->select(['count(su.id) count', 'CONCAT(sct.name, " №", s.number, " ", s.name) name'])
            ->innerJoin(School::tableName() . ' s', 's.id = su.school_id')
            ->innerJoin(City::tableName() . ' c', 'c.id = s.city_id')
            ->innerJoin(State::tableName() . ' st', 'st.id = c.state_id')
            ->innerJoin(SchoolType::tableName() . ' sct', 'sct.id = s.type_id')
            ->groupBy('s.id')
            ->orderBy('count DESC')
            ->asArray()
            ->all();

        $cityStatistics = SiteUser::find()
            ->alias('su')
            ->select(['count(su.id) count', 'CONCAT(st.name, " обл., ", c.city) name'])
            ->innerJoin(School::tableName() . ' s', 's.id = su.school_id')
            ->innerJoin(City::tableName() . ' c', 'c.id = s.city_id')
            ->innerJoin(State::tableName() . ' st', 'st.id = c.state_id')
            ->groupBy('c.id')
            ->orderBy('count DESC')
            ->asArray()
            ->all();

        return $this->render('index', [
            'stateStatistics' => $stateStatistics,
            'schoolStatistics' => $schoolStatistics,
            'cityStatistics' => $cityStatistics,
        ]);
    }
}