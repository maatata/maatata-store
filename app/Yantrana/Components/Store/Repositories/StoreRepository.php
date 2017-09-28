<?php
/*
* StoreRepository.php - Repository file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Store\Models\Store as StoreModel;
use App\Yantrana\Components\Store\Blueprints\StoreRepositoryBlueprint;

class StoreRepository extends BaseRepository
                          implements StoreRepositoryBlueprint
{
    /**
     * @var StoreModel - Store Model
     */
    protected $store;

    /**
     * Constructor.
     *
     * @param StoreModel $store - Store Model
     *-----------------------------------------------------------------------*/
    public function __construct(StoreModel $store)
    {
        $this->store = $store;
    }
}
