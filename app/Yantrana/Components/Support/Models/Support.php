<?php
/*
* Support.php - Model file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support\Models;

use App\Yantrana\Core\BaseModel;

class Support extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'supports';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
