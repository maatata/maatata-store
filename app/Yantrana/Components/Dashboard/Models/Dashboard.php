<?php
/*
* Dashboard.php - Model file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard\Models;

use App\Yantrana\Core\BaseModel;

class Dashboard extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'dashboards';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
