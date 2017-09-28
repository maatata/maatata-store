<?php
/*
* ManagePagesRepository.php - Repository file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Pages\Models\Page;
use App\Yantrana\Components\Pages\Blueprints\ManagePagesRepositoryBlueprint;
use File;

class ManagePagesRepository extends BaseRepository
                          implements ManagePagesRepositoryBlueprint
{
    /**
     * @var Page - page Model
     */
    protected $managePages;

    /**
     * Constructor.
     *
     * @param Page $page - page Model
     *-----------------------------------------------------------------------*/
    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /**
     * fetch all pages list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchDatableSource($pageID, $searchText = null)
    {
        $dataTablesConfig = [
            'searchable' => [
                'title',
                'description',
                'link_details',
            ],
        ];

        $search = $searchText['search'];

        // check is it null
        if ($pageID and $pageID === 'null') {
            $pageID = null;
        }

        if (__isEmpty($search['value'])) {
            $query = $this->page->where('parent_id', $pageID);
        } else {
            $query = $this->page;
        }

        return $query->dataTables($dataTablesConfig)
                     ->toArray();
    }

    /**
     *  fetch all pages list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchAllPages()
    {
        return $this->page->all();
    }

    /**
     *  fetch max list order number in list.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMaxListOrder()
    {
        return  $this->page->max('list_order');
    }

    /**
     * store new page.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function store($input)
    {
        extract($input);

        $page = new $this->page();

        //check add to menu
        if (empty($add_to_menu)  or $add_to_menu == false) {
            $add_to_menu = 2;
        }

        //check status
        if (empty($active) or $active == false) {
            $active = 2;
        }

        //check parent page
        if (empty($parent_page) or $parent_page === 0) {
            $parent_page = null;
        }

        //check hide_sidebar
        if (empty($hide_sidebar)) {
            $hide_sidebar = 1;
        } elseif ($hide_sidebar === true) { // yes it is hide

            $hide_sidebar = 0;
        }

        $listOrder = $this->fetchMaxListOrder();

        $page->title = $title;
        $page->type = $type;
        $page->add_to_menu = $add_to_menu;
        $page->status = $active;
        $page->hide_sidebar = $hide_sidebar;
        $page->list_order = $listOrder + 1;
        $page->parent_id = $parent_page;

        if ($type == 1) {
            $page->description = $description;
        } elseif ($type == 2) {
            $linkArray = [
                'value' => $link,
                'type' => $open_as,
            ];

            $link = json_encode($linkArray);
            $page->link_details = $link;
        }

        // Check if page stored
        if ($page->save()) {
            activityLog('ID of '.$page->id.' page added.');

            return true;
        }

        return false;
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
        return $this->page
                    ->where('id', $pageID)
                    ->select(
                        'id',
                        'title',
                        'status',
                        'add_to_menu',
                        'parent_id',
                        'type',
                        'description',
                        'link_details',
                        'hide_sidebar'
                    )
                    ->first();
    }

    /**
     * update pages List Order.
     *
     * @param array $pagesData
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateListOrder($pagesData)
    {
        // Check if pages list order updated
        if ($this->page->batchUpdate($pagesData, 'id')) {

            //activityLog('ID of '.$page->id.' pages list order updated.');

            return true;
        }

        return false;
    }

    /**
     * Update page.
     *
     * @param array $page
     * @param array $updateData
     * 
     * @return updated array
     *---------------------------------------------------------------- */
    public function update($page, $updateData)
    {
        // Check if page updated
        if ($page->modelUpdate($updateData)) {
            activityLog('ID of '.$page->id.' page update.');

            return true;
        }

        return false;
    }

    /**
     * Fetch by id.
     *
     * @param int $pageID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function findByID($pageID)
    {
        return $this->page->find($pageID);
    }

    /**
     * Fetch a child page.
     *
     * @param int $pageID
     * 
     * @return eloquent collection object
     *------------------------------------------------------------------------ */
    public function findChildPages($pageID)
    {
        return $this->page->whereParent_id($pageID)
                          ->get()
                          ->toArray();
    }

    /**
     * Delete page.
     *
     * @param object $page
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($page)
    {
        // Check if page deleted
        if ($page->delete()) {
            activityLog('ID of '.$page->id.' page deleted.');

            return  true;
        }

        return false;
    }

    /**
     * fetch parent page data.
     *
     * @param int $pageID
     *
     * @return eloqunt collection object
     *---------------------------------------------------------------- */
    public function fetchParentPageData($pageID)
    {
        return $this->page
                    ->where('id', $pageID)
                    ->first([
                        'parent_id',
                        'title',
                    ]);
    }

    /**
     * fetch all active & add to menu pages lists.
     *
     * @return Eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllActiveAndAddToMenu()
    {
        return $this->viaCache('cache.pages.active.addtomenu.all', function () {

            return $this->page
                        ->orderBy('list_order')
                        ->where([
                            'status' => 1, // active 
                            'add_to_menu' => 1, // add page in menu yes
                        ])->select(
                            'id',
                            'title',
                            'type',
                            'list_order',
                            'link_details',
                            'parent_id'
                        )
                        ->get();
        });
    }
}
