<?php

namespace App\Yantrana\Core;

use App\Yantrana\__Laraware\Core\CoreModel;

abstract class BaseModel extends CoreModel
{
    /*
     * The custom primary key.
     *
     * @var string
     *----------------------------------------------------------------------- */

    //protected $primaryKey = '_id';

    /*
     * The generate UID or not
     *
     * @var string
     *----------------------------------------------------------------------- */

    //protected $isGenerateUID = true;

    /*public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = currentDateTime(); // get current dateTime using selected timezone
        });

        static::updating(function ($model) {
            $model->updated_at = currentDateTime(); // get current dateTime using selected timezone
        });
        
        parent::boot();
    }*/
}
