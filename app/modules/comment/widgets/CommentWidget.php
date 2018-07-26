<?php
namespace app\modules\comment\widgets;

use app\models\CommentChannel;
use app\modules\comment\components\CommentService;
use app\modules\comment\models\Comment;
use yii\base\Widget;

/**
 * Class CommentWidget
 * @package app\modules\comment\widgets
 */
class CommentWidget extends Widget
{
    /**
     * @var CommentService
     */
    protected $commentService;
    /**
     * @var string
     */
    protected $viewPath;
    /**
     * @var int
     */
    protected $totalComments;
    /**
     * @var string
     */
    public $template = 'base';
    /**
     * @var array
     */
    public $activeFormOptions = [
        'action'                 => null,
        'options'                => [
            'data-pjax' => true,
            'class'     => 'comment-form'
        ],
        'enableClientValidation' => true,
        'enableAjaxValidation'   => true,
        'validationUrl'          => [],
    ];
    /**
     * The ID of comment channel
     *
     * @var int
     */
    public $channelId = null;
    /**
     * The name of comment channel
     *
     * @var string
     */
    public $channelName = null;

    /**
     * CommentWidget constructor.
     *
     * @param CommentService $commentService
     * @param array          $config
     */
    public function __construct(CommentService $commentService, array $config = []) {
        $this->commentService = $commentService;

        parent::__construct($config);
    }

    /**
     * @throws \Exception
     */
    public function init() {
        parent::init();

        $this->commentService->setTemplate($this->template);

        $this->viewPath = $this->commentService->getWidgetViewPath();

        if($this->channelId === null && $this->channelName === null) {
            throw new \Exception('Channel ID or Channel Name must be specified.');
        }

        if($this->channelId === null) {
            /** @var CommentChannel $channel */
            $channel = CommentChannel::findOne(['slug' => $this->channelName]);

            if($channel === null) {
                throw new \Exception("Channel \"{$this->channelName}\" not found.");
            }

            $this->channelId = $channel->id;
        } elseif(CommentChannel::findOne($this->channelId) === null) {
            throw new \Exception("Channel ID \"{$this->channelId}\" not found.");
        }

        $this->activeFormOptions['action']        = '/comment/' . $this->channelId . '/add';
        $this->activeFormOptions['validationUrl'] = '/comment/' . $this->channelId . '/check-submission';
    }

    public function run() {
        $model        = new Comment;
        $templatePath = $this->viewPath . '/templates/' . $this->template;
        $data         = [
            'model' => $model,
        ];

        $this->commentService->setTotalComments(Comment::getCountComments($this->channelId));
        $this->commentService->setIsGuest(\Yii::$app->user->isGuest);

        $userId = \Yii::$app->user->id ?: null;

        $trees         = Comment::getTopTrees($this->channelId, $this->commentService->getTreesLimit(), null, $userId);
        $treeStructure = Comment::getComments($this->commentService->commentOffset, $this->channelId, $userId, $trees);

        $this->commentService->prepareMaxTreeId($treeStructure);

        $form  = $this->render($templatePath . '/_parts/form', $data);
        $items = $this->render($templatePath . '/_parts/items', ['comments' => $treeStructure]);

        $template = $this->render($templatePath . '/template', ['hasTreesToLoadMore' => (count($trees) < $this->commentService->treesLimit) ? false : true]);

        $template = str_replace('{form}', $form, $template);
        $template = str_replace('{items}', $items, $template);

        return $template;
    }

    /**
     * @return int
     */
    public function getTotalComments() {
        return $this->totalComments;
    }

    /**
     * @return CommentService
     */
    public function getCommentService() {
        return $this->commentService;
    }
}