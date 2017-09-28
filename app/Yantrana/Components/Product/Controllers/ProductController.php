<?php
/*
* ProductController.php - Controller file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Category;
use App\Yantrana\Components\Product\ProductEngine;
use Route;
use Config;
use JavaScript;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * @var productAssets - Product Assets
     */
    protected $productAssets;

    /**
     * @var categories
     */
    protected $categories;

    /**
     * Constructor.
     *
     * @param ProductEngine $productEngine - Product Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ProductEngine $productEngine)
    {
        $this->productEngine = $productEngine;
        $this->productAssets = Config('__tech.product_assets');
    }

    /**
     * Render product list view.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    public function all(Request $request, $categoryID = null)
    {

        $categoryIDs = $request->get('categoryID');

        if(!empty($categoryIDs)){
            
            $processReaction = $this->productEngine
                                ->prepareListMultipleCategories(
                                    $categoryIDs,
                                    $request->all(),
                                    Route::currentRouteName()
                                );
        }else{
            $processReaction = $this->productEngine
                                    ->prepareList(
                                        $categoryID,
                                        $request->all(),
                                        Route::currentRouteName()
                                    );
        }

        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('home.page')
                             ->with([
                                'error' => true,
                                'message' => __('Requested category does not exist.'),
                            ]);
        }

        $products = $processReaction['data'];


        if (!empty($request['sort_by'])) {
            $products['sortBy'] = $request['sort_by'];
        }

        if (!empty($request['sort_order'])) {
            $products['sortOrder'] = $request['sort_order'];
        }

        $sortByArray = ['name', 'price'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $products['sortBy'] = __('name');
        }

        $data = $products;

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'filterUrl' => $data['filterUrl'],
            'categoryData' => $data['category'],
            'productPrices' => $data['productPrices'],
            'filterPrices' => $data['filterPrices'],
            'brandID' => $data['productsBrandID'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getStoreSettings('currency_symbol'),
            'categoryID' => (!empty($categoryID)) ? $categoryID : '',
            'pageType' => $data['pageType'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
        ]);

        // Check if current ajax request
        if ($request->ajax()) {
            /*return __processResponse(
                $processReaction,
                [],
                $products
            );*/

            return view('product.list', $data)->render();
        }else
            return $this->loadPublicView('product.list', $data);
    }

    /**
     * get products details.
     * 
     * @param array $request
     * @param int   $productID
     * @param int   $productID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function quickViewDetailsSupportData(Request $request, $productID, $categoryID = null)
    {
        $products = $this->productEngine->getQuickViewDetailsData($productID, $categoryID);

        // get engine reaction						
        return __processResponse($products, [
                2 => __('Sorry this product is currently not available, Please reload the page.'),
            ], $products['data']);
    }

    /**
     * Render product details view.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function details(Request $request, $productID, $productName, $categoryID = null)
    {
        $pageType = $request['page_type'];

        $processReaction = $this->productEngine
                                   ->preparDetails($productID, $pageType, $categoryID);

        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('home.page');
        }

        $details = $processReaction['data'];

        JavaScript::put([
            'productID' => (!empty($productID)) ? $productID : '',
            'categoryID' => (!empty($categoryID)) ? $categoryID : '',
        ]);

        $product = $details['serverPutProductData'];

        $data = [
            'breadCrumb' => $details['breadCrumb'],
            'images' => $product['image'],
            'product' => $product['details'],
            'specifications' => $product['specifications'],
            'youtubeVideoCode' => $product['youtubeVideoCode'],
            'categories' => $product['categories'],
            'isActiveCategory' => $product['isActiveCategory'],
            'relatedProductData' => $product['relatedProductData'],
        ];

        return $this->loadPublicView('product.details', $data);
    }

    /**
     * search products supported data.
     *
     * @param array $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function search(Request $request)
    {
        // user input data
        $search = $this->productEngine->prepareSearch($request->all());

        if ($request->ajax()) {
            return __processResponse(
                $search,
                [],
                $search['data']
            );
        }

        $search['data']['sortBy'] = (!empty($request['sort_by'])) ? $request['sort_by'] : '';
        $search['data']['sortOrder'] = (!empty($request['sort_order'])) ? $request['sort_order'] : '';

        $sortByArray = ['name', 'price'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $search['data']['sortBy'] = __('name');
        }

        $data = $search['data'];

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'productPrices' => $data['productPrices'],
            'filterUrl' => $data['filterUrl'],
            'filterPrices' => $data['filterPrices'],
            'searchTerm' => $request['search_term'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getStoreSettings('currency_symbol'),
            'brandID' => $data['productsBrandID'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
            'pageType' => $data['pageType'],
        ]);

        return $this->loadPublicView('product.list', $data);
    }

    /**
     * Brnad related product ist.
     *
     * @param array  $request
     * @param int    $productID
     * @param string $productName
     * @param int    $categoryID
     *---------------------------------------------------------------- */
    public function brandRelatedProducts(Request $request, $brandID, $brandName = null)
    {
        $processReaction = $this->productEngine
                                ->prepareBrandRelatedProduct($brandID, $brandName, $request->all());

        if ($processReaction['reaction_code'] === 18) {
            return redirect()->route('home.page');
        }

        if ($request->ajax()) {
            return __processResponse(
                $processReaction,
                [],
                $processReaction['data']
            );
        }

        $products = $processReaction['data'];

        if (!empty($request['sort_by'])) {
            $products['sortBy'] = $request['sort_by'];
        }

        if (!empty($request['sort_order'])) {
            $products['sortOrder'] = $request['sort_order'];
        }

        $sortByArray = ['name', 'price'];

        // when string not match then add by default name
        if (!empty($request['sort_by']) and !in_array($request['sort_by'], $sortByArray)) {
            $products['data']['sortBy'] = __('name');
        }

        $data = $products;

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'productPrices' => $data['productPrices'],
            'pageType' => 'brand',
            'filterUrl' => $data['filterUrl'],
            'currenSymbol' => getStoreSettings('currency_symbol'),
            'filterPrices' => $data['filterPrices'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
        ]);

        return $this->loadPublicView('product.list', $data);
    }

    /**
     * get data of founded result of product list.
     *
     * @param int $categoryID
     *---------------------------------------------------------------- */
    public function filterAll(Request $request)
    {
        // user input data
        $filter = $this->productEngine->prepareFilter($request->all());

        JavaScript::put([
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
        ]);

        return __processResponse($filter, [
                2 => __('Brand does not exist.'),
            ], $filter['data']);
    }

    /**
     * To show for filtering data.
     *---------------------------------------------------------------- */
    public function filterSearch(Request $request)
    {
        // user input data
        $filter = $this->productEngine->prepareFilterSearch();

        return __processResponse($filter, [
                2 => __('Product does not exist.'),
            ], $filter['data']);
    }

    /**
     * brand related filter dialog data.
     *
     * @param $brandID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function filterBrandRelatedProduct($brandID = null)
    {
        // user input data
        $filter = $this->productEngine
                          ->prepareFilterBrandRelatedProduct($brandID);

        return __processResponse($filter, [
                18 => __('Product does not exist.'),
            ], $filter['data']);
    }
}
