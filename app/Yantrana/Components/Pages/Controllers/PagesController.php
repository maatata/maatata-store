<?php
/*
* PagesController.php - Controller file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Pages\PagesEngine;

class PagesController extends BaseController
{
    /**
     * @var PagesEngine - Pages Engine
     */
    protected $pagesEngine;

    /**
     * Constructor.
     *
     * @param PagesEngine $pagesEngine - Pages Engine
     *-----------------------------------------------------------------------*/
    public function __construct(PagesEngine $pagesEngine)
    {
        $this->pagesEngine = $pagesEngine;
    }

    /**
     * Page details.
     *
     * @param int $pageID
     * @param int $pageTitle
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function displayPageDetails($pageID)
    {
        $pageID = (int) $pageID;

        // Check if pageId is 1
        if ($pageID === 1) {
            return redirect()->route('home.page');
        }

        $processReaction = $this->pagesEngine->getDetails($pageID);

        // Check if page not exist
        if ($processReaction['reaction_code'] === 18) {
            return $this->loadPublicView('errors.public-not-found');
        }

        return $this->loadPublicView('pages.display-details', $processReaction['data']);
    }
}
