<?php

namespace app\components\traits;

use app\components\helpers\DateTimeHelper;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Trait SortTrait
 * @package app\components\traits
 */
trait SortTrait
{
    /**
     * @param ActiveQuery $query
     * @param             $dbField
     * @param             $value
     */
    public function compareRangeDate(ActiveQuery &$query, $dbField, $value)
    {
        $parsedDate = DateTimeHelper::parseRangeDate($value);
        $dateFormat = '%Y-%m-%d';

        if ($parsedDate['firstTimeSeconds'] !== null) {
            $dateFormat = '%Y-%m-%d %H:%i:%s';
        } elseif ($parsedDate['firstTime'] !== null) {
            $dateFormat = '%Y-%m-%d %H:%i';
        }

        if ($parsedDate['firstTime'] === null) {
            $dbFieldFormat = 'DATE(' . $dbField . ')';
        } else {
            $dbFieldFormat = $dbField;
        }

        if ($parsedDate['firstDate'] !== null && $parsedDate['secondDate'] !== null) {
            $query->andWhere(new Expression(
                $dbFieldFormat . ' BETWEEN STR_TO_DATE(:firstDate, "' . $dateFormat .
                '") AND STR_TO_DATE(:secondDate, "' . $dateFormat . '")',
                [
                    ':firstDate' => $parsedDate['firstDate'],
                    ':secondDate' => $parsedDate['secondDate'],
                ]
            ));
        } elseif ($parsedDate['firstDate'] !== null) {
            $query->andWhere(new Expression($dbFieldFormat . ' = STR_TO_DATE(:firstDate, "' .
                $dateFormat . '")', [':firstDate' => $parsedDate['firstDate']]));
        }
    }

    /**
     * Adding new attributes to be sorted
     *
     * $attributes can be in the formats
     * [
     *      'modelAttributeName' => 'dbFieldName',
     *      'modelAttributeName' => [
     *          'dbFieldName',
     *
     *          //Yii config
     *          //@see \yii\data\Sort::$attributes
     *          'default' => 'default order'
     *          'label' => 'label'
     *      ],
     *      'dbFieldName'
     * ]
     *
     * @param ActiveDataProvider $dataProvider
     * @param array $attributes
     *
     * @return void
     */
    public function addSortAttributes(ActiveDataProvider $dataProvider, array $attributes)
    {
        foreach ($attributes as $key => $sortAttribute) {
            if (is_string($key) && $sortAttribute === null && $dataProvider->sort->hasAttribute($key)) {
                unset($dataProvider->sort->attributes[$key]);

                continue;
            }

            $modelAttribute = is_string($key) ? $key : $sortAttribute;

            $dataProvider->sort->attributes[$modelAttribute] = [
                'asc' => [$sortAttribute => SORT_ASC],
                'desc' => [$sortAttribute => SORT_DESC],
            ];
        }
    }
}
