<?php
/*
* PagesRepository.php - Repository file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Pages\Models\Page as PagesModel;
use App\Yantrana\Components\Pages\Blueprints\PagesRepositoryBlueprint;

class PagesRepository extends BaseRepository
                          implements PagesRepositoryBlueprint
{
    /**
     * @var PagesModel - Pages Model
     */
    protected $pages;

    /**
     * Constructor.
     *
     * @param PagesModel $pages - Pages Model
     *-----------------------------------------------------------------------*/
    public function __construct(PagesModel $pages)
    {
        $this->pages = $pages;
    }

    /**
     * fetch page details.
     *
     * @param int $pageID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetails($pageID)
    {
        return $this->pages
                    ->where('id', $pageID)
                    ->select(
                        'id',
                        'title',
                        'status',
                        'add_to_menu',
                        'parent_id',
                        'type',
                        'parent_id',
                        'description',
                        'hide_sidebar'
                    )
                    ->first();
    }
}
