<?php

namespace app\assets;

/**
 * Class ModalAsset
 * @package app\assets
 */
class ModalAsset extends \yii\web\AssetBundle
{
    /**
     * @var string
     */
    protected static $pathToImages;

    public $sourcePath = '@app/media';

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css',
    ];

    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js'
    ];

    public $publishOptions = ['forceCopy' => true];
}
