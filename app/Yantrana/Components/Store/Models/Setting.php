<?php
/*
* Setting.php - Model file
*
* This file is part of the Setting component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Models;

use App\Yantrana\Core\BaseModel;
use Cache;

class Setting extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'settings';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.storeSetting.namevalue',
        'cache.storeSetting.all',
        'cache.storeSetting.namevalue.lists',
    ];

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
