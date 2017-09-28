<?php
/*
* Media.php - Model file
*
* This file is part of the Media component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Media\Models;

use App\Yantrana\Core\BaseModel;

class Media extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'medias';

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
