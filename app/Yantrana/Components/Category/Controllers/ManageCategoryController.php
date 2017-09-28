<?php
/*
* ManageCategoryController.php - Controller file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Controllers;

use App\Yantrana\Core\BaseController;
use Illuminate\Http\Request;
use App\Yantrana\Components\Category\Categories;
use App\Yantrana\Components\Category\ManageCategoryEngine;
use App\Yantrana\Components\Category\Requests\CategoryAddRequest;
use App\Yantrana\Components\Category\Requests\ManageCategoryEditRequest;
use App\Yantrana\Components\Category\Requests\CategoryDeleteRequest;

class ManageCategoryController extends BaseController
{
    /**
     * @var ManageCategoryEngine - Category Engine
     */
    protected $manageCategoryEngine;

    /**
     * @var Categories - Categories
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param ManageCategoryEngine $manageCategoryEngine - Category Engine
     * @param Categories           $categories           - Categories
     *-----------------------------------------------------------------------*/
    public function __construct(ManageCategoryEngine $manageCategoryEngine, Categories $categories)
    {
        $this->manageCategoryEngine = $manageCategoryEngine;
        $this->categories = $categories;
    }

    /**
     * Take all_child_categories.
     *
     * @param (object) $itemCollection.
     * @param (int)    $itemID.
     * @param (array)  $activeItemsContainer.
     *
     * @return array
     *------------------------------------------------------------------------ */
    private function findChildrens($itemCollection, $itemID = null, $activeItemsContainer = [])
    {
        $itemID = (int) $itemID;

        foreach ($itemCollection as $item) {
            if ($item->parent_id == (int) $itemID) {
                $activeItemsContainer[] = [
                    'id' => $item->id,
                    'status' => $item->status,
                ];

                $activeItemsContainer = $this->findChildrens(
                                            $itemCollection,
                                            $item->id,
                                            $activeItemsContainer
                                        );
            }
        }

        return $activeItemsContainer;
    }

    private function findChildIds($itemCollection, $itemID = null, $activeItemsContainer = [])
    {
        $itemID = (int) $itemID;

        foreach ($itemCollection as $item) {
            if ($item->parent_id == (int) $itemID) {
                $activeItemsContainer[] = $item->id;
                $activeItemsContainer[] = findChilds($itemCollection, $item->id, $activeItemsContainer);
            }
        }

        return $activeItemsContainer;
    }

    /**
     * Manupution for active  & inactive category count.
     *
     * @param int $ID
     *
     * @return array
     *---------------------------------------------------------------- */
    private function findChildStatus($ID)
    {
        $allCategories = $this->manageCategoryEngine->getAll();
        $childCategories = $this->findChildrens(
                                    $allCategories,
                                    $ID
                                );

        $activeCatcount = $inActiveCatcount = [];

        foreach ($childCategories as $category) {
            if ($category['status'] === 1) {
                $activeCatcount[] = $category['id'];
            } else {
                $inActiveCatcount[] = $category['id'];
            }
        }

        return [
            'active' => count($activeCatcount),
            'inActive' => count($inActiveCatcount),
        ];
    }

    /**
     * get all categories.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index(Request $request, $mCategoryID)
    {
        $categories = $this->manageCategoryEngine
                            ->getCategories($mCategoryID, $request->all());

        $allCategories = $this->manageCategoryEngine->getAll();

        $requireColumns = [

           'childCount' => function ($key) {
                   // return active | deactive category count
                return $this->findChildStatus($key['id']);

            },
            'currentCategoryProductsCount' => function ($key) use ($allCategories) {

                // Get all category related products count.
                $products = $this->manageCategoryEngine
                                    ->getCurrentCategoryProducts(
                                      $key['id']
                                    );

                return count($products);

            },
            'totalCategoryProductCount' => function ($key) use ($allCategories) {

                $getChildrens = findChilds($allCategories, $key['id']);

                // Get all category related products count.
                $categoriesProductCount = $this->manageCategoryEngine
                                                 ->getCategoriesProducts(
                                                   array_unique(array_flatten($getChildrens))
                                                 );

                return count($categoriesProductCount);

            },
            'id', 'name', 'status',
        ];

        return __dataTable($categories, $requireColumns);
    }

    /**
     * getDetails.
     *
     * @param catID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getSupportData($catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->getSupportData($catID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Category does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * get all categories for fancytree.
     *---------------------------------------------------------------- */
    public function fancytreeSupportData()
    {
        $processReaction = $this->manageCategoryEngine
                                ->getAllCategories();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle add category request.
     *
     * @param object CategoryAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function add(CategoryAddRequest $request)
    {
        $processReaction = $this->manageCategoryEngine
                                ->processAdd($request->all());

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Category added successfully.'),
                    18 => __('Parent category not exist.'),
                    3 => __('Category name already exist.'),
                ], $processReaction['data']);
    }

    /**
     * getDetails.
     *
     * @param catID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetails($catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->getDetails($catID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Category does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * update category data.
     *
     * @param $catID 
     * @param ManageCategoryEditRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function update(ManageCategoryEditRequest $request, $catID)
    {
        if (empty($catID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageCategoryEngine
                                ->processUpdate($request->all(), $catID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Category updated successfully.'),
                    14 => __('Nothing updated.'),
                    18 => __('Category does not exist.'),
                    3 => __('Category name already exist.'),
                ], $processReaction['data']);
    }

    /**
     * delete category.
     *
     * @param int $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($categoryID, CategoryDeleteRequest $request)
    {
        if (empty($categoryID)) {
            return __apiResponse([], 7);
        }

        $enginReaction = $this->manageCategoryEngine->processDelete($categoryID, $request->all());

        // get engine reaction						
        return __processResponse($enginReaction, [
                    1 => __('Category deleted successfully.'),
                    3 => __('Invalid password. please enter correct password.'),
                    18 => __('Category does not exist.'),
                ], $enginReaction['data']);
    }
}
