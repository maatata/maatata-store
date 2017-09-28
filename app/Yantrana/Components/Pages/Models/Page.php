<?php
/*
* Pages.php - Model file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Models;

use App\Yantrana\Core\BaseModel;
use Cache;

class Page extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'pages';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
       'cache.pages.active.addtomenu.all',
    ];

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'add_to_menu' => 'integer',
        'parent_id' => 'integer',
        'type' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
