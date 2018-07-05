<?php

namespace app\behaviors;

use app\components\Jdf;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class JDateTimeBehavior extends AttributeBehavior
{

    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';

    const DATE_FORMAT = 'Y/m/d';
    const DATETIME_FORMAT = 'Y/m/d H:i';

    public $dateAttributes;
    public $dateTimeAttributes;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFindOrSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterFindOrSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterFindOrSave'
        ];
    }

    protected function getValue($event)
    {
        return $this->value;
    }

    public function beforeSave($event)
    {
        if (!empty($this->dateAttributes)) {
            foreach ($this->dateAttributes as $attribute) {
                if (!empty($this->owner->{$attribute})) {
                    if ($this->isDateOrDateTime($this->owner->{$attribute})) {
                        $this->owner->{$attribute} = $this->convertToGDate($this->owner->{$attribute}, self::TYPE_DATE);
                    }
                }
            }
        }

        if (!empty($this->dateTimeAttributes)) {
            foreach ($this->dateTimeAttributes as $attribute) {
                if (!empty($this->owner->{$attribute})) {
                    if ($this->isDateOrDateTime($this->owner->{$attribute})) {
                        $this->owner->{$attribute} = $this->convertToGDate($this->owner->{$attribute}, self::TYPE_DATETIME);
                    }
                }
            }
        }
    }

    public function afterFindOrSave($event)
    {
        if (!empty($this->dateAttributes)) {
            foreach ($this->dateAttributes as $date_attribute) {
                if (!empty($this->owner->{$date_attribute})) {
                    $this->owner->{$date_attribute} = $this->convertToJDate($this->owner->{$date_attribute}, self::TYPE_DATE);
                }
            }
        }

        if (!empty($this->dateTimeAttributes)) {
            foreach ($this->dateTimeAttributes as $attribute) {
                if (!empty($this->owner->$attribute)) {
                    $this->owner->{$attribute} = $this->convertToJDate($this->owner->{$attribute}, self::TYPE_DATETIME);
                }
            }
        }
    }

    protected function convertToTimestamp($input, $type)
    {
        if ($type == self::TYPE_DATE) {
            $date_array = explode('/', $input);
            $timestamp = Jdf::jmktime(0, 0, 0, $date_array[1], $date_array[2], $date_array[0]);
            return $timestamp;
        }
        if ($type == self::TYPE_DATETIME) {
            $input_array = explode(' ', $input);
            $date_array = explode('/', $input_array[0]);
            $time_array = explode(':', $input_array[1]);
            $timestamp = Jdf::jmktime($time_array[0], $time_array[1], isset($time_array[2]) ? $time_array : 0, $date_array[1], $date_array[2], $date_array[0]);
            return $timestamp;
        }

    }

    protected function convertToJDate($input, $type)
    {
        $time = '';
        $date_array = [];
        if ($type == self::TYPE_DATETIME) {
            $input_array = explode(' ', $input);
            $date_array = explode('-', $input_array[0]);
            $time = isset($input_array[1]) ? substr($input_array[1], 0, 8) : '00:00';
        } else if ($type == self::TYPE_DATE) {
            $date_array = explode('-', $input);
        }
        $jDate_array = Jdf::gregorian_to_jalali($date_array[0], $date_array[1], $date_array[2]);
        foreach ($jDate_array as $index => $dateElement) {
            if (strlen($dateElement) == 1) {
                $jDate_array[$index] = "0{$dateElement}";
            }
        }
        $jDate = implode('/', $jDate_array);
        return !empty($time) ? "{$jDate} {$time}" : "{$jDate}";
    }

    protected function convertToGDate($input, $type)
    {
        $time = '';
        $date_array = [];
        if ($type == self::TYPE_DATETIME) {
            $input_array = explode(' ', $input);
            $date_array = explode('/', $input_array[0]);
            $time = isset($input_array[1]) ? $input_array[1] : '00:00';
        } else if ($type == self::TYPE_DATE) {
            $date_array = explode('/', $input);
        }
        $gDate_array = Jdf::jalali_to_gregorian($date_array[0], $date_array[1], $date_array[2]);
        $gDate = implode('-', $gDate_array);
        return !empty($time) ? "{$gDate} {$time}" : "{$gDate}";
    }

    protected function isDateOrDateTime($input)
    {
        $input_array = explode(' ', trim($input));
        if (isset($input_array[0]) && isset($input_array[2])) {
            $time_array = explode(':', $input_array[2]);
            $date_array = explode('/', $input_array[0]);
            if (isset($time_array[0]) && isset($time_array[1]) && isset($date_array[0]) && isset($date_array[1]) && isset($date_array[2])) {
                return self::TYPE_DATETIME;
            } else {
                return false;
            }
        } else {
            $input_array = explode('/', $input);
            if (isset($input_array[0]) && isset($input_array[1]) && isset($input_array[2])) {
                return self::TYPE_DATE;
            } else {
                return false;
            }
        }
    }

}
