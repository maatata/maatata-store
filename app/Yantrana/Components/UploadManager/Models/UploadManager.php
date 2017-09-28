<?php
/*
* UploadManager.php - Model file
*
* This file is part of the UploadManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UploadManager\Models;

use App\Yantrana\Core\BaseModel;

class UploadManager extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'upload_managers';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
