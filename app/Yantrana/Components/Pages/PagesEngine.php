<?php
/*
* PagesEngine.php - Main component file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages;

use Breadcrumb;
use App\Yantrana\Components\Pages\Repositories\PagesRepository;
use App\Yantrana\Components\Pages\Blueprints\PagesEngineBlueprint;

class PagesEngine implements PagesEngineBlueprint
{
    /**
     * @var PagesRepository - Pages Repository
     */
    protected $pagesRepository;

    /**
     * Constructor.
     *
     * @param PagesRepository $pagesRepository - Pages Repository
     *-----------------------------------------------------------------------*/
    public function __construct(PagesRepository $pagesRepository)
    {
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * Prepare details if page exist or valid.
     *
     * @param int $pageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getDetails($pageID)
    {
        $reservePageIds = config('__tech.reserve_pages_ids');

        // Check if this is reserve page
        if (in_array($pageID, $reservePageIds)) {
            return __engineReaction(18);
        }

        $page = $this->pagesRepository->fetchDetails($pageID);

        // Check if page empty
        if (empty($page)) {
            return __engineReaction(18);
        }

        // Check if this page is Deactive
        if (!isAdmin() and $page->status == 2) {
            return __engineReaction(18);
        }

        $pageDetails = [
            'title' 	  => $page->title,
            'id' 		  => $page->id,
            'description' => $page->description,
            'hideSidebar' => $page->hide_sidebar,
            'status' 	  => $page->status,
        ];

        return __engineReaction(1, [
                'pageDetails' => $pageDetails,
                'breadCrumb'  => Breadcrumb::generate('pages', $pageID),
            ]);
    }
}
