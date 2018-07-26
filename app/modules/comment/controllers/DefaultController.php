<?php

namespace app\modules\comment\controllers;

use app\models\CommentChannel;
use app\models\definitions\DefComment;
use app\modules\comment\components\CommentService;
use app\modules\comment\models\Comment;
use app\modules\comment\models\CommentVote;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class DefaultController
 * @package app\modules\comment\controllers
 */
class DefaultController extends Controller
{
    /**
     * @var CommentService
     */
    protected $commentService;

    /**
     * DefaultController constructor.
     *
     * @param string $id
     * @param \yii\base\Module $module
     * @param CommentService $commentService
     * @param array $config
     */
    public function __construct($id, \yii\base\Module $module, CommentService $commentService, array $config = [])
    {
        $this->commentService = $commentService;

        parent::__construct($id, $module, $config);
    }

    /**
     * @param int $channelId
     *
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionAdd($channelId)
    {
        $r = Yii::$app->request;

        if (!$r->isPost || !$r->isAjax || \Yii::$app->user->isGuest) {
            throw new BadRequestHttpException();
        }

        $post = $r->post();
        $csrf = $r->post(Yii::$app->request->csrfParam);

        if (!Yii::$app->request->validateCsrfToken($csrf)) {
            throw new BadRequestHttpException;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$this->validateChannelId($channelId)) {
            return [];
        }

        $appendComment = null;

        try {
            $comment = new Comment();
            $comment->load($post);

            if ($comment->replyTo) {
                $appendComment = Comment::findOne($comment->replyTo);

                if (!$appendComment->children()->all()) {
                    $comment->isFirstReply = true;
                }
            }

            $this->preFillModel($comment, $channelId);

            if ($appendComment === null && !$comment->makeRoot()) {
                return [];
            }

            if ($appendComment !== null && !$comment->appendTo($appendComment)) {
                return [];
            }

            $service = $this->commentService;
            $service->setTemplate($r->post('t'));

            $comment->refresh();

            return $this->renderAjax($service->getTemplatePath() . '/_parts/items', ['comments' => [$comment]]);
        } catch (\Exception $e) {
            Yii::error("Невозможно добавить комментарий.\nError: " . $e->getMessage());
        }

        return [];
    }

    /**
     * @param int $channelId
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCheckSubmission($channelId)
    {
        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $model = new Comment();

        if ($model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $this->preFillModel($model, $channelId);

            return ActiveForm::validate($model);
        }

        return [];
    }

    /**
     * @param Comment $model
     * @param int $channelId
     */
    protected function preFillModel(&$model, $channelId)
    {
        $model->status = DefComment::STATUS_ACTIVE;
        $model->channel_id = $channelId;
        $model->message = trim($model->message);

        if (!Yii::$app->user->isGuest) {
            $model->site_user_id = 1;
        }
    }

    /**
     * @param int $channelId
     *
     * @see https://www.sitepoint.com/paginating-real-time-data-cursor-based-pagination
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLoadMore($channelId)
    {
        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$this->validateChannelId($channelId)) {
            return [];
        }

        $treeId = (int)Yii::$app->request->post('treeId');
        $templateName = (string)Yii::$app->request->post('template');

        if (!$treeId) {
            return [];
        }

        $template = $this->commentService->getTemplate($templateName);
        $userId = (Yii::$app->user->id ?: null);

        $trees = Comment::getTopTrees($channelId, $this->commentService->getTreesLimit(), $treeId);

        $treeStructure = [];
        if (count($trees) > 0) {
            $treeStructure = Comment::getComments($this->commentService->commentOffset, $channelId, $userId, $trees);
        }

        $this->commentService->prepareMaxTreeId($treeStructure);

        $this->commentService->setIsGuest(\Yii::$app->user->isGuest);

        $items = $this->renderPartial($this->commentService->getTemplatePath($template) . '/_parts/items',
            ['comments' => $treeStructure]);

        return [
            'items' => $items,
            'treeId' => (int)$this->commentService->maxTreeId,
            'ids' => ArrayHelper::getColumn($treeStructure, 'id'),
            'treesCount' => count($trees),
        ];
    }

    /**
     * @param int $channelId
     *
     * @see https://www.sitepoint.com/paginating-real-time-data-cursor-based-pagination
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionReloadTree($channelId)
    {
        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$this->validateChannelId($channelId)) {
            return [];
        }

        $treeId = (int)Yii::$app->request->post('treeId');
        $templateName = (string)Yii::$app->request->post('template');

        if (!$treeId) {
            return [];
        }

        $template = $this->commentService->getTemplate($templateName);
        $userId = (Yii::$app->user->id ?: null);

        /** @var Comment $tree */
        $commentsTree = Comment::getComments($this->commentService->commentOffset, $channelId, $userId, $treeId);

        $this->commentService->prepareMaxTreeId($commentsTree);

        $this->commentService->setIsGuest(\Yii::$app->user->isGuest);

        $items = $this->renderPartial($this->commentService->getTemplatePath($template) . '/_parts/items',
            ['comments' => $commentsTree]);

        return [
            'items' => $items,
            'treeId' => (int)$this->commentService->maxTreeId,
            'ids' => ArrayHelper::getColumn($commentsTree, 'id'),
        ];
    }

    /**
     * @param int $channelId
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\Exception
     */
    public function actionVote($channelId)
    {
        if (!Yii::$app->request->isPost || !Yii::$app->request->isAjax || Yii::$app->user->isGuest) {
            throw new NotFoundHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!$this->validateChannelId($channelId)) {
            return [];
        }

        $commentId = (int)Yii::$app->request->post('commentId');
        $rating = (int)Yii::$app->request->post('rating');

        $comment = Comment::findOne([
            'id' => $commentId,
            'channel_id' => $channelId,
        ]);

        if ($comment === null) {
            return [];
        }

        $rating = $rating > 0 ? 1 : -1;
        $isNewVote = false;

        $commentVote = CommentVote::findOne(['site_user_id' => Yii::$app->user->id, 'comment_id' => $commentId]);

        if (!$commentVote) {
            $commentVote = new CommentVote;
            $commentVote->site_user_id = Yii::$app->user->id;
            $commentVote->comment_id = $commentId;
            $commentVote->rating = $rating;

            $isNewVote = true;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($isNewVote) {
                $commentVote->save(true);

                $totalRating = $comment->rating + $rating;

                $comment->updateAttributes(['rating' => $totalRating]);

                $transaction->commit();

                return ['totalRating' => $totalRating, 'rating' => $rating, 'blockBtn' => $rating];
            }

            if ($commentVote->rating !== $rating && ($commentVote->rating + $rating) === 0) {
                $commentVote->delete();

                $totalRating = $comment->rating + $rating;

                $comment->updateAttributes(['rating' => $totalRating]);

                $transaction->commit();

                return ['totalRating' => $totalRating, 'rating' => $rating, 'blockBtn' => 0];
            }

            return [];
        } catch (\Exception $e) {
            $transaction->rollBack();

            Yii::error("Невозможно проголосовать за комментарий.\nError: " . $e->getMessage());
        }

        return [];
    }

    /**
     * @return CommentService
     */
    public function getCommentService()
    {
        return $this->commentService;
    }

    /**
     * @param int $channelId
     *
     * @return bool
     */
    protected function validateChannelId($channelId)
    {
        return CommentChannel::findOne(['id' => $channelId]) !== null;
    }
}
