<?php
/*
* DashboardController.php - Controller file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Dashboard\DashboardEngine;

class DashboardController extends BaseController
{
    /**
     * @var DashboardEngine - Dashboard Engine
     */
    protected $dashboardEngine;

    /**
     * Constructor.
     *
     * @param DashboardEngine $dashboardEngine - Dashboard Engine
     *-----------------------------------------------------------------------*/
    public function __construct(DashboardEngine $dashboardEngine)
    {
        $this->dashboardEngine = $dashboardEngine;
    }

    /**
     * get dashboard list.
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function dashboardSupportData()
    {
        $processReaction = $this->dashboardEngine
                                ->prepareDashboardSupportData();

        return __processResponse($processReaction, [], [
            'dashboard' => $processReaction['data'], ]);
    }
}
