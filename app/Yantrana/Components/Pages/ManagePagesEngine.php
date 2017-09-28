<?php
/*
* ManagePagesEngine.php - Main component file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages;

use App\Yantrana\Components\Pages\Repositories\ManagePagesRepository;
use App\Yantrana\Components\Pages\Blueprints\ManagePagesEngineBlueprint;

class ManagePagesEngine implements ManagePagesEngineBlueprint
{
    /**
     * @var ManagePagesRepository - ManagePages Repository
     */
    protected $managePagesRepository;

    /**
     * Constructor.
     *
     * @param ManagePagesRepository $managePagesRepository - ManagePages Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ManagePagesRepository $managePagesRepository)
    {
        $this->managePagesRepository = $managePagesRepository;
    }

    /**
     * get all pages.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getDatableSource($pageID, $searchText = null)
    {
        return $this->managePagesRepository
                    ->fetchDatableSource($pageID, $searchText);
    }

    /**
     * get all pages.
     *
     * @param param1 type 
     *---------------------------------------------------------------- */
    public function fetchAllPages()
    {
        return $this->managePagesRepository->fetchAllPages();
    }

    /**
     * Process add new page.
     *
     * @param array $inputData
     *---------------------------------------------------------------- */
    public function prepareForAddNewPage($inputData)
    {
        //Check if page added
        if ($this->managePagesRepository->store($inputData)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * description.
     *
     * @param param1 type 
     *---------------------------------------------------------------- */
    public function getPagesData()
    {
        $pageData = [];
        $pages = $this->fetchAllPages();

        if (!empty($pages)) {
            foreach ($this->fetchAllPages() as $key => $page) {
                if ($page->type !== 3) {
                    $pageData[] = $page;
                }
            }
        }

        return fancytreeSource($pageData);
    }

    /**
     * Get page details.
     *
     * @param int $pageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getDetails($pageID)
    {
        $reservePageIds = config('__tech.reserve_pages');

        // Check if given page id is reserve pageId
        if (in_array($pageID, $reservePageIds)) {
            return __engineReaction(18);
        }

        // fetch page details
        $page = $this->managePagesRepository->fetchDetails($pageID);

        // Check if page empty or not
        if (empty($page)) {
            return __engineReaction(18);
        }

        $linkDetails = $page->link_details;
        $description = $page->description;
        $pageType = $page->type;

        $pageDetails = [
            'title' => $page->title,
            'status' => ($page->status == 1) ? true : false,
            'add_to_menu' => ($page->add_to_menu == 1) ? true : false,
            'parent_page' => $page->parent_id,
            'hide_sidebar' => ($page->hide_sidebar == 0) ? true : false,
            'id' => $page->id,
            'type' => $pageType,
            'fancytree_data' => $this->getPagesData()
        ];

        if ($pageType == 1) {
            $pageDetails['description'] = $description;
            $pageDetails['link'] = '';
            $pageDetails['open_as'] = '';
        } elseif ($pageType == 2) {
            $linkArray = json_decode($linkDetails, true);
            $pageDetails['link'] = $linkArray['value'];
            $pageDetails['open_as'] = $linkArray['type'];
            $pageDetails['description'] = '';
        }

        // Get page type and page link open as config array
        $configItems = [
            'pageType' => getSelectizeOptions(
                            '__tech.pages_types',
                            '__tech.pages_type_codes'
                        ),
            'pageLinks' => getSelectizeOptions(
                            '__tech.link_target',
                            '__tech.link_target_array'
                        ),
        ];

        return __engineReaction(1, [
            'pageDetails' => $pageDetails,
            'configItems' => $configItems,
        ]);
    }

    /**
     * Process update request if page exist else return response.
     *
     * @param int   $pageID
     * @param array $input
     * 
     * @return reaction code
     *---------------------------------------------------------------- */
    public function processUpdate($pageID, $input)
    {
        $page = $this->managePagesRepository->fetchDetails($pageID);

        // Check if page exist
        if (empty($page)) {
            return __engineReaction(18);
        }

        extract($input);

        $pageType = $type;

        // Check if parent page not exist
        if (empty($parent_page) or $parent_page === 0) {
            $parent_page = null;
        }

        $addToMenu = 1;

        // Check if page not menu item
        if (empty($add_to_menu) || $add_to_menu == false) {
            $addToMenu = 2;
        }

        $active = 1;

        // Check if page not active
        if (empty($status) || $status == false) {
            $active = 2;
        }

        //check hide_sidebar
        if (empty($hide_sidebar)) {
            $hide_sidebar = 1;
        } elseif ($hide_sidebar == true) { // yes it is hide

            $hide_sidebar = 0;
        }

        $pageArray = [
            'title' => $title,
            'add_to_menu' => $addToMenu,
            'parent_id' => $parent_page,
            'type' => $pageType,
            'status' => $active,
            'hide_sidebar' => $hide_sidebar,
        ];

        // Check if page type is link
        if ($pageType == 2) {
            $linkArray = [
                      'value' => $link,
                      'type' => $open_as,
                ];

            $pageArray['link_details'] = json_encode($linkArray);
        } else {
            $pageArray['description'] = $description;
        }

        // Check if page updated
        if ($this->managePagesRepository->update($page, $pageArray)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Process delete.
     *
     * @param int pageID 
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($pageID)
    {
        $reservePageIds = config('__tech.reserve_pages');

        // Check if given page id is reserve pageId
        if (in_array($pageID, $reservePageIds)) {
            return __engineReaction(3);
        }

        $page = $this->managePagesRepository->findByID($pageID);

        // Check if page not empty
        if (empty($page)) {
            return __engineReaction(18);
        }

        // find child pages
        $child = $this->managePagesRepository->findChildPages($page->id);

        // Check if child page exist
        if (!__isEmpty($child)) {
            return __engineReaction(2);
        }

        // Check if page deleted
        if ($this->managePagesRepository->delete($page)) {
            return __engineReaction(1);
        }

        return __engineReaction(18);
    }

    /**
     * Process for list order update request.
     *
     * @param array $input
     *
     * @return reaction number
     *---------------------------------------------------------------- */
    public function prepareListOrder($input)
    {
        // Check if list order updated
        if ($this->managePagesRepository->updateListOrder($input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * get parent page data.
     *
     * @param array $pageID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function getParentPageData($pageID)
    {
        $parentPage = $this->managePagesRepository
                           ->fetchParentPageData($pageID);

        // Check if parent page exist
        if (!__isEmpty($parentPage)) {
            return __engineReaction(1, $parentPage);
        }

        return __engineReaction(18);
    }
}
