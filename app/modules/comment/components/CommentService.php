<?php

namespace app\modules\comment\components;

use yii\base\Object;

/**
 * Class CommentService
 * @package app\modules\comment\components
 *
 * @property int $commentOffset
 * @property int $treesLimit
 * @property int $totalComments
 * @property int $maxTreeId
 * @property bool $isGuest
 * @property string $template
 * @property array $allowedTemplates
 * @property int $channelId
 */
class CommentService extends Object
{
    const UNKNOWN_USERNAME = 'Unknown';
    protected $template = 'vidkruvai';
    protected $widgetViewPath = '@app/modules/comment/widgets/views/comment';
    protected $commentOffset = 0;
    protected $treesLimit = 5;
    protected $totalComments = 0;
    protected $maxTreeId;
    protected $isGuest;
    protected $allowedTemplates = [
        'vidkruvai'
    ];

    /**
     * @param $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @param null $template
     *
     * @return null|string
     */
    public function getTemplate($template = null)
    {
        if ($template === null) {
            return $this->template;
        }

        return in_array($template, $this->allowedTemplates, false) ? $template : $this->template;
    }

    /**
     * @param int $total
     */
    public function setTotalComments($total)
    {
        $this->totalComments = $total;
    }

    /**
     * @return int
     */
    public function getTotalComments()
    {
        return $this->totalComments;
    }

    /**
     * @return string
     */
    public function getWidgetViewPath()
    {
        return $this->widgetViewPath;
    }

    /**
     * @return int
     */
    public function getCommentOffset()
    {
        return $this->commentOffset;
    }

    /**
     * @return int
     */
    public function getTreesLimit()
    {
        return $this->treesLimit;
    }

    /**
     * @return array
     */
    public function getAllowedTemplates()
    {
        return $this->allowedTemplates;
    }

    /**
     * @param int $maxTreeId
     */
    public function setMaxTreeId($maxTreeId)
    {
        $this->maxTreeId = $maxTreeId;
    }

    /**
     * @return int
     */
    public function getMaxTreeId()
    {
        return $this->maxTreeId;
    }

    /**
     * @param $isGuest
     */
    public function setIsGuest($isGuest)
    {
        $this->isGuest = $isGuest;
    }

    /**
     * @return bool
     */
    public function getIsGuest()
    {
        return $this->isGuest;
    }

    /**
     * @param array $listComments
     */
    public function prepareMaxTreeId(array $listComments)
    {
        $rev = array_reverse($listComments);

        if (isset($rev[0])) {
            $c = $rev[0];
            $this->maxTreeId = $c->tree;
        }
    }

    /**
     * @param $template
     *
     * @return string
     */
    public function getTemplatePath($template = null)
    {
        if ($template === null) {
            $template = $this->template;
        } else {
            $template = $this->getTemplate($template);
        }

        return $this->widgetViewPath . '/templates/' . $template;
    }
}
