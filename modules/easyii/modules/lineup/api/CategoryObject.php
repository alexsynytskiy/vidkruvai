<?php
namespace yii\easyii\modules\lineup\api;

use Yii;
use yii\data\ActiveDataProvider;
use yii\easyii\components\API;
use yii\easyii\models\Tag;
use yii\easyii\modules\lineup\models\Item;
use yii\helpers\Url;
use yii\widgets\LinkPager;

class CategoryObject extends \yii\easyii\components\ApiObject
{
    public $slug;
    public $image;
    public $tree;
    public $depth;

    public $date;

    private $_adp;
    private $_items;

    public function getTitle(){
        return LIVE_EDIT ? API::liveEdit($this->model->title, $this->editLink) : $this->model->title;
    }

    public function pages($options = []){
        return $this->_adp ? LinkPager::widget(array_merge($options, ['pagination' => $this->_adp->pagination])) : '';
    }

    public function pagination(){
        return $this->_adp ? $this->_adp->pagination : null;
    }

    public function items($options = [])
    {
        if(!$this->_items){
            $this->_items = [];

            $with = ['seo'];
            if(Yii::$app->getModule('admin')->activeModules['lineup']->settings['enableTags']){
                $with[] = 'tags';
            }

            $query = Item::find()->with('seo')->where(['category_id' => $this->id, 'status' => Item::STATUS_ON])->sortDate();

            if(!empty($options['where'])){
                $query->andFilterWhere($options['where']);
            }
            if(!empty($options['tags'])){
                $query
                    ->innerJoinWith('tags', false)
                    ->andWhere([Tag::tableName() . '.name' => (new Item())->filterTagValues($options['tags'])])
                    ->addGroupBy('item_id');
            }

            $this->_adp = new ActiveDataProvider([
                'query' => $query,
                'pagination' => !empty($options['pagination']) ? $options['pagination'] : false
            ]);

            foreach($this->_adp->models as $model){
                $this->_items[] = new ArtistObject($model);
            }
        }
        return $this->_items;
    }

    public function getEditLink(){
        return Url::to(['/admin/lineup/a/edit/', 'id' => $this->id]);
    }
}