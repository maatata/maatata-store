<?php
/*
* Address.php - Model file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Models;

use App\Yantrana\Core\BaseModel;

class Address extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'addresses';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'type' => 'integer',
        'primary' => 'integer',
        'users_id' => 'integer',

    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
