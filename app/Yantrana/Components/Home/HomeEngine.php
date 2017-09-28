<?php
/*
* HomeEngine.php - Main component file
*
* This file is part of the Home component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Home;

use App\Yantrana\Components\Home\Repositories\HomeRepository;
use App\Yantrana\Components\Home\Blueprints\HomeEngineBlueprint;

class HomeEngine implements HomeEngineBlueprint
{
    /**
     * @var HomeRepository - Home Repository
     */
    protected $homeRepository;

    /**
     * Constructor.
     *
     * @param HomeRepository $homeRepository - Home Repository
     *-----------------------------------------------------------------------*/
    public function __construct(HomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
    }
}
