<?php
/*
* Country.php - Model file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support\Models;

use App\Yantrana\Core\BaseModel;

class Country extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'countries';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'iso_num_code' => 'integer',
        'phone_code' => 'integer',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
