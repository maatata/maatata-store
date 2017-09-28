<?php
/*
* SupportEngine.php - Main component file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support;

use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Support\Blueprints\SupportEngineBlueprint;

class SupportEngine implements SupportEngineBlueprint
{
    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * Constructor.
     *
     * @param SupportRepository $supportRepository - Support Repository
     *-----------------------------------------------------------------------*/
    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }
}
