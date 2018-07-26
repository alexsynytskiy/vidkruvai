<?php

namespace app\modules\comment\components;

use Yii;

class CommentsVisualisationHelper
{
    /**
     * Counting the comment padding.
     * @return string counting result
     */
    public static function leftPaddingClassName($depth) {
        $leftFirstCommentPadding = "";
        if($depth > 0) {
            $leftFirstCommentPadding = "p-".abs(-5 + 40 * ($depth - 1));
        }

        return $leftFirstCommentPadding;
    }
}