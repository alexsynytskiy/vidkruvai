<?php

namespace app\components\behaviors;

use yii\db\BaseActiveRecord;

/**
 * Class TimestampBehavior
 * @package app\components\behaviors
 *
 * @property \app\components\ActiveRecord $owner
 */
class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    /**
     * @var array
     */
    public $additionalCreatedAtAttributes = [];
    /**
     * @var array
     */
    public $additionalUpdatedAtAttributes = [];

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->attributes = [];

        $additionalCreatedAt = $this->additionalCreatedAtAttributes;
        $additionalUpdatedAt = $this->additionalUpdatedAtAttributes;

        $isCreatedAt = $this->owner->hasAttribute($this->createdAtAttribute);
        $isUpdatedAt = $this->owner->hasAttribute($this->updatedAtAttribute);

        if ($isCreatedAt) {
            $this->attributes[BaseActiveRecord::EVENT_BEFORE_INSERT][] = $this->createdAtAttribute;
        }

        if ($additionalCreatedAt && is_array($additionalCreatedAt)) {
            foreach ($additionalCreatedAt as $attr) {
                if ($this->owner->hasAttribute($attr)) {
                    $this->attributes[BaseActiveRecord::EVENT_BEFORE_INSERT][] = $attr;
                }
            }
        }

        if ($additionalUpdatedAt && is_array($additionalUpdatedAt)) {
            foreach ($additionalUpdatedAt as $attr) {
                if ($this->owner->hasAttribute($attr)) {
                    $this->attributes[BaseActiveRecord::EVENT_BEFORE_INSERT][] = $attr;
                    $this->attributes[BaseActiveRecord::EVENT_BEFORE_UPDATE][] = $attr;
                }
            }
        }

        if ($isUpdatedAt) {
            $this->attributes[BaseActiveRecord::EVENT_BEFORE_INSERT][] = $this->updatedAtAttribute;
            $this->attributes[BaseActiveRecord::EVENT_BEFORE_UPDATE][] = $this->updatedAtAttribute;
        }
    }
}
