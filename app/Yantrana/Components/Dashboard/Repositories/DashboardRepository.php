<?php
/*
* DashboardRepository.php - Repository file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Dashboard\Models\Dashboard as DashboardModel;
use App\Yantrana\Components\Dashboard\Blueprints\DashboardRepositoryBlueprint;

class DashboardRepository extends BaseRepository
                          implements DashboardRepositoryBlueprint
{
    /**
     * @var DashboardModel - Dashboard Model
     */
    protected $dashboardModel;

    /**
     * Constructor.
     *
     * @param DashboardModel $dashboardModel - Dashboard Model
     *-----------------------------------------------------------------------*/
    public function __construct(DashboardModel $dashboardModel)
    {
        $this->dashboardModel = $dashboardModel;
    }
}
