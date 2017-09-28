<?php
/*
* CategoryRepository.php - Repository file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\Product\Models\Product;
use App\Yantrana\Components\Product\Models\ProductCategory;
use Config;
use File;
use Auth;
use Hash;

class ManageCategoryRepository extends BaseRepository
{
    /**
     * @var CategoryModel - Category Model
     */
    protected $category;
    protected $allMyChildsIDs;
    protected $allMyChilds;
    protected $product;
    protected $productCategory;

    /**
     * Constructor.
     *
     * @param Category $category - Category Model
     *-----------------------------------------------------------------------*/
    public function __construct(Category $category, Product $product,
     ProductCategory $productCategory)
    {
        $this->category = $category;
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->allMyChildsIDs = [];
        $this->allMyChilds = [];
    }

    /**
     * fetch category.
     *
     * @param $categoryID 
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch($categoryID)
    {
        return $this->category
                    ->whereCatId($categoryID)
                    ->selectField()
                    ->first();
    }

    /**
     * fetch all active categories.
     *
     * @return Eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllActive()
    {
        return $this->viaCache('cache.categories.all.active', function () {
            return $this->category
                        ->whereStatus(1)
                        ->select('id', 'name', 'parent_id')
                        ->get();
        });
    }

    /**
     * fetch  all categories.
     *
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchCategories($categoryID, $searchText = null)
    {
        $dataTablesConfig = [
            'fieldAlias' => [
                'name' => 'name',
            ],
            'searchable' => [
                'name' => 'name',
                'parent_id' => 'parent_id',
            ],
        ];

        $search = $searchText['search'];

        // check is it null
        if ($categoryID and $categoryID === 'null') {
            $categoryID = null;
        }

        if (__isEmpty($search['value'])) {
            $query = $this->category->parent($categoryID);
        } else {
            $query = $this->category;
        }

        return $query->selectField()
                     ->dataTables($dataTablesConfig)
                     ->toArray();
    }

    /**
     * update category status using category id.
     *
     * @param array $category
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateStatus($category, $input)
    {
        extract($input);

        return $category->modelUpdate(['status' => $status]);
    }

    /**
     * fetch all categories list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAll()
    {
        return $this->viaCache('cache.categories.all', function () {
            return $this->category->selectField()->get();
        });
    }

    /**
     * fetch all categories list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchWithoutCacheAll()
    {
        return $this->category->selectField()->get();
    }

    /**
     * Store new category.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function store($inputData)
    {
        $category = new $this->category();
        $category->name = $inputData['name'];
        $category->status = ($inputData['status']) ? 1 // active 
                                 : 0; // deactive 
        $category->parent_id = $inputData['parent_cat'];

        //Check if category added
        if ($category->save()) {
            activityLog('ID of '.$category->id.' category added.');

            return true;
        }

        return false;
    }

    /**
     * get category records.
     *
     * @param int    $parentID
     * @param string $catName
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchCatByName($parentID, $catName)
    {
        return $this->category->parent($parentID)
                              ->name($catName)
                              ->get()
                              ->toArray();
    }

    /**
     * get category records.
     *
     * @param int $parentID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function findByID($parentID)
    {
        return $this->category->find($parentID);
    }

    /**
     * check in datatable category name is uniques.
     *
     * @param array $input
     * @param int   $catID
     *---------------------------------------------------------------- */
    public function checkUniqueRecord($input, $catID)
    {
        $query = $this->category;

        if (!empty($input['parent_cat'])) {
            $query->parent($input['parent_cat']);
        } else {
            $query->parent(null);
        }

        return  $query->name($input['name'])
                        ->where('id', '!=', $catID)
                        ->get();
    }

    /**
     * update category record.
     *
     * @param array $input
     * @param array $category
     *---------------------------------------------------------------- */
    public function update($input, $category)
    {
        extract($input);

        // set null 
        if (empty($parent_cat) or $parent_cat == 0) {
            $parent_cat = null;
        }

        $updateData = [
            'name' => $name,
            'status' => $status,
            'parent_id' => $parent_cat,
        ];

        if ($category->modelUpdate($updateData)) {
            activityLog('ID of '.$category->id.' category update.');

            return $category;
        }

        return false;
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
        foreach ($this->category->all() as $key => $category) {
            if ($category['parent_id'] == $categoryID) {
                $this->allMyChildsIDs[] = $category['id'];

                $this->getAllChilds($category['id']);
            }
        }

        $this->allMyChildsIDs[] = $categoryID;

        // get only Categories id
        return [
            'allMyChildsIDs' => $this->allMyChildsIDs,
        ];
    }

    /**
     * delete category.
     *
     * @param array $category
     *
     * @return reaction code
     *---------------------------------------------------------------- */
    public function delete($category, $input)
    {
        $password = Auth::user()->password;

        if (Hash::check($input['password'], $password) and isAdmin()) {

            // Take category related product & his childrens for delete it.!	
            if ($this->categoryDelete($category->id)) {
                return 1;
            }
        }

        return 14;
    }

    /**
     * This method delete category and its sub-categories with
     * products.
     *
     *
     * @var string
     *---------------------------------------------------------------- */
    private function categoryDelete($currentCategoryID)
    {
        $catIDs = [];
        $diffCats = [];
        $sameCats = [];
        $catArray = [];
        $allCatProducts = [];
        $deleteProducts = [];
        $deleteProductsRelationship = [];

        // Take all child of current category.
        $catIDs = $this->getAllChilds($currentCategoryID);

        $catArray = $catIDs['allMyChildsIDs'];

        //Take all product releted to category & his child using pivot relationship.
        $allCatProducts = $this->product->whereHas('categories', function ($query) use ($catArray) {

            $query->whereIn('categories_id', $catArray);

        })->with('categories')->get();

        if (!empty($allCatProducts)) {
            foreach ($allCatProducts as $catProduct) {
                $productExistInCat = [];
                //using pivot relationship three tables values.
                foreach ($catProduct->categories as $category) {
                    $productExistInCat[] = $category->pivot->categories_id;
                }
                //Take count of existing product.
               $productExistInCatCount = count($productExistInCat);

                if ($productExistInCatCount > 1) {
                    $diffCats = array_diff($productExistInCat, $catArray);

                    if (!empty($diffCats)) {
                        //Same categories in one product array
                        $sameCats = array_intersect($productExistInCat, $catArray);

                        if (!empty($sameCats)) {
                            foreach ($sameCats as $value) {
                                $deleteProductsRelationship['cat'][] = $value;
                                $deleteProductsRelationship['product'][] = $catProduct->id;
                            }
                        }
                    } else {
                        //unique product delete.
                        $deleteProducts[] = $catProduct->id;
                    }
                } else {
                    //All delete parent of childrens.
                $deleteProducts[] = $catProduct->id;
                }
            }

            if (!empty($deleteProducts)) {
                $success = $this->product
                                  ->whereIn('id', $deleteProducts)
                                ->delete();

                if ($success) {
                    $deletedPIDs = implode($deleteProducts, '|');
                    $deletedCatIDs = implode(array_unique($catIDs['allMyChildsIDs']), '|');

                    activityLog('ID of '.$deletedPIDs.' products deleted of this category'.$deletedCatIDs);

                    foreach ($deleteProducts as $deletedProductID) {
                        //Take path of product image folder
                          $productAssestsPath = Config::get('__tech.product_assets').$deletedProductID;

                        if (File::isDirectory($productAssestsPath)) {
                            //Delete firectory
                            File::deleteDirectory($productAssestsPath);
                        }
                    }
                } else {
                    return 14;
                }
            }

            if (!empty($deleteProductsRelationship)) {
                $relatedCats = $deleteProductsRelationship['product'];
              //delete parent category & childrens & related products in ProductCategory tables
              $deletedSuccess = $this->productCategory->whereIn('products_id', $relatedCats)
                                    ->whereIn('categories_id', $deleteProductsRelationship['cat'])
                                    ->delete();

                if (!$deletedSuccess) {
                    return 14;
                }

                $deletedCat = implode($deleteProductsRelationship['cat'], '|');
                $deletedProduct = implode($deleteProductsRelationship['product'], '|');

                activityLog('ID of '.$deletedCat.' category of products.'.$deletedProduct.'deleted');
            }
        }
        //delete category
        if (is_array($catArray)) {
            if ($this->category->whereIn('id', $catArray)->deleteIt()) {
                $deletedCategoriesIDs = implode(array_unique($catIDs['allMyChildsIDs']), '|');

                activityLog('ID of '.$deletedCategoriesIDs.' category deleted.');

                return 1;
            }
        }

        return 14;
    }

    /** get categories products 
     * @param int $categoryID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategoriesProducts($categoryID)
    {
        // fetch categories products count
        return  $this->productCategory
                     ->whereIn('categories_id', $categoryID)
                     ->pluck('products_id')->all();
    }

    /** fetch current categories products 
     * @param int $categoryID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCurrentCategoryProducts($categoryID)
    {
        // fetch categories products count
        return  $this->productCategory
                     ->where('categories_id', $categoryID)
                     ->pluck('products_id')->all();
    }

    /**
     * fetch category record but it is active.
     *
     * @param int $categoryID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByIdAndIsActive($categoryID)
    {
        return $this->category
                        ->where([
                            'id' => $categoryID,
                            'status' => 1,
                        ])->first();
    }
}
