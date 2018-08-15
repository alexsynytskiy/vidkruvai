<?php

namespace yii\easyii\modules\questions\assets;

/**
 * Class QuestionsAsset
 * @package yii\easyii\modules\questions\assets
 */
class QuestionsAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@easyii/modules/questions/media';

    public $js = [
        'js/PublicationPage.js',
    ];

    public $publishOptions = [
        'forceCopy' => true
    ];
}