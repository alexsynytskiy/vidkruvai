<?php

namespace app\modules\comment\assets;

/**
 * Class CommentAsset
 * @package app\modules\comment\assets
 */
class CommentAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/modules/comment/media';

    public $js = [
        'js/Comment.js',
    ];

    public $css = [
        'css/style.css',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}