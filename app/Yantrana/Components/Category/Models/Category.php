<?php
/*
* Category.php - Model file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Models;

use App\Yantrana\Core\BaseModel;
use Cache;

class Category extends BaseModel
{
    /**
     * @var string - The database table used by the model.
     */
    protected $table = 'categories';

    /**
     * Caching Ids related to this model which may need to clear on add/update/delete.
     *
     * @var string
     *----------------------------------------------------------------------- */
    protected $cacheIds = [
        'cache.categories.all',
        'cache.categories.all.active',
        'cache.categories.where.active.first',
    ];

    /**
     * @var array - The attributes that should be casted to native types..
     */
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'parent_id' => 'integer',
        'name' => 'string',
    ];

    /**
     * @var array - The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * Scope a query to only include status.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->whereStatus($status);
    }

    /**
     * Scope a query to only include search id.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereCatId($query, $ID)
    {
        return $query->whereId($ID);
    }

    /**
     * Scope a query to only include selected field.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectField($query)
    {
        return $query->select('id', 'name', 'status', 'parent_id');
    }

    /**
     * Scope a query to only include name.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($query, $name)
    {
        return $query->whereName($name);
    }

    /**
     * Scope a query to only include name.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParent($query, $parentId)
    {
        return $query->whereParent_id($parentId);
    }
}
