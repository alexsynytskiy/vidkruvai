<?php

namespace app\components\helpers;

use app\models\Category;
use app\models\City;
use app\models\definitions\DefStoreItem;
use app\models\Sale;
use app\models\State;
use app\models\StoreItem;

/**
 * Class RatingHelper
 * @package app\components\helpers
 */
class RatingHelper
{
    /**
     * @param array $statesRating
     * @param Category[] $categories
     *
     * @return array
     */
    public static function sortStatesByRating($statesRating, $categories)
    {
        foreach ($statesRating as $stateName => $categoryValues) {
            $rating = 0;

            foreach ($categories as $category) {
                $rating += $categoryValues[$category->name];
            }

            $statesRating[$stateName]['rating'] = round($rating / count($categories), 4);
            $statesRating[$stateName]['stateId'] = State::findOne(['name' => $stateName])->id;
        }

        $statesRating = self::sortBySubValue($statesRating, 'rating', false, true);

        return $statesRating;
    }

    /**
     * @param array $array
     * @param string $value
     * @param bool $asc - ASC (true) or DESC (false) sorting
     * @param bool $preserveKeys
     *
     * @return array
     * */
    private static function sortBySubValue($array, $value, $asc = true, $preserveKeys = false)
    {
        if (is_object(reset($array))) {
            $preserveKeys ? uasort($array, function ($a, $b) use ($value, $asc) {
                return $a->{$value} === $b->{$value} ? 0 : ($a->{$value} <=> $b->{$value}) * ($asc ? 1 : -1);
            }) : usort($array, function ($a, $b) use ($value, $asc) {
                return $a->{$value} === $b->{$value} ? 0 : ($a->{$value} <=> $b->{$value}) * ($asc ? 1 : -1);
            });
        } else {
            $preserveKeys ? uasort($array, function ($a, $b) use ($value, $asc) {
                return $a[$value] === $b[$value] ? 0 : ($a[$value] <=> $b[$value]) * ($asc ? 1 : -1);
            }) : usort($array, function ($a, $b) use ($value, $asc) {
                return $a[$value] === $b[$value] ? 0 : ($a[$value] <=> $b[$value]) * ($asc ? 1 : -1);
            });
        }

        return $array;
    }

    /**
     * @param State[] $states
     * @param Category[] $categories
     *
     * @return array
     */
    public static function prepareStatesRating($states, $categories)
    {
        $statesRating = $stateCities = [];

        foreach ($states as $key => $state) {
            $cities = City::find()->where(['state_id' => $state->id])->all();

            $stateCitiesStats = self::prepareStateCitiesRating($cities, $categories);

            $citiesRating = $stateCitiesStats['citiesRating'];
            $citiesCompeting = $stateCitiesStats['citiesCount'];

            $stateCities[$state->name] = $citiesCompeting;

            /**
             * @var array $citiesRating
             * @var integer $cityId
             * @var array $cityCategories
             */
            foreach ($citiesRating as $cityId => $cityCategories) {
                foreach ($cityCategories as $category => $rating) {
                    $statesRating[$state->name][$category] = array_key_exists($state->name, $statesRating) &&
                    array_key_exists($category, $statesRating[$state->name]) ?
                        $statesRating[$state->name][$category] + $rating :
                        $rating;
                }
            }
        }

        foreach ($statesRating as $stateName => $stateCategories) {
            /**
             * @var string $categoryName
             * @var integer $ratingValue
             * @var array $stateCategories
             */
            foreach ($stateCategories as $categoryName => $ratingValue) {
                $statesRating[$stateName][$categoryName] = $ratingValue ?
                    round($ratingValue / $stateCities[$stateName], 2) : 0;
            }
        }

        return $statesRating;
    }

    /**
     * @param City[] $cities
     * @param Category[] $categories
     *
     * @return array
     */
    public static function prepareStateCitiesRating($cities, $categories)
    {
        $citiesRating = [];
        $citiesCompeting = 0;

        foreach ($cities as $city) {
            $itemsBought = Sale::find()
                ->alias('s')
                ->innerJoin(StoreItem::tableName() . ' si', 'si.id = s.store_item_id')
                ->where([
                    's.city_id' => $city->id,
                    'si.type' => DefStoreItem::TYPE_CITY,
                ])
                ->all();

            if (count($itemsBought) || count($city->getActiveTeams())) {
                if ($itemsBought) {
                    /** @var Sale $item */
                    foreach ($itemsBought as $item) {
                        /** @var Category $category */
                        foreach ($categories as $category) {
                            if ($category->id === $item->storeItem->category->parents()->one()->id) {
                                $citiesRating[$city->id][$category->name] = array_key_exists($city->id, $citiesRating) &&
                                array_key_exists($category->name, $citiesRating[$city->id]) ?
                                    $citiesRating[$city->id][$category->name] + 1 :
                                    1;
                            }
                        }
                    }
                } else {
                    /** @var Category $category */
                    foreach ($categories as $category) {
                        $citiesRating[$city->id][$category->name] = 0;
                    }
                }

                $citiesCompeting++;
            }
        }

        return ['citiesCount' => $citiesCompeting, 'citiesRating' => $citiesRating];
    }
}
