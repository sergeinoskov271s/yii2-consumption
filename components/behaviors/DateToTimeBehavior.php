<?php


namespace app\components\behaviors;


use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;

/**
 * Class DateToTimeBehavior
 * @package app\components\behaviors
 */
class DateToTimeBehavior extends AttributeBehavior
{
    public string $timeAttribute;

    /**
     * Returns the value for the current attributes.
     * This method is called by [[evaluateAttributes()]]. Its return value will be assigned
     * to the attributes corresponding to the triggering event.
     * @param Event $event the event that triggers the current attribute updating.
     * @return mixed the attribute value
     * @throws InvalidConfigException
     */
    protected function getValue( $event) {

        if (empty($this->timeAttribute)) {
            throw new InvalidConfigException(
                'Can`t find "fromAttribute" property in ' . $this->owner->className()
            );
        }

        if (!empty($this->owner->{$this->attributes[$event->name]})) {
            $this->owner->{$this->timeAttribute} = strtotime(
                $this->owner->{$this->attributes[$event->name]}
            );

            return date('Y-m-d  H::m::s', $this->owner->{$this->timeAttribute});
        } elseif (!empty($this->owner->{$this->timeAttribute})
            && is_numeric($this->owner->{$this->timeAttribute})
        ) {
            $this->owner->{$this->attributes[$event->name]} = date(
                'Y-m-d  H::m::s',
                $this->owner->{$this->timeAttribute}
            );

            return $this->owner->{$this->attributes[$event->name]};
        }

        return null;
    }


}