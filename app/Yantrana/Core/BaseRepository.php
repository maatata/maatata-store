<?php

namespace App\Yantrana\Core;

use App\Yantrana\__Laraware\Core\CoreRepository;

class BaseRepository extends CoreRepository
{
    /**
     * Fetch pagination count.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function getPaginationCount()
    {
        return (getStoreSettings('pagination_count')) ? getStoreSettings('pagination_count') : config('__tech.pagination_count');
    }
}
