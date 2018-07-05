<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 *
 * @property int $imageFile
 * @property int $audioFile
 * @property int $otherFile
 */
class MediaFile extends Model
{
    public $imageFile;
    public $audioFile;
    public $otherFile;
}
