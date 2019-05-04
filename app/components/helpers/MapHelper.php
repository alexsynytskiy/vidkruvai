<?php

namespace app\components\helpers;

use app\components\Controller;
use app\models\Category;
use app\models\City;
use app\models\definitions\DefTeam;
use app\models\State;
use app\models\Team;

/**
 * Class MapHelper
 * @package app\components\helpers
 */
class MapHelper
{
    /**
     * @param Controller $controller
     * @param array $statesRating
     * @param Category[] $categories
     *
     * @return array
     */
    public static function prepareRegions(&$controller, $statesRating, $categories)
    {
        /** @var State[] $states */
        $states = State::find()->all();
        $stateData = [
            'UA-30' => 'Київ',
            'UA-43' => 'АР Крим',
            'UA-40' => 'Севастополь',
        ];

        foreach ($stateData as $key => $state) {
            $stateData[$key] = [
                'id' => $key,
                'id_no_spaces' => $key,
                'title' => $state,
                'tooltip' => $state,
                'target' => 'blank',
                'fill' => 'rgb(221, 221, 221)',
            ];
        }

        foreach ($states as $state) {
            $teamsCount = 0;
            $cities = City::find()->where(['state_id' => $state->id])->all();
            /** @var City $city */
            foreach ($cities as $city) {
                $teamsCount += count($city->getActiveTeams());
            }

            $stateData[$state->map_code] = [
                'id' => $state->map_code,
                'id_no_spaces' => $state->map_code,
                'title' => $state->name . ' область',
                'tooltip' => $state->name . ' область',
                'fill' => 'rgba(112, 124, 255, ' . $teamsCount / 100 . ')',
                'popover' => $controller->renderPartial('/rating/map-progress', [
                    'state' => $state,
                    'stateProgress' => $statesRating[$state->name],
                    'categories' => $categories,
                ]),
                'target' => 'blank',
            ];
        }

        $stateData['UA-30']['fill'] = $stateData['UA-32']['fill'];

        return $stateData;
    }

    /**
     * @return array
     */
    public static function prepareMarks()
    {
        $activeTeams = Team::find()->where(['status' => DefTeam::STATUS_ACTIVE])->all();
        $marksJS = [];

        $userCityId = \Yii::$app->siteUser->identity->school->city_id;

        /** @var Team $team */
        foreach ($activeTeams as $team) {
            $city = $team->teamCaptain()->school->city;

            if ($city->latitude && $city->longitude && !array_key_exists($city->id, $marksJS)) {
                $tooltip = '';

                foreach ($city->getActiveTeams() as $cityTeam) {
                    $tooltip .= '<a href="#" class="team-name" data-city-id="' . $city->id .
                        '" data-team-id="' . $cityTeam->id . '">' . $cityTeam->name . '</a>';
                }

                $marksJS[$city->id] = [
                    'id' => $city->id,
                    'attached' => true,
                    'isLink' => false,
                    'src' => '{assetUrl}/img/pin' . ($userCityId === $city->id ? '2' : '') . '.svg',
                    'width' => 38,
                    'height' => 53,
                    'geoCoords' => [$city->latitude, $city->longitude],
                    'tooltip' => '<b>' . $city->city . ', команди:</b>' . $tooltip,
                    'popover' => '<div class="city-teams"><b>' . $city->city . ', команди:</b>' . $tooltip . '</div>',
                ];
            }
        }

        return $marksJS;
    }
}
