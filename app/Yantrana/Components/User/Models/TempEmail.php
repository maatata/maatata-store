<?php

namespace App\Yantrana\Components\User\Models;

use App\Yantrana\Core\BaseModel;

class TempEmail extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'temp_emails';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'users_id' => 'integer',
    ];
}
