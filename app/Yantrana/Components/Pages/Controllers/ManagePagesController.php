<?php
/*
* ManagePagesController.php - Controller file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Controllers;

use Illuminate\Http\Request;
use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Pages\ManagePagesEngine;
use App\Yantrana\Components\Pages\Requests\ManagePagesAddRequest;
use App\Yantrana\Components\Pages\Requests\ManagePagesEditRequest;

class ManagePagesController extends BaseController
{
    /**
     * @var ManagePagesEngine - ManagePages Engine
     */

    /**
     * Constructor.
     *
     * @param ManagePagesEngine $managePagesEngine - ManagePages Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManagePagesEngine $managePagesEngine)
    {
        $this->managePagesEngine = $managePagesEngine;
    }

    /**
     * Handle page list request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function index(Request $request, $pageID)
    {
        $pages = $this->managePagesEngine
                            ->getDatableSource($pageID, $request->all());

        $requireColumns = [

            // created page date
            'formatted_created_at' => function ($key) {

             return formatStoreDate($key['created_at']);

            },
            'slug_title' => function ($key) {

             return str_slug($key['title']);

            },
            // updated page date
            'formatted_updated_at' => function ($key) {

             return formatStoreDate($key['updated_at']);

            },
            // page active or not
            'active' => function ($key) {

               return getTitle($key['status'], '__tech.pages_status_codes');

            },
            // page add to menu or not
            'add_to_menu' => function ($key) {

               return getTitle($key['add_to_menu'], '__tech.pages_status_codes');

            },
            // page type 
            'formated_type' => function ($key) {

                return getTypeTitle($key['type']);

            },
            //  external_page 
            'external_page' => function ($key) {

                return route('display.page.details', [
                        'pageID' => $key['id'],
                        'pageName' => str_slug($key['title']),
                    ]);

            },
            // page type 
            'link' => function ($key) {
                $link = json_decode($key['link_details'], true);

                return [
                   'url' => $link['value'],
                   'target' => $link['type'],
                ];
            },
              'id',
            'title',
            'parent_id',
            'list_order',
            'type',
        ];

        return __dataTable($pages, $requireColumns);
    }

    /**
     * Handle get pages type request.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getPagesType()
    {
        $pageType = getSelectizeOptions(
                    '__tech.pages_types',
                    '__tech.pages_type_codes'
                );

        $pageLinks = getSelectizeOptions(
                    '__tech.link_target',
                    '__tech.link_target_array'
                );

        // get engine reaction						
        return __processResponse(1,
                    [],
                    [
                        'type' => $pageType,
                        'link' => $pageLinks,
                        'fancytree_data' => $this->managePagesEngine
                                                        ->getPagesData(),
                 ]);
    }

    /**
     * Handle update list order request.
     * 
     * @param array Request $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function updateListOrder(Request $request)
    {
        $processReaction = $this->managePagesEngine
                                ->prepareListOrder(
                                    $request->input('pages_list_order')
                                );

        // get engine reaction                      
        return __processResponse($processReaction, [
                    1 => __('List order updated successfully.'),
                    14 => __('Nothing updated'),
                ], $processReaction['data']);
    }

    /**
     * Handle add new page request.
     *
     * @param ManagePagesAddRequest $request
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function add(ManagePagesAddRequest $request)
    {	
        $processReaction = $this->managePagesEngine
                                ->prepareForAddNewPage($request->all());

        return __processResponse($processReaction, [
                    1 => __('Page added successfully.'),
                    2 => __('Page not added.'),
                ]);
    }

    /**
     * Handle details data request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetails($pageID)
    {
        $processReaction = $this->managePagesEngine->getDetails($pageID);

        return __processResponse($processReaction, [
                18 => __('Page does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle update page request.
     *
     * @param numeric                         $pageID
     * @param array    ManagePagesEditRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function update($pageID, ManagePagesEditRequest $request)
    {
        $processReaction = $this->managePagesEngine
                                ->processUpdate($pageID, $request->all());

        return __processResponse($processReaction, [
                    1 => __('Page updated successfully.'),
                    18 => __('Page does not exist.'),
                    14 => __('Nothing update.'),
                ]);
    }

    /**
     * Handle delete page data request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($pageID, CommonPostRequest $request)
    {
        $processReaction = $this->managePagesEngine->processDelete($pageID);

        return __processResponse($processReaction, [
                    1 => __('Page deleted successfully.'),
                    3 => __('Something went wrong.'),
                    18 => __('Page does not exist.'),
                    2 => __('This menu/page item can not be deleted because it contains childrens items.'),
                ]);
    }

    /**
     * Handle display page details request.
     *
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function displayPageDetails($pageID)
    {
        $processReaction = $this->managePagesEngine
                                ->getDetails($pageID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Page does not exist.'),
                ], null, true);
    }

    /**
     * Handle get parent page data request.
     * 
     * @param int $pageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getParentPage($pageID)
    {
        $processReaction = $this->managePagesEngine
                                ->getParentPageData($pageID);

        return __processResponse($processReaction, [
                    18 => 'Page does not exist.',
                ], $processReaction['data']);
    }
}
