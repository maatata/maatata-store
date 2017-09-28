<?php
/*
* StoreController.php - Controller file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Store\StoreEngine;

class StoreController extends BaseController
{
    /**
     * @var StoreEngine - Store Engine
     */
    protected $storeEngine;

    /**
     * Constructor.
     *
     * @param StoreEngine $storeEngine - Store Engine
     *-----------------------------------------------------------------------*/
    public function __construct(StoreEngine $storeEngine)
    {
        $this->storeEngine = $storeEngine;
    }
}
