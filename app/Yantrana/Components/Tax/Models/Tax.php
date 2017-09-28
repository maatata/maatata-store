<?php
/*
* Tax.php - Model file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Models;

use App\Yantrana\Core\BaseModel;

class Tax extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'tax';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'applicable_tax' => 'float',
        'type' => 'integer',
        'countries__id' => 'integer',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * Define float format of tax table applicable_tax attribute.
     *
     * @param $amount
     */
    public function setApplicableTaxAttribute($amount)
    {
        return $this->attributes['applicable_tax'] = formatAmount($amount);
    }

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];
}
