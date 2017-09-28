<?php
/*
* Report.php - Model file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report\Models;

use App\Yantrana\Core\BaseModel;

class Report extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'reports';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
