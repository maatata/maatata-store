<?php
/*
* StoreEngine.php - Main component file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store;

use App\Yantrana\Components\Store\Repositories\StoreRepository;
use App\Yantrana\Components\Store\Blueprints\StoreEngineBlueprint;

class StoreEngine implements StoreEngineBlueprint
{
    /**
     * @var StoreRepository - Store Repository
     */
    protected $storeRepository;

    /**
     * Constructor.
     *
     * @param StoreRepository $storeRepository - Store Repository
     *-----------------------------------------------------------------------*/
    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }
}
