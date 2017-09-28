<?php
/*
* ProductRepository.php - Repository file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\Product\Models\ProductCategory;
use App\Yantrana\Components\Product\Models\Product as ProductModel;
use App\Yantrana\Components\Product\Models\ProductOptionLabel as ProductOptionLabel;
use App\Yantrana\Components\Product\Blueprints\ProductRepositoryBlueprint;
use App\Yantrana\Components\Category\Repositories\ManageCategoryRepository;
use Route;
use Config;

class ProductRepository extends BaseRepository
                          implements ProductRepositoryBlueprint
{
    /**
     * @var ProductModel - Product Model
     */
    protected $product;

    /**
     * @var ProductModel - Product Model
     */
    protected $paginationArray;

    /**
     * @var $category - Category Model
     */
    protected $category;

    /**
     * @var
     */
    protected $allMyChilds;

    /**
     * @var ProductOptionLabel
     */
    protected $productOptions;

    /**
     * @var ProductCategory - ProductCategory Model
     */
    protected $productCategory;

    /**
     * @var categoryRepository - categoryRepository Repository
     */
    protected $categoryRepository;

    /**
     * Constructor.
     *
     * @param ProductModel $product - Product Model
     *-----------------------------------------------------------------------*/
    public function __construct(ProductModel $product, Category $category,
    ProductCategory $productCategory, ProductOptionLabel $productOptions,
    ManageCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productCategory = $productCategory;
        $this->allMyChilds = [];
        $this->product = $product;
        $this->productOptions = $productOptions;
        $this->paginationArray = Config::get('__tech.pagination_rows');
    }

    /**
     * fetch all active products 
     * 1 status is active.
     *
     * @param int $categoryIDs
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategorieProducts($categoryIDs, $input = [], $inactiveBrandIds = [])
    {
        return $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds)
                    ->with('checkOptionExists')
                    ->brandAndPrice($input) // filter product when the max & min price available or brand
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description', 'out_of_stock', 'featured',
                            ],
                        ])
                    )
                    ->whereInHasCategories($categoryIDs)
                    ->paginate($this->getPaginationCount());
    }

    /**
     * fetch all active products without pagination.
     * 
     * @param int $categoryIDs
     * @param int $input
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategorieWithoutPaginate($categoryIDs, $route, $inactiveBrandIds)
    {
        $query = $this->product
                    ->verifyByBrand($inactiveBrandIds)
                    ->activeProductAndCheckOutOfStock()
                    ->whereInHasCategories($categoryIDs);

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->whereNotNull('products.brands__id')
                         ->groupBy('products.brands__id')
                         ->pluck('products.brands__id')
                         ->toArray();
    }

    /**
     * get all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategories()
    {
        return $this->categoryRepository->fetchAll();
    }

    /**
     * get all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategories($categoriesID = [])
    {
        return  $this->productCategory
                    ->whereIn('categories_id', $categoriesID)
                    ->get(['categories_id', 'products_id']);
    }

    /**
     * fetch all active product count.
     *
     * @param int $categoryID
     *
     * @return int
     *---------------------------------------------------------------- */
    public function fetchProductsCount($categoryID)
    {
        $product = $this->product->isStatus(1); // active

        if (!empty($categoryID)) {
            $product->whereInHasCategories($categoryID);
        }

        return $product->get();
    }

    /**
     * get active featured products.
     * 
     * @param int $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProduct($productID)
    {
        return $this->product->where([
                                'id' => $productID,
                                'status' => 1, // active
                            ])->first();
    }

    /**
     * get active featured products.
     * 
     * @param int $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($productID)
    {
        return $this->product
                    ->ofId($productID)->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'product_id',
                               'description', 'status', 'out_of_stock',
                               'old_price', 'price', 'youtube_video',
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch products categories.
     * 
     * @param $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategory($productID)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->get(['products_id', 'categories_id']);
    }

    /**
     * fetch product details for quick view details
     * option values.
     *
     * @param int $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchQuickViewDetails($productID)
    {
        return  $this->product
                    ->where([
                        'id' => $productID,
                        'status' => 1, // active
                    ])
                    ->with(['option' => function ($query) {
                        $query->with('optionValues');
                    }])->with(['categories' => function ($query) {
                        $query->select('categories.id', 'categories.name');
                    }])
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'product_id',
                               'status',
                               'out_of_stock',
                               'old_price',
                               'price',
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch product details with option for noraml page
     * option values 
     * product images
     * related product.
     *
     * @param $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetails($productID)
    {
        $query = $this->product
                        ->with('relatedProducts', 'image', 'categories', 'specification', 'brand')
                        ->with(['option' => function ($query) {
                                $query->with('optionValues');
                        }]);

        if (isAdmin()) {
            $query->ofId($productID);
        } else {
            $query->where([
                                    'products.id' => $productID,
                                    'products.status' => 1,
                                ]);
        }

        return $query->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'product_id',
                               'description',
                               'status',
                               'out_of_stock',
                               'old_price',
                               'price',
                               'youtube_video',
                               'brands__id',
                            ],
                        ])
                    )->first();
    }

    /**
     * fetch related product.
     *
     * @param array $relatedProductIDs
     * @param array $activeCatIds
     * @param array $inactiveBrandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRelatedProducts($relatedProductIDs, $activeCatIds, $inactiveBrandIds)
    {
        return $this->product->whereIn('id', $relatedProductIDs)
                            ->isStatus(1) // active)
                            ->verifyByBrand($inactiveBrandIds)
                            ->whereInHasCategories($activeCatIds)
                            ->select(
                                'id',
                                'name',
                                'thumbnail',
                                'status',
                                'out_of_stock',
                                'price'
                            )->get();
    }

    /**
     * fetch recent view products.
     *
     * @param array(int$recentViewProductIDs
     * @param int $productIDs
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRecentViewedProducts($recentViewProductIDs, $productID)
    {
        return $this->product->whereIn('id', $recentViewProductIDs)
                             ->isStatus(1) // active)	
                             ->where('id', '!=', $productID)
                             ->select(
                                'id',
                                'name',
                                'thumbnail',
                                'status',
                                'out_of_stock',
                                'price'
                            )->get();
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
        foreach ($this->fetchCategories() as $key => $category) {
            if (($category['id'] == $categoryID)
                          and
                ($category['status'] == 1)
                    and
                in_array($categoryID,  $this->allMyChilds) !== true) {
                $this->allMyChilds[] = $categoryID;
            }

            if (($category['parent_id'] == $categoryID)
                          and
            ($category['status'] == 1)) {
                if (!in_array($category['id'], $this->allMyChilds)) {
                    $this->allMyChilds[] = $category['id'];
                }

                $this->getActiveChilds($category['id']);
            }
        }

        return $this->allMyChilds;
    }

    /**
     * getActiveChilds of parent category.
     *
     * @param  (int) $categoryID.
     *                             
     * @return array
     *------------------------------------------------------------------------ */
    public function allChildCategories($categoryID, $allChild = [])
    {
        $allCategories = $this->fetchCategories()->toArray();

        foreach ($allCategories as $category) {
            if ($category['parent_id']  ==  $categoryID) {
                $allChild[] = $category['id'];

                $allChild = self::allChildCategories(
                                                        $category['id'],
                                                        $allChild
                                                    );
            }
        }

        return $allChild;
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array  $activeCatIds
     * @param array  $input
     * @param string $route
     * @param array  $inactiveBrandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAll($activeCatIds, $input = null, $route, $inactiveBrandIds)
    {
        $query = $this->product
                      ->sortOrder()
                      ->activeProductAndCheckOutOfStock()
                      ->verifyByBrand($inactiveBrandIds)
                      ->with('checkOptionExists');

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->brandAndPrice($input) // filter product when the max & min price available or brand
                        ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'status',
                               'price',
                               'description',
                               'out_of_stock',
                               'brands__id',
                               'featured',
                            ],
                        ]))
                        ->whereInHasCategories($activeCatIds)
                        ->paginate($this->getPaginationCount());
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array  $activeCatIds
     * @param array  $input
     * @param string $route
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllWithoutPaginate($activeCatIds, $input = null, $route, $inactiveBrandIds)
    {
        $query = $this->product
                    ->activeProductAndCheckOutOfStock()
                    ->verifyByBrand($inactiveBrandIds);

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        return $query->whereInHasCategories($activeCatIds)
                        ->whereNotNull('products.brands__id')
                        ->groupBy('products.brands__id')
                        ->pluck('products.brands__id')
                        ->toArray();
    }

    /**
     * Fetch all product records via paginated data.
     *
     * @param array $activeCatIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllForFilter($activeCatIds, $input = null)
    {
        $query = $this->product->sortOrder()->activeProductAndCheckOutOfStock();

        if (Route::currentRouteName() === 'products.featured') {
            $query->where('products.featured', 1);
        }

        if (!empty($input['sbid'])) {
            $query->whereIn('brands__id', $input['sbid']);
        }

        return $query->select('id', 'name', 'thumbnail', 'price', 'out_of_stock')
                     ->whereInHasCategories($activeCatIds)
                     ->get();
    }

    /**
     * Fetch category records.
     *
     * @param number $categoryID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategoryByID($categoryID)
    {
        return $this->categoryRepository->fetchByIdAndIsActive($categoryID);
    }

    /**
     * check search product is valid.
     *
     * @param array $searchTerm
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchProducts($searchTerm)
    {
        return $this->product
                    ->activeProductAndCheckOutOfStock()
                    ->search($searchTerm)
                    ->pluck('id');
    }

    /**
     * Fetch Searched active products records.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchData($pIds = [], $input = [], $inactiveBrandIds)
    {
        return $this->product
                    ->with('checkOptionExists')
                    ->verifyByBrand($inactiveBrandIds)
                    ->whereIn('id', $pIds)
                    ->sortOrder()
                    ->brandAndPrice($input) // get brand , min_price, max_price
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id',
                               'name',
                               'thumbnail',
                               'status',
                               'price',
                               'description',
                               'out_of_stock',
                               'brands__id',
                               'featured',
                            ],
                        ])
                    )
                    ->paginate($this->getPaginationCount());
    }

    /**
     * Fetch Searched active products records & filter.
     *
     * @param array $input
     * @param array $pIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSearchedProductDataForFilter($pIds = [], $input = [], $inactiveBrandIds)
    {
        return $this->product
                    ->whereIn('id', $pIds)
                    ->verifyByBrand($inactiveBrandIds)
                    ->brandAndPrice($input) // get brand , min_price, max_price
                    ->whereNotNull('products.brands__id')
                    ->groupBy('products.brands__id')
                    ->pluck('products.brands__id')->toArray();
    }

    /**
     * Fetch products categories.
     *
     * @param array $productIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductsCategories($productIDs)
    {
        return $this->productCategory
                    ->whereIn('products_id', $productIDs)
                    ->select('products_id', 'categories_id')
                    ->get();
    }

    /**
     * if check product option exist.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function productOptionsIsExist($productID = null)
    {
        $options = $this->productOptions
                        ->productID($productID)
                        ->get()
                        ->toArray();

        if (empty($options)) {
            return false;
        }

        return true;
    }

    /**
     * get brand related products.
     *
     * @param number $brandID
     * @param array  $isActiveCate
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandRelatedProduct($productID = [], $input = [])
    {
        return  $this->product
                    ->sortOrder()
                    ->activeProductAndCheckOutOfStock()
                    ->with('checkOptionExists')
                    ->whereIn('id', $productID)
                    ->brandAndPrice($input)
                    ->select(
                        __nestedKeyValues([
                            'products' => [
                               'id', 'name', 'thumbnail', 'status', 'price',
                               'description', 'out_of_stock', 'featured',
                            ],
                        ])
                    )
                    ->paginate($this->getPaginationCount());
    }

    /**
     * fetch brand product by id.
     *
     * @param number $brandID
     * @param array  $isActiveCate
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductByBrandId($brandID)
    {
        return  $this->product
                     ->whereBrands__id($brandID)
                     ->activeProductAndCheckOutOfStock()
                     ->pluck('id')->all();
    }

    /**
     * fetch product max and min price.
     *
     * @param array  $categoryIDs
     * @param string $route
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMaxAndMinPrice($categoryIDs, $route, $inactiveBrandIds = [])
    {
        $query = $this->product
                       ->verifyByBrand($inactiveBrandIds)
                       ->activeProductAndCheckOutOfStock();

        if ($route === 'products.featured') {
            $query->where('products.featured', 1);
        }

        if (!__isEmpty($categoryIDs)) {
            $query->whereHas('categories', function ($q) use ($categoryIDs) {
                                $q->whereIn('categories_id', $categoryIDs);
                            });
        }

        return $query
                  ->selectMinAndMaxPrice()
                  ->first();
    }

    /**
     * fetch product min and max price of product based on product ids.
     *
     * @param array $input
     * @param array $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchMaxAndMinPriceOfProduct($productIds, $inactiveBrandIds = [])
    {
        return $this->product
                    ->whereIn('id', $productIds)
                    ->whereNotIn('products.brands__id', $inactiveBrandIds)
                    ->selectMinAndMaxPrice()
                    ->first();
    }

    /**
     * fetch brand for products.
     *
     * @param array $isActiveCate
     * @param int   $brandID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchFilterBrandRelatedProduct($productIds = [])
    {
        return  $this->product
                    ->whereIn('id', $productIds)
                    ->activeProductAndCheckOutOfStock()
                    ->selectBrandCount()
                    ->groupBy('products.brands__id')
                    ->get();
    }

    /**
     * fetch brand id of this product.
     *
     * @param int $productIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandIdByPid($productIds)
    {
        return  $this->product
                     ->where('id', $productIds)
                     ->whereNotNull('brands__id')
                     ->select('brands__id')
                     ->first();
    }
}
