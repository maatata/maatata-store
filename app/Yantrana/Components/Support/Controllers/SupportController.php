<?php
/*
* SupportController.php - Controller file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Support\SupportEngine;

class SupportController extends BaseController
{
    /**
     * @var SupportEngine - Support Engine
     */
    protected $supportEngine;

    /**
     * Constructor.
     *
     * @param SupportEngine $supportEngine - Support Engine
     *-----------------------------------------------------------------------*/
    public function __construct(SupportEngine $supportEngine)
    {
        $this->supportEngine = $supportEngine;
    }
}
