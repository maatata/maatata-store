<?php
/*
* Brand.php - Model file
*
* This file is part of the Brand component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand\Models;

use App\Yantrana\Core\BaseModel;
use App\Yantrana\Components\Product\Models\Product;
use Cache;

class Brand extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'brands';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.brands.all.active',
        'cache.brands.all.inactive',
    ];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * @var array - The attributes that should be casted to native types.
     */
    protected $casts = [
        '_id' => 'integer',
        'status' => 'integer',
        'brandID' => 'integer',
    ];

    /**
     * Scope a query to only include active brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to only include id brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFetchByID($query, $id)
    {
        return $query->where('_id', $id);
    }

    /**
     * Scope a query to only include ids brand.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFetchByIDs($query, $ids)
    {
        return $query->whereIn('_id', $ids);
    }

    /**
     * This method define the relationship b/w brand & product.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function productCount()
    {
        return $this->hasMany(Product::class, 'brands__id', '_id')
                    ->select('brands__id');
    }

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = ['_id', 'name', 'created_at', 'updated_at', 'description', 'logo', 'status'];
}
