<?php

namespace App\Yantrana\Components\Category;

/*
* Store categories class
*
* PHP version 5.5.9 or newer
*
* @category   class  
* @package    Maatata Store
* @author     Mohammad Bagheri
* 
*---------------------------------------------------------------------------- */

use Illuminate\Support\Str;
use Config;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;

/**
 *-----------------------     CATEGORIES CLASS   ------------------------------ *.
 *
 * Categories class get use of recursive data
 *
 * @return json
 *---------------------------------------------------------------------------- */
class Categories
{
    private $selectedCategory;

    // expand selected categories array 
    private $categoryRepos;

    // expand selected categories array 
    private $expandSelectedCatgeory;

    // get all categories 
    private $categories;

    // get all parent categories 
    private $allMyParents;

    // get all child categories names 
    private $allMyChilds;

    // get all child categories ids 
    private $allMyChildsIDs;

    // listingFor for categories or products 
    private $listingFor;

    // categoriesMarkup for menu tree 
    private $categoriesMarkup;

    // categoriesNevMarkup for menu tree 
    private $categoriesNevMarkup;

    // product related categories array 
    private $productCategories;

    // categories status  
    private $configStatus;

    // side-bar-categories 
    private $sidebarCategories;

    /*
    * Constructor
    *
    * @return response
    * 
    *------------------------------------------------------------------------ */

    public function __construct(ManageCategoryRepository $manageCategoryRepository)
    {
        $this->categoryRepos = $manageCategoryRepository;
        $this->categoriesMarkup = '';
        $this->categoriesNevMarkup = '';
        $this->configStatus = Config::get('store_techs.category.status');
        $this->categories = $this->categoryRepos->fetchAll()->toArray();
        $this->allMyChilds = [];
        $this->allMyChildsIDs = [];
    }

    /**
     * List all categories.
     *
     * @param (string) $listingFor.
     * @param (int)    $categoryID.
     * @param (int)    $productID.
     * @param (string) $requestType.
     * @param (string) $catRequestType.
     *
     * @return Json
     *----------------------------------------------------------------------- */
    public function categoryData(
      $listingFor = null,
      $requestType = null,
      $categoryID = null,
      $productID = null
    ) {
        $this->selectedCategory = $categoryID;
        $this->listingFor = $listingFor;
        $this->expandSelectedCatgeory = 0;

        if ($listingFor == 'categories') {
            //Listitng  for categories.
          if (!empty($this->categories)) {
              foreach ($this->categories as $key => $category) {
                  if (!empty($categoryID)) {
                      if ($category['id'] == $categoryID) {

                        // for category edit
                        if ($requestType == 'catEdit') {
                            $this->expandSelectedCatgeory = $category['parent_id'];

                            // current category hide
                            unset($this->categories[$key]);
                        } else {
                            $this->expandSelectedCatgeory = $category['id'];

                            $this->categories[$key];
                        }
                      }
                  }
              }

            // find all parents function
            $this->findAllParents($this->expandSelectedCatgeory);
          }
        } else {
            // Listitng  for products.
            if (!empty($this->categories)) {

                //for product edit selected categories
                $this->productCategories = $this->getProductCategories($productID);

                foreach ($this->categories as $category) {
                    if (!empty($categoryID)) {
                        if ($category['id'] == $categoryID) {
                            $this->expandSelectedCatgeory = $category['id'];
                        }
                    }
                }

                if ($requestType == 'productAdd') {

                  // find all parents function
                  $this->findAllParents($this->expandSelectedCatgeory);
                } else {

                  // find all parents function
                  $this->findAllParents($this->productCategories);
                }
            }
        }

        $noParent[0] = [
            'title' => __('make a parent'),
            'key' => -1,
            'parent_id' => null,
        ];

        $noCategories[0] = [
            'title' => __('Categories does not exist.'),
            'key' => -1,
            'parent_id' => null,
        ];

        $expandCategoriesArray = $this->buildTree(
                                              $this->categories,
                                              null,
                                              $this->expandSelectedCatgeory
                                            );

        // request type for edit category
        if ($requestType == 'catEdit') {

            // add index in categories list name as make a parent
            if (empty($expandCategoriesArray)) {
                return __apiResponse([
                                'categories' => $noParent,
                            ]);
            } else {
                $allTreeCates = array_merge($noParent, $expandCategoriesArray);

                // add index in categories list name as make a parent
                if (!empty($allTreeCates)) {
                    return __apiResponse([
                                'categories' => $allTreeCates,
                            ]);
                }
            }
        } else {
            if (empty($expandCategoriesArray)) {
                $emptyArray = $noCategories;

                    // categories empty in table display msg in input field
                    return __apiResponse([
                                'categories' => $emptyArray,
                            ]);
            } else {

                // request type for add new category
                if ($requestType == 'catAdd') {
                    $catArray = array_merge($noParent, $expandCategoriesArray);

                    return __apiResponse([
                                'categories' => $catArray,
                            ]);
                } else {
                    return __apiResponse([
                                'categories' => $expandCategoriesArray,
                            ]);
                }
            }
        }
    }
    /**
     * getProductCategories for Product Edit.
     *
     * @param  (int)    $productID product id     
     *                              
     * @return response
     *------------------------------------------------------------------------ */
    public function getProductCategories($productID = null)
    {
        if (!empty($productID)) {
            //return $this->categoryRepos->productBycategory($productID);
        }
    }

    /**
     * findAllParents of child category.
     *
     * @param  (int) $parentID category id     
     *                          
     * @return array
     *------------------------------------------------------------------------ */
    public function findAllParents($parentID = null)
    {
        if (empty($this->categories)) {
            return false;
        }

        // for expand categories tree
        foreach ($this->categories as $category) {
            // product edit take one or more parents ids mean array of IDs
            if (is_array($parentID)) {
                foreach ($parentID as $parent) {
                    if ($category['id'] == $parent) {
                        $this->allMyParents[] = $parentID;

                        if (!empty($category['parent_id'])) {
                            $this->findAllParents($category['parent_id']);
                        }
                    }
                }
            } else {

            // category edit take only one parent id single ID
            if ($category['id'] == $parentID) {
                $this->allMyParents[] = $parentID;

                if (!empty($category['parent_id'])) {
                    $this->findAllParents($category['parent_id']);
                }
            }
            }
        }
    }

    /**
     * getAllChilds of parent category.
     *
     * @param  (int) $categoryID category id     
     *                            
     * @return array
     *------------------------------------------------------------------------ */
    public function getAllChilds($categoryID = null)
    {
        if (empty($this->categories)) {
            return false;
        }

        foreach ($this->categories as $key => $category) {
            if ($category['parent_id'] == $categoryID) {
                if (!in_array($category['id'], $this->allMyChilds)) {
                    $this->allMyChilds[$category['id']] = $category['name'];

                    $this->allMyChildsIDs[] = $category['id'];
                }

                $this->getAllChilds($category['id']);
            }
        }

        // get only Categories id & Name
        return [
            'allMyChilds' => $this->allMyChilds,
            'allMyChildsIDs' => $this->allMyChildsIDs,
        ];
    }

    /**
     * getActiveChilds of parent category.
     *
     * @param  (int) $categoryID.
     *                             
     * @return array
     *------------------------------------------------------------------------ */
    public function getActiveChilds($categoryID = null)
    {
        foreach ($this->categories as $key => $category) {
            if (($category['parent_id'] == $categoryID)
                              &&
            ($category['status'] == $this->configStatus['active']['id'])) {
                if (!in_array($category['id'], $this->allMyChilds)) {
                    $this->allMyChilds[] = $category['id'];
                }

                $this->getActiveChilds($category['id']);
            }
        }

        return $this->allMyChilds;
    }

    /**
     * return parent id is null & category and status is 0.
     *
     * @param [numeric] $categoryID category child id
     *
     * @return array
     *------------------------------------------------------------------------ */
    public function parentNullCategories($categoryID = null)
    {
        // call the function of find all parents of related category ID ($categoryID)
        $parent = $this->findAllParents($categoryID);

        // fetch parent id with his status is 1
        $parentsCategories = $this->categoryRepos
                                  ->fetchParentIdRecord($this->allMyParents);

        // fetch parent id with his status is 0
        $parentsStatus = $this->categoryRepos
                                  ->fetchParentIdWithStatusRecord($this->allMyParents);
        $parentIDs = [];

        foreach ($parentsStatus as $parentsCategoryStatus) {
            $parentIDs[$parentsCategoryStatus['id']] = $parentsCategoryStatus['status'];
        }

        $parentIDsStatus = [];

        foreach ($parentsCategories as $parentsCategory) {
            $parentIDsStatus[$parentsCategory['id']] = $parentsCategory['status'];
        }

        // return $parentIDsStatus;
        return [
            'nullParentID' => $parentIDs,
            'allParentIds' => $parentIDsStatus,
        ];
    }

    /**
     * buildTree of recursive array.
     *
     * @param (int) $ar                     all categories  
     * @param (int) $pid                    parent id     
     * @param (int) $expandSelectedCatgeory category id   
     *
     * @return array
     *------------------------------------------------------------------------ */
    private function buildTree($ar, $pid = null, $expandSelectedCatgeory = null)
    {
        $op = [];
        $count = 0;

        if (empty($ar)) {
            return false;
        }

        foreach ($ar as $item) {
            if ($item['parent_id'] == $pid) {
                $op[$count] = [
                  'title' => $item['name'],
                  'key' => $item['id'],
                  'parent_id' => $item['parent_id'],
                ];

                if ($this->listingFor == 'categories') {

                    // expand active category
                    if (!empty($this->allMyParents)
                            and
                        in_array($item['id'], $this->allMyParents)) {
                        $op[$count]['expanded'] = true;
                    }

                    // active category  
                    if ($item['id'] == $expandSelectedCatgeory) {
                        $op[$count]['active'] = true;
                    }
                } elseif ($this->listingFor == 'products') {

                    // product selected caegories expanded
                    if (!empty($this->allMyParents)
                            and
                        in_array($item['id'], $this->allMyParents)) {
                        $op[$count]['expanded'] = true;
                    }

                    // product related selected categories
                    if (!empty($this->productCategories)
                            and
                        in_array($item['id'], $this->productCategories)) {
                        $op[$count]['selected'] = true;
                    }

                    // for add product select category  
                    if ($item['id'] == $expandSelectedCatgeory) {
                        $op[$count]['selected'] = true;
                    }
                }

                 // using recursion
                $children = $this->buildTree(
                                                $ar,
                                                $item['id'],
                                                $expandSelectedCatgeory
                                            );

                // All subItems
                if (!empty($children)) {
                    $op[$count]['children'] = $children;
                    $op[$count]['folder'] = true;
                }
                ++$count;
            }
        }

        return $op;
    }

    /**
     * buildMenu for sidebar.
     *                 
     * @return array
     *------------------------------------------------------------------------ */
    public function buildMenuTree()
    {
        if (!empty($this->categories)) {
            foreach ($this->categories as $category) {
                if ($category['status'] == $this->configStatus['active']['id']) {
                    $this->sidebarCategories[] = $category;
                }
            }
        }

      // categories only active for sidebar
        if (!empty($this->sidebarCategories)) {
            $categoriesArray = $this->buildTree($this->sidebarCategories);
        }

        if (!empty($categoriesArray)) {
            $this->createMenuItems($categoriesArray);
            //$this->nevigationTree($categoriesArray);

            return $this->categoriesMarkup;
        }
    }

    /**
     * createMenuItems for sidebar.
     *
     * @param  @param $categoriesArray all categories array  
     *                                  
     * @return array
     *------------------------------------------------------------------------ */
    private function createMenuItems($categoriesArray)
    {
        if (!empty($categoriesArray)) {
            foreach ($categoriesArray as $category) {
                $categoryTitle = $category['title'];

                if (!empty($category['children'])) {

                // this section contain childrens 
                $this->categoriesMarkup .= '<li><a href="'.route('master').'/#/products/'.$category['key'].'/'.Str::slug($categoryTitle).'">'.$categoryTitle.'</a><ul>';

                    $this->createMenuItems($category['children']);

                    $this->categoriesMarkup .= '</ul></li>';
                } else {

                // this section for parent categories null values
                $this->categoriesMarkup .= '<li><a href="'.route('master').'/#/products/'.$category['key'].'/'.Str::slug($categoryTitle).'">'.$categoryTitle.'</a></li>';
                }
            }
        }
    }

    /**
     * createMenuItems for nevigation.
     *
     * @param  @param $categoriesArray all categories array  
     *                                  
     * @return array
     *------------------------------------------------------------------------ */
    private function nevigationTree($categoriesArray)
    {
        if (!empty($categoriesArray)) {
            foreach ($categoriesArray as $category) {
                $categoryTitle = $category['title'];

                if (!empty($category['children'])) {

                // this section contain childrens 
                $this->categoriesNevMarkup .= '<li><a href="'.route('master').'/#/products/'.$category['key'].'/'.Str::slug($categoryTitle).'">'.$categoryTitle.'</a><ul>';

                    $this->nevigationTree($category['children']);

                    $this->categoriesNevMarkup .= '</ul></li>';
                } else {

                // this section for parent categories null values
                $this->categoriesNevMarkup .= '<li><a href="'.route('master').'/#/products/'.$category['key'].'/'.Str::slug($categoryTitle).'">'.$categoryTitle.'</a></li>';
                }
            }
        }
    }
}

// End of file Categories.php 
// Location: ./app/libs/Categories.php 
