<?php

/*
* MenuEngine.php - Main component file
*
* This file is part of the Home component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Home;

use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Pages\Repositories\ManagePagesRepository;

class MenuEngine
{
    /**
     * category placement $categoryPlacement.
     *---------------------------------------------------------------- */
    protected $categoryPlacement;

    /**s
      * brand placement $brandsPlacement
      *
      * @return void
      *---------------------------------------------------------------- */
    protected $brandsPlacement;

    /**
     * @var - Category Repository
     */
    protected $categoryRepository;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var ManagePagesRepository - ManagePages Repository
     */
    protected $pagesRepository;

    /**
     * Constructor.
     *
     * @param ManageCategoryRepository $categoryRepository - Category Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
        ManageCategoryRepository $categoryRepository,
        BrandRepository $brandRepository,
        ManagePagesRepository $pagesRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * get navigation bar menu tree with html formated.
     *---------------------------------------------------------------- */
    public function nevigationTree()
    {
        // set setting when show the categories in menu
        $categoryPlacement = getStoreSettings('categories_menu_placement');
        $this->categoryPlacement = $categoryPlacement = ($categoryPlacement == 2 or $categoryPlacement == 3) ? $categoryPlacement : false;

        // set setting when show the brands in menu
        $brandsPlacement = getStoreSettings('brand_menu_placement');
        $this->brandsPlacement = $brandsPlacement = ($brandsPlacement == 2 or $brandsPlacement == 3) ? $brandsPlacement : false;

        // page object
        $object = $this->getPages();

        // if conditionally the categories show in menu is true the categories object Marge in continue pass object
        // if not the object pass to next process
        $object = __ifIsset($categoryPlacement,
                    collect($object->toArray())->merge($this->getCategories()),
                    collect($object->toArray())
                );

        // if conditionally the brand show in menu is true the brand object marge in continue pass object
        // if not the object pass to next process
        $object = __ifIsset($brandsPlacement,
                    collect($object->toArray())->merge($this->getBrands()),
                    collect($object->toArray())
                );

        return $this->buildTree($object, null);
    }

    /**
     * get side bar categories menu.
     *---------------------------------------------------------------- */
    public function getSideBarCategoriesMenu()
    {
        return $this->generateTree($this->categoryRepository->fetchAllActive());
    }

    /**
     * get sidebar bar brands menu.
     *---------------------------------------------------------------- */
    public function getSideBarBrandsData()
    {
        $brands = [];

        $brandCollection = $this->brandRepository->fetchAllActive();

        if (!__isEmpty($brandCollection)) {
            foreach ($brandCollection as $key => $brand) {
                $brandCollection[$key]['slugName'] = str_slug($brand->name);
            }
        }

        return $brandCollection;
    }

    /**
     * get all active  and allow to add to menu pages.
     *
     * @return collection object
     *---------------------------------------------------------------- */
    protected function getPages()
    {
        return $this->pagesRepository->fetchAllActiveAndAddToMenu();
    }

    /**
     * get active brands list.
     *
     * @return collection object
     *---------------------------------------------------------------- */
    protected function getBrands()
    {
        $brandArray = [];

        // fetch all active brand lists
        $brandCollection = $this->brandRepository->fetchAllActive();

        $prefix = 'b_';

        foreach ($brandCollection as $key => $brand) {
            $tempBrandArray = [
                  'title' => $brand->name,
                  'id' => $prefix.$brand->_id,
                  'type' => 4, // system defined type
                  'parent_id' => 3, // system brand id 
                  'link' => route('product.related.by.brand', [
                                                'brandID' => $brand->_id,
                                                'brandName?' => str_slug($brand->name),
                                            ]),
            ];

            array_push($brandArray, $tempBrandArray);
        }

        return $brandArray;
    }

    /**
     * get active categories list.
     *---------------------------------------------------------------- */
    protected function getCategories()
    {
        $categoryArray = [];

        // fetch all active categories list
        $categoryCollection = $this->categoryRepository->fetchAllActive();

        $prefix = 'c_';

        foreach ($categoryCollection as $key => $category) {
            $tempCategory = [
                  'title' => ucwords($category->name),
                  'id' => $prefix.$category->id,
                  'type' => 4, // system defined type
                  'parent_id' => $prefix.$category->parent_id, // system defined category id
                  'link' => route('products_by_category', [
                                        'categoryID' => $category->id,
                                        'categoryName?' => str_slug($category->name),
                                    ]),
            ];

            if (!$category->parent_id) {
                $tempCategory['parent_id'] = 2;
            }

            array_push($categoryArray, $tempCategory);
        }

        return $categoryArray;
    }

    /**
     * generate generate Multidimensional Array.
     *
     * @param array $getArray
     * @param parentID 
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function buildTree($getArray, $parentID = null)
    {
        $data = [];
        $count = 0;
        $getArray = $getArray->sortBy('list_order');

        foreach ($getArray as $item) {
            $item = (object) $item;

            if ($item->parent_id == $parentID) {
                $data[$count] = [
                      'name' => $item->title,
                      'id' => $item->id,
                      'type' => $item->type,
                      'parent_id' => $item->parent_id,
                ];

                if ($item->type === 2 and !empty($item->link_details)) {
                    $linkDetils = json_decode($item->link_details, true);
                    $data[$count]['link'] = $linkDetils['value'];
                    $data[$count]['target'] = $linkDetils['type'];
                } elseif ($item->type === 3 and !empty($item->link_details)) {
                    $data[$count]['link'] = route($item->link_details);
                    $data[$count]['target'] = '_self';
                } else {
                    if (isset($item->link)) {
                        $data[$count]['link'] = $item->link;
                        $data[$count]['target'] = '_self';
                    } else {
                        $data[$count]['link'] = pageDetailsRoute($item->id, $item->title);
                        $data[$count]['target'] = '_self';
                    }
                }

                // do not add brands or categories if not required
                if (($this->categoryPlacement === false and $item->id === 2)
                        or ($this->brandsPlacement === false and $item->id === 3)) {
                    continue;
                }

                $children = $this->buildTree($getArray, $item->id);

                // sort categories
                if ($data[$count]['id'] === 2) {
                    $children = collect($children)->sortBy('name');
                }

                // sort brand
                if ($data[$count]['id'] === 3) {
                    $children = collect($children)->sortBy('name');
                }

                if (!empty($children)) {
                    $data[$count]['children'] = $children;
                }
            }

            ++$count;
        }

        return $data;
    }

    /**
     * generate tree for side bar.
     *
     * @param array $getArray
     * @param int   $parentID
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function generateTree($getArray, $parentID = null)
    {
        $data = [];
        $count = 0;
        $getArray = $getArray->sortBy('name');

        foreach ($getArray as $item) {
            $item = (object) $item;

            if ($item->parent_id == $parentID) {
                $data[$count]['id'] = $item->id;

                if (!empty($item->title)) {
                    $data[$count]['name'] = $item->title;
                } else {
                    $data[$count]['name'] = $item->name;
                }

                $data[$count]['parent_id'] = $item->parent_id;

                if ($item->type === 2 and (!empty($item->type))) {
                    $linkDetils = json_decode($item->link_details, true);
                    $data[$count]['link'] = $linkDetils['value'];
                    $data[$count]['target'] = $linkDetils['type'];
                } else {
                    if (isset($item->link)) {
                        $data[$count]['link'] = $item->link;
                        $data[$count]['target'] = '_self';
                    } else {
                        $data[$count]['link'] = pageDetailsRoute($item->id, $item->title);
                        $data[$count]['target'] = '_self';
                    }
                }

                $children = $this->generateTree($getArray, $item->id);

                if (!empty($children)) {
                    $data[$count]['children'] = $children;
                }
            }

            ++$count;
        }

        return $data;
    }
}
