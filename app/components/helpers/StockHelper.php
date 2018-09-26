<?php

namespace app\components\helpers;

use app\models\Level;

/**
 * Class StockHelper
 * @package app\components\helpers
 */
class StockHelper
{
    /**
     * @param \yii\web\View $view
     * @param array $levels
     */
    public static function renderLevelsList($view, $levels)
    {
        if (count($levels) > 0) {
            /** @var Level $level */
            foreach ($levels as $level) {
                if ($level->nextLevel) {
                    echo $view->render('_level-item', ['model' => $level]);
                }
            }
        }
    }
}
