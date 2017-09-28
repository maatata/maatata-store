<?php
/*
* ProductEngine.php - Main component file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product;

use App\Yantrana\Components\Product\Repositories\ProductRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Category\Models\Category;
use App\Yantrana\Components\Product\Blueprints\ProductEngineBlueprint;
use Breadcrumb;
use Route;
use Input;
use ShoppingCart;
use Request;

class ProductEngine implements ProductEngineBlueprint
{
    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * @var allMyChilds - allMyChilds array
     */
    protected $allMyChilds;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * Constructor.
     *
     * @param ProductRepository $productRepository - Product Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ProductRepository $productRepository,
                        BrandRepository $brandRepository)
    {
        $this->productRepository = $productRepository;
        $this->brandRepository = $brandRepository;
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getProductRelatedActiveCategories($categoriesItems = [])
    {
        $categories = [];

        if (!empty($categoriesItems)) {
            // categories related portion
            foreach ($categoriesItems as $key => $category) {
                $categories[] = [
                    'name' => $category->name,
                    'categoryUrl' => categoriesProductRoute(
                                        $category->id,
                                        str_slug($category->name)
                                    ),
                ];
            }
        }

        return $categories;
    }

    /**
     * return the price fultering.
     *
     * @param int $categoryID
     * @param array  input
     
     * @return array
     *---------------------------------------------------------------- */
    public function priceFilter($productPrices, $input)
    {
        $filterPrices = [];

        $showFilterPrice = [];

        if (isset($input['min_price']) and isset($input['max_price'])) {
            $showFilterPrice = priceFormat(round($input['min_price'])).
                                __(' to ')
                                .priceFormat(round($input['max_price']));
        }

        if (!empty($productPrices)) {
            $filterPrices['max_price'] = round((isset($input['max_price']))
                                            ? $input['max_price']
                                            : $productPrices->max_price);

            $filterPrices['min_price'] = round((isset($input['min_price']))
                                            ? $input['min_price']
                                            : $productPrices->min_price);
        }

        return [
            'filtered_price' => $filterPrices,
            'show_filtered_price' => $showFilterPrice,
        ];
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getRelatedProduct($relatedProductItems = [])
    {
        $relatedProductsIDs = [];
        $relatedProductData = [];

        // related product section
        if (!__isEmpty($relatedProductItems)) {
            foreach ($relatedProductItems as $relatedProduct) {
                $relatedProductsIDs[] = $relatedProduct->related_product_id;
            }

            if (!__isEmpty($relatedProductsIDs)) {
                $charactorLimit = config('__tech.character_limit');

                $categoryCollection = $this->productRepository->fetchCategories();
                $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

                // find all active categories
                $activeCatIds = findActiveChilds($categoryCollection);

                // fetch all related products
                $relatedProducts = $this->productRepository
                                         ->fetchRelatedProducts($relatedProductsIDs, $activeCatIds, $inactiveBrandIds);

                if (!__isEmpty($relatedProducts)) {
                    foreach ($relatedProducts as $key => $relatedProduct) {
                        $relatedProductData[] = [
                            'id' => $relatedProduct->id,
                            'price' => priceFormat($relatedProduct->price),
                            'thumbnail' => $relatedProduct->thumbnail,
                            'out_of_stock' => $relatedProduct->out_of_stock,
                            'slugName' => str_slug($relatedProduct->name),
                            'related_product_price' => priceFormat($relatedProduct->price),
                            'name' => str_limit($relatedProduct->name,
                                                $limit = $charactorLimit,
                                                $end = '...'),
                        ];
                    }
                }
            }
        }

        return $relatedProductData;
    }

    /**
     * get product details.
     *
     * @param int $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getProductForDetails($product)
    {
        $getSelectedOptions = [];
        $addonPrice = [];
        $getPrice = [];

        // fetch product option
        if (!__isEmpty($product->option)) {
            foreach ($product->option as $optionKey => $option) {
                $optionValue = $option->optionValues->toArray();

                $productNameArray = array(
                    '__option_name__' => $option->name,
                );
                $nameMarkUp = __('Select __option_name__');
                $option['optionName'] = strtr($nameMarkUp, $productNameArray);

                if (!empty($optionValue)) {
                    // fetch option value and price and calculate total
                    $product->option[$optionKey]['optionValueExist'] = true;

                    foreach ($option->optionValues as  $vlaueKey => $optionValue) {
                        $optionValue['addon_price_format'] = priceFormat($optionValue->addon_price);
                        $optionValue['addon_price'] = $optionValue->addon_price;
                        $optionValue['subtotal'] = $optionValue->addon_price + $product->price;
                        $optionValue['optionName'] = $option->name;
                    }

                    $getSelectedOptions[] = $option->optionValues[0];
                    $addonPrice[] = $option->optionValues[0]->addon_price;
                } else {
                    $product->option[$optionKey]['optionValueExist'] = false;
                }

                $getPrice['total'] = priceFormat($product->price + array_sum($addonPrice));
                $getPrice['base_price'] = priceFormat($product->price);
            }
        }

        $searchedCartItemRowID = ShoppingCart::search($product->id, $getSelectedOptions);

        if (!empty($searchedCartItemRowID)) {
            $product->cartProduct = ShoppingCart::findRow($searchedCartItemRowID);
        }

        if (!empty($getSelectedOptions)) {
            $product->getSelectedOptions = $getSelectedOptions;
        }

        $product->getPrice = $getPrice;
        $product->newTotalPriceCount = 0;
        $product->newTotalPrice = priceFormat($product->price);

        if (!empty($addonPrice)) {
            $totalAddonPrice = array_sum($addonPrice);
            $product->newTotalPrice = priceFormat($product->price + $totalAddonPrice);
            $product->newTotalPriceCount = 1;
        }

        // get active categories for product related
        $product['productCategories'] = $this->getProductRelatedActiveCategories($product->categories);

        return $product;
    }

    /**
     * Check if the this product is valid.
     *
     * @param int $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    protected function checkIsValidCategory($productID)
    {
        $productsCategories = $this->productRepository
                                   ->fetchProductCategory($productID);

        $findActiveParents = [];

        // all categories
        $categories = $this->productRepository->fetchCategories();

        if (!empty($productsCategories)) {
            foreach ($productsCategories as $productCategory) {
                $categoriesIDs = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesIDs);
            }
        }

        // get active categories  & make in sigle level
        $makeArrayInSingleLevel = array_flatten($findActiveParents);

        // get active categories & get only unique
        return array_unique($makeArrayInSingleLevel);
    }

    /**
     * Get data for quick view dialog.
     *
     * @param int $productID
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getQuickViewDetailsData($productID, $categoryID)
    {
        $product = $this->productRepository->fetchQuickViewDetails($productID);

        // Check if product exist or not 
        if (__isEmpty($product)) {
            return __engineReaction(2);
        }

        $productId = $product->id;
        $productName = $product->name;

        // Check this product of category is active or not
        $activeCatIDs = $this->checkIsValidCategory($productId);

        // Check the active category array is empty $activeCatIDs
        // so it's product is invalid
        if (__isEmpty($activeCatIDs)) {
            return __engineReaction(2);
        }

        // get product related material like option related products etc
        $productDetails = $this->getProductForDetails($product);

        $detailURL = route('product.details', (!empty($categoryID))
                    ? [$productId, str_slug($productName), $categoryID]
                    : ['productID' => $productId, 'productName' => str_slug($productName)]);

        $productDetails['detailURL'] = $detailURL;
        $productDetails['productImage'] = getProductImageURL($productId, $product->thumbnail);
        $productDetails['qtyCart'] = (__isEmpty($productDetails->cartProduct))
                                            ? 1 : $productDetails->cartProduct['qty'];
        $productDetails['isCartExist'] = ShoppingCart::where($productId);
        $productDetails['oldPrice' ] = ($productDetails->old_price) ? priceFormat($productDetails->old_price) : '';

        return __engineReaction(1, ['details' => $productDetails]);
    }

    /**
     * prepare product details for normal page.
     *
     * @param int $productID
     * @param int $pageType
     * @param int $categoryID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function preparDetails($productID, $pageType, $categoryID = null)
    {
        $product = $this->productRepository->fetchDetails($productID);

        // Check if product exist or not 
        if (__isEmpty($product)) {
            return __engineReaction(18);
        }

        $brand = $product->brand;
        $brandId = $product->brands__id;
        $isBrandInValid = false;
        $brandData = [];

        // If brand exits then check if is active or not
        if (!__isEmpty($brand)) {

            // if logged in user is not admin then show error notification
            if (!isAdmin() and $brand->status !== 1) {
                return __engineReaction(18);
            }

            if ($brand->status !== 1) {
                $isBrandInValid = true;
            }

            $brandData = [
                'id' => $brandId,
                'logoImageURL' => getBrandLogoURL($brandId, $brand->logo),
                'name' => $brand->name,
            ];
        }

        $productId = $product->id;
        $productName = $product->name;

        // Check this product of category is active or not
        $activeCatIDs = $this->checkIsValidCategory($productId);

        // Check the active category array is empty $activeCatIDs
        // so it's product is invalid
        if (!isAdmin() and __isEmpty($activeCatIDs)) {
            return __engineReaction(18);
        }

        // get the product specification data
        $specificationData = [];

        if (!__isEmpty($product->specification)) {
            $productSpecification = $product->specification;

            foreach ($productSpecification as $value) {
                $specificationData[] = [
                    'name' => $value->name,
                    'value' => $value->value,
                ];
            }
        }

        $searchValues['id'] = $productId;

        $productImage[0] = [
            'products_id' => $productId,
            'file_name' => $product->thumbnail,
            'title' => $product->name,
        ];

        $imageSliderData = [];

        // making a format of image slider
        if (!__isEmpty($product->image)) {
            foreach ($product->image as $key => $image) {
                $imageSliderData[$key] = [
                    'products_id' => $image['products_id'],
                    'file_name' => $image['file_name'],
                    'title' => $image['title'],
                ];
            }
        }

        // marge product image in index 0
        $images = array_merge($productImage, $imageSliderData);

        // get active categories for product related
        $categories = $this->getProductRelatedActiveCategories($product->categories);

        // get the RelatedProduct data
        $relatedProducts = $this->getRelatedProduct($product->relatedProducts);

        $productDetails = [
            'id' => $productId,
            'name' => $productName,
            'status' => $product->status,
            'brand' => $brandData,
            'isBrandInValid' => $isBrandInValid,
            'product_id' => $product->product_id,
            'description' => $product->description,
        ];

        // without javascript interation data
        $serverPutProductData = [
            'image' => $images,
            'details' => $productDetails,
            'specifications' => $specificationData,
            'youtubeVideoCode' => ($product->youtube_video) ? $product->youtube_video : null,
            'categories' => $categories,
            'isActiveCategory' => $activeCatIDs,
            'relatedProductData' => $relatedProducts,
        ];

        if ($pageType == '') {
            $pageType = 'categories';
        }

        $breadCrumb = Breadcrumb::generate('productDetails', $productId,
                                             ['pageType' => $pageType,
                                             'categoryID' => $categoryID, ]
                                            );

        return __engineReaction(1, [
                'serverPutProductData' => $serverPutProductData,
                'breadCrumb' => $breadCrumb,
            ]);
    }

    /**
     * Prepare product list.
     *
     * @param int    $categoryID
     * @param array  $input
     * @param string $featureProductRouteName
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList($categoryID = null, $input = null, $featureProductRouteName)
    {
        $categoryCollection = $this->productRepository->fetchCategories();
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

        $route = !empty($featureProductRouteName)
                    ? $featureProductRouteName
                    : Route::currentRouteName();

        $brands = [];

        // When the category is available
        if (!__isEmpty($categoryID)) {
            $category = $this->productRepository->fetchCategoryByID($categoryID);

            $pageType = 'categories';
            $breadCrumbType = Breadcrumb::generate('categories', $categoryID);

            // Check if category exist
            if (__isEmpty($category)) {
                return __engineReaction(18, [
                                    'pageType' => $pageType,
                                    'breadCrumb' => $breadCrumbType,
                                ]);
            }

            $activeCatIds = findActiveParentsNChilds(
                                    $categoryCollection,
                                    $category->id
                                );

            // check if the parent cat deactive so do not display child product
            if ($activeCatIds['parents'] === false) {
                return __engineReaction(18, [
                                    'pageType' => $pageType,
                                    'breadCrumb' => $breadCrumbType,
                                ]);
            }

            // fetch valid product base on valid categories
            $productCollection = $this->productRepository
                                      ->fetchCategorieProducts(
                                          $activeCatIds['childrens'],
                                          $input, $inactiveBrandIds
                                    );

            // fetch product without pagination					      
            $resultedProudctOfBrandIds = $this->productRepository
                                               ->fetchCategorieWithoutPaginate(
                                                  $activeCatIds['childrens'],
                                                  $route, $inactiveBrandIds
                                              );

            // fetch min & max price of product
            $productPrices = $this->productRepository
                                   ->fetchMaxAndMinPrice(
                                        $activeCatIds['childrens'],
                                        $route, $inactiveBrandIds
                                    );

            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        } else {

            // find all active categories
            $allActiveCategories = findActiveChilds($categoryCollection, $categoryID);

            // fetch products data base on active categories & also valid product
            $productCollection = $this->productRepository->fetchAll($allActiveCategories, $input, $route, $inactiveBrandIds);

            // fetch product without pagination					      
            $resultedProudctOfBrandIds = $this->productRepository
                                              ->fetchAllWithoutPaginate(
                                                  $allActiveCategories, $input, $route, $inactiveBrandIds
                                              );

            $pageType = 'products';
            $breadCrumbType = Breadcrumb::generate('products');

            if ($route === 'products.featured') {
                $pageType = 'featured';
                $breadCrumbType = Breadcrumb::generate('featured');
            }

            // fetch min & max price of product
            $productPrices = $this->productRepository->fetchMaxAndMinPrice($allActiveCategories, $route, $inactiveBrandIds);

            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        }

        // fetch brnd record if available
        if (isset($input['sbid']) and !__isEmpty($input['sbid'])) {
            $brands = $this->brandRepository->fetchBrand($input['sbid']);
        }

        $paginationData = [
            'currentPage' => $productCollection->currentPage(),
            'lastPage' => $productCollection->lastPage(),
            'nextPageURL' => $productCollection->nextPageUrl(),
        ];

        $charactorLimit = config('__tech.character_limit');
        $paginateCount = getStoreSettings('pagination_count');

        $products = [];

        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {
                $productName = $product->name;
                $productID = $product->id;
                $productSlugName = str_slug($productName);

                $products[] = [
                    'id' => $productID,
                    'name' => str_limit($productName,
                                            $limit = $charactorLimit,
                                            $end = '...'),
                    'slugName' => $productSlugName,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'out_of_stock' => $product->out_of_stock,
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'formate_price' => priceFormat($product->price),
                    'detailURL' => route('product.details', [
                                            $productID, $productSlugName, $categoryID, ]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                ];
            }
        }

        $productsBrandID = (!empty($input['sbid'])) ? $input['sbid'] : '';

        $brandIds = __ifIsset($resultedProudctOfBrandIds, implode($resultedProudctOfBrandIds, '|'), '');

        return __engineReaction(1, [
            'breadCrumb' => $breadCrumbType,
            'pageType' => (!empty($pageType)) ? '?page_type='.$pageType : '',
            'productCollection' => $productCollection,
            'brands' => $brands,
            'filterUrl' => route('product.filter').'?brands='.$brandIds,
            'currentRoute' => Request::url(),
            'filterPrices' => __ifIsset($priceFilteredArray['filtered_price'], $priceFilteredArray['filtered_price'], ''),
            'productPrices' => __ifIsset($productPrices, $productPrices, []),
            'showFilterPrice' => __ifIsset($priceFilteredArray['show_filtered_price'], $priceFilteredArray['show_filtered_price'], ''),
            'products' => $products,
            'productsBrandID' => $productsBrandID,
            'paginationData' => $paginationData,
            'category' => isset($category) ? $category : null,
            'customRoute' => $route,
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
        ]);
    }

    /**
     * Prepare product list multiple categories.
     *
     * @param int    $categoryIDs
     * @param array  $input
     * @param string $featureProductRouteName
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareListMultipleCategories($categoryIDs = null, $input = null)
    {
        $categoryCollection = $this->productRepository->fetchCategories();
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

        $route = !empty($featureProductRouteName)
            ? $featureProductRouteName
            : Route::currentRouteName();


        $brands = [];

        // When the category is available
        if (!__isEmpty($categoryIDs)) {
            $pageType = 'categories';
            $breadCrumbType = Breadcrumb::generate('categories', $categoryIDs);

            // fetch valid product base on valid categories
            $productCollection = $this->productRepository
                                      ->fetchCategorieProducts(
                                          $categoryIDs,
                                          $input, $inactiveBrandIds
                                    );

            // fetch product without pagination                       
            $resultedProudctOfBrandIds = $this->productRepository
                                               ->fetchCategorieWithoutPaginate(
                                                  $categoryIDs,
                                                  $route, $inactiveBrandIds
                                              );

            // fetch min & max price of product
            $productPrices = $this->productRepository
                                   ->fetchMaxAndMinPrice(
                                        $categoryIDs,
                                        $route, $inactiveBrandIds
                                    );

            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        } else {

            // find all active categories
            $allActiveCategories = findActiveChilds($categoryCollection, $categoryIDs);

            // fetch products data base on active categories & also valid product
            $productCollection = $this->productRepository->fetchAll($allActiveCategories, $input, $route, $inactiveBrandIds);

            // fetch product without pagination                       
            $resultedProudctOfBrandIds = $this->productRepository
                                              ->fetchAllWithoutPaginate(
                                                  $allActiveCategories, $input, $route, $inactiveBrandIds
                                              );

            $pageType = 'products';
            $breadCrumbType = Breadcrumb::generate('products');

            if ($route === 'products.featured') {
                $pageType = 'featured';
                $breadCrumbType = Breadcrumb::generate('featured');
            }

            // fetch min & max price of product
            $productPrices = $this->productRepository->fetchMaxAndMinPrice($allActiveCategories, $route, $inactiveBrandIds);

            // filter array of max & min price
            $priceFilteredArray = $this->priceFilter($productPrices, $input);
        }

        // fetch brnd record if available
        if (isset($input['sbid']) and !__isEmpty($input['sbid'])) {
            $brands = $this->brandRepository->fetchBrand($input['sbid']);
        }

        $paginationData = [
            'currentPage' => $productCollection->currentPage(),
            'lastPage' => $productCollection->lastPage(),
            'nextPageURL' => $productCollection->nextPageUrl(),
        ];

        $charactorLimit = config('__tech.character_limit');
        $paginateCount = getStoreSettings('pagination_count');

        $products = [];

        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {
                $productName = $product->name;
                $productID = $product->id;
                $productSlugName = str_slug($productName);

                $products[] = [
                    'id' => $productID,
                    'name' => str_limit($productName,
                                            $limit = $charactorLimit,
                                            $end = '...'),
                    'slugName' => $productSlugName,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'out_of_stock' => $product->out_of_stock,
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'formate_price' => priceFormat($product->price),
                    'detailURL' => route('product.details', [
                                            $productID, $productSlugName, '', ]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                ];
            }
        }

        $productsBrandID = (!empty($input['sbid'])) ? $input['sbid'] : '';

        $brandIds = __ifIsset($resultedProudctOfBrandIds, implode($resultedProudctOfBrandIds, '|'), '');

        return __engineReaction(1, [
            'breadCrumb' => $breadCrumbType,
            'pageType' => (!empty($pageType)) ? '?page_type='.$pageType : '',
            'productCollection' => $productCollection,
            'brands' => $brands,
            'filterUrl' => route('product.filter').'?brands='.$brandIds,
            'currentRoute' => Request::url(),
            'filterPrices' => __ifIsset($priceFilteredArray['filtered_price'], $priceFilteredArray['filtered_price'], ''),
            'productPrices' => __ifIsset($productPrices, $productPrices, []),
            'showFilterPrice' => __ifIsset($priceFilteredArray['show_filtered_price'], $priceFilteredArray['show_filtered_price'], ''),
            'products' => $products,
            'productsBrandID' => $productsBrandID,
            'paginationData' => $paginationData,
            'category' => isset($category) ? $category : null,
            'customRoute' => $route,
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
        ]);
    }

    /**
     * get product data.
     *
     * @param int $productID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function getProduct($productID)
    {
        return $this->productRepository->fetchProduct($productID);
    }

    /**
     * get active categories.
     *
     * @param int $productIDs
     *
     * @return object
     *---------------------------------------------------------------- */
    protected function getActiveCategories($productIDs)
    {
        $productsCategories = $this->productRepository
                                    ->fetchProductCategory($productIDs);
        $categoryIDs = [];
        $categoryCollection = $this->productRepository->fetchCategories();
        $productCat = false;
        if (!empty($productsCategories)) {
            foreach ($productsCategories as $key => $productCategory) {
                $response = findActiveParentsNChilds(
                                        $categoryCollection,
                                        $productCategory['categories_id']
                                    );

                if (!__isEmpty($response['parents'])) {
                    $productCat = true;
                }
            }
        }

        return $productCat;
    }

    /**
     * returnEmptyData.
     *
     * @return array
     *---------------------------------------------------------------- */
    private function returnEmptyData($searchTerm)
    {
        return [
                'filterPrices' => [],
                'breadCrumb' => BreadCrumb::generate('productSearch', null, ['searchTerm' => $searchTerm]),
                'searchTerm' => $searchTerm,
                'productCount' => 0,
                'filterUrl' => route('product.filter').'?brands='.'',
                'productPrices' => [],
                'showFilterPrice' => [],
                'pageType' => '',
                'currentRoute' => '',
                'paginationData' => [],
                'productsBrandID' => [],
                'customRoute' => '',
            ];
    }

    /**
     * check the search product is valid.
     *
     * @param array  $activeCategoryIds
     * @param object $productIds
     *---------------------------------------------------------------- */
    protected function isValidProducts($productIds, $categoryId = null)
    {
        $activeCategoryIds = findActiveChilds(
                                $this->productRepository->fetchCategories(),
                                $categoryId
                            );

        // check if the any category is active or not
        if (__isEmpty($activeCategoryIds)) {
            return [];
        }

        // get product categories
        $productCategories = $this->productRepository
                                  ->fetchProductsCategories($productIds);

        if (__isEmpty($productCategories)) {
            return [];
        }

        $validProductIds = [];

        foreach ($productCategories as $key => $productCategory) {

            // in array check the category id is available in  $activeCategoryIds array
            // it means the product of category is valid 
            if (in_array($productCategory->categories_id, $activeCategoryIds)) {
                $validProductIds[$key] = $productCategory->products_id;
            }
        }

        return array_unique($validProductIds);
    }

    /**
     * Prepare search data.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSearch($input)
    {
        $searchTerm = __ifIsset($input['search_term'], $input['search_term'], '');

        // fetch searh products ids 
        $searchedProductIds = $this->productRepository->fetchSearchProducts($searchTerm);

        // found record of search products or not 
        if (__isEmpty($searchedProductIds)) {
            return __engineReaction(2, $this->returnEmptyData($searchTerm));
        }

        $validProductIds = $this->isValidProducts($searchedProductIds, null);

        // get valid products ids
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2, $this->returnEmptyData($searchTerm));
        }

        // fetch inactive brand lists
        $inactiveBrandIds = $this->brandRepository->fetchInactiveBrand();

        // fetch valid products data base on ids
        $productCollection = $this->productRepository
                                    ->fetchSearchData($validProductIds, $input, $inactiveBrandIds);

        // fetch search product data filter             	  
        $resultedProudctOfBrandIds = $this->productRepository
                                          ->fetchSearchedProductDataForFilter($validProductIds, $input, $inactiveBrandIds);

        $brands = [];

        // fetch brnd record if available
        if (isset($input['sbid']) and !__isEmpty($input['sbid'])) {
            $brands = $this->brandRepository->fetchBrand($input['sbid']);
        }

        // fetch min & max price of product
        $productPrices = $this->productRepository->fetchMaxAndMinPriceOfProduct($validProductIds, $inactiveBrandIds);

        // filter array of max & min price
        $priceFilteredArray = $this->priceFilter($productPrices, $input);

        $paginationData = [
            'currentPage' => $productCollection->currentPage(),
            'lastPage' => $productCollection->lastPage(),
            'nextPageURL' => $productCollection->nextPageUrl(),
        ];

        $products = [];
        $brandIDs = [];

        $charactorLimit = config('__tech.character_limit');

        // Check if products not empty
        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {
                $productName = $product->name;
                $productID = $product->id;
                $productIDs[] = $productID;
                $slugName = str_slug($productName);
                $products[] = [
                    'id' => $productID,
                    'name' => str_limit($productName, $limit = $charactorLimit, $end = '...'),
                    'slugName' => $slugName,
                    'formate_price' => priceFormat($product->price),
                    'out_of_stock' => $product->out_of_stock,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'detailURL' => route('product.details', [$productID, $slugName]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                ];
            }
        }

        $productsBrandID = [];

        if (!empty($input['sbid'])) {
            $productsBrandID = $input['sbid'];
        }

        // set brnad filter url source for data
        $brandIds = __ifIsset($resultedProudctOfBrandIds, implode($resultedProudctOfBrandIds, '|'), '');

        return __engineReaction(1, [
            'breadCrumb' => BreadCrumb::generate('productSearch', null, ['searchTerm' => $searchTerm]),
            'searchTerm' => $searchTerm,
            'productCount' => $productCollection->total(),
            'productCollection' => $productCollection,
            'products' => $products,
            'filterUrl' => route('product.filter').'?brands='.$brandIds,
            'filterPrices' => $priceFilteredArray['filtered_price'],
            'productPrices' => $productPrices,
            'showFilterPrice' => $priceFilteredArray['show_filtered_price'],
            'pageType' => 'search',
            'currentRoute' => Request::url(),
            'paginationData' => $paginationData,
            'brands' => $brands,
            'productsBrandID' => $productsBrandID,
            'category' => isset($category) ? $category : null,
            'customRoute' => Route::currentRouteName(),
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
        ]);
    }

    /**
     * prepare filter for search data.
     *
     * @param array $input
     *
     * @return object
     *---------------------------------------------------------------- */
    public function prepareFilter($input)
    {
        $brandsIds = [];

        if (__ifIsset($input['brands'])) {
            $brandsIds = explode('|', $input['brands']);
        }

        // fetch brands of display found result of products
        $brands = $this->brandRepository->fetchBrand($brandsIds);

        return __engineReaction(1, ['productRelatedBrand' => $brands]);
    }

    /**
     * get products raleted brands.
     *
     * @param int $brandID
     *
     * @return object
     *---------------------------------------------------------------- */
    public function prepareFilterBrandRelatedProduct($brandID = null)
    {
        $brand = $this->brandRepository->fetchIsActiveByID($brandID);

        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        // fetch product record base on brand id
        $productIds = $this->productRepository
                                 ->fetchProductByBrandId($brand->_id);

        // check  founded brand products is valid                	   
        $validProductIds = $this->isValidProducts($productIds, null);

        // if not valid product so there is no valid product available 
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2);
        }

        $productRelatedBrand = $this->productRepository
                                      ->fetchFilterBrandRelatedProduct($validProductIds);

        return __engineReaction(1, ['productRelatedBrand' => $productRelatedBrand]);
    }

    /**
     * get brand raleted products.
     *
     * @param int $brandID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareBrandRelatedProduct($brandID, $brandName, $input = [])
    {
        $brand = $this->brandRepository->fetchIsActiveByID($brandID);

        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        $productIds = $this->productRepository
                               ->fetchProductByBrandId($brand->_id);

        // verify the product ids & return valid product ids
        $validProductIds = $this->isValidProducts($productIds, null);

        // check the any valid product id empty $validProductIds
        // it means empty then valid product not available 
        if (__isEmpty($validProductIds)) {
            return __engineReaction(2, [
                'breadCrumb' => BreadCrumb::generate('brandProduct', $brandID),
                'brandID' => $brandID,
                'brand' => $brand,
                'filterUrl' => route('product.filter'),
                'currentRoute' => Request::url(),
                'customRoute' => Route::currentRouteName(),
                'filterPrices' => [],
                'productPrices' => [],
                'paginationData' => [],
                'productExistOrNot' => false,
            ]);
        }

        // fetch the product data
        $productCollection = $this->productRepository
                                     ->fetchBrandRelatedProduct($validProductIds, $input);

        $paginationData = [
            'currentPage' => $productCollection->currentPage(),
            'lastPage' => $productCollection->lastPage(),
            'nextPageURL' => $productCollection->nextPageUrl(),
        ];

        $products = [];
        $charactorLimit = config('__tech.character_limit');

        // Check if products not empty
        if ($productCollection->total() != 0) {
            foreach ($productCollection as $product) {
                $productName = $product->name;
                $productID = $product->id;
                $productIDs[] = $product->id;
                $slugName = str_slug($productName);

                $products[] = [
                    'id' => $productID,
                    'name' => str_limit($productName,
                                            $limit = $charactorLimit,
                                            $end = '...'),
                    'slugName' => $slugName,
                    'formate_price' => priceFormat($product->price),
                    'out_of_stock' => $product->out_of_stock,
                    'thumbnailURL' => getProductImageURL($productID, $product->thumbnail),
                    'price' => $product->price,
                    'featured' => $product->featured,
                    'detailURL' => route('product.details', [
                                            $productID, $slugName, ]),
                    'options' => !__isEmpty($product->checkOptionExists) ? true : false,
                ];
            }
        }

        // fetch min & max price of product
        $productPrices = $this->productRepository->fetchMaxAndMinPriceOfProduct($validProductIds);

        // filter array of max & min price
        $priceFilteredArray = $this->priceFilter($productPrices, $input);

        return __engineReaction(1, [
            'breadCrumb' => BreadCrumb::generate('brandProduct', $brandID),
            'productCollection' => $productCollection,
            'products' => $products,
            'currentRoute' => Request::url(),
            'filterUrl' => route('product.filter'),
            'filterPrices' => $priceFilteredArray['filtered_price'],
            'productPrices' => $productPrices,
            'showFilterPrice' => $priceFilteredArray['show_filtered_price'],
            'brandID' => $brandID,
            'paginationData' => $paginationData,
            'brand' => $brand,
            'customRoute' => Route::currentRouteName(),
            'productExistOrNot' => $productCollection->hasMorePages() ? true : false,
        ]);
    }
}
