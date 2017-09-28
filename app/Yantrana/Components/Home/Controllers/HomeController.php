<?php
/*
* HomeController.php - Controller file
*
* This file is part of the Home component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Home\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Pages\PagesEngine;
use App\Yantrana\Components\Product\ProductEngine;
use App\Yantrana\Components\Brand\BrandEngine;
use Illuminate\Http\Request;
use JavaScript;

class HomeController extends BaseController
{
    /**
     * @var PagesEngine - Pages Engine
     */
    protected $pagesEngine;

    /**
     * @var ProductEngine - Product Engine
     */
    protected $productEngine;

    /**
     * @var BrandEngine - Brand Engine
     */
    protected $brandEngine;

    /**
     * Constructor.
     *
     * @param PagesEngine $pagesEngine - Pages Engine
     *-----------------------------------------------------------------------*/
    public function __construct(PagesEngine $pagesEngine,
        ProductEngine $productEngine,
        BrandEngine $brandEngine)
    {
        $this->pagesEngine = $pagesEngine;
        $this->productEngine = $productEngine;
        $this->brandEngine = $brandEngine;
    }

    /**
     * Handle home view request.
     *---------------------------------------------------------------- */
    public function home(Request $request)
    {
        $homePage = getStoreSettings('home_page');

        $homePage = (int) $homePage;

        if (__isEmpty(getStoreSettings('home_page')) || $homePage === 1) {
            $details = $this->pagesEngine->getDetails(1); // home page id is 1

            return $this->loadPublicView('pages.display-details', $details['data']);
        } elseif ($homePage === 2) {
            return $this->getProductsList($request, $categoryID = null, 'products');
        } elseif ($homePage === 3) {
            return $this->getProductsList($request, $categoryID = null, 'products.featured');
        } elseif ($homePage === 4) {
            return $this->getBrandList();
        }
    }

    /**
     * disply the product list.
     *
     * @param $request
     * @param $categoryID
     *
     * @return view
     *---------------------------------------------------------------- */
    protected function getProductsList($request, $categoryID, $route)
    {
        $processReaction = $this->productEngine
                                ->prepareList($categoryID, $request->all(), $route);

        //Route::currentRouteName()						
        if ($processReaction['reaction_code'] === 18) {
            return $this->loadPublicView('errors.public-not-found');
        }

        $products = $processReaction['data'];

        // Check if current ajax request
        if ($request->ajax()) {
            return __processResponse(
                $processReaction,
                [],
                $products
            );
        }

        if (!empty($request['sort_by'])) {
            $products['sortBy'] = $request['sort_by'];
        }

        if (!empty($request['sort_order'])) {
            $products['sortOrder'] = $request['sort_order'];
        }

        $data = $products;

        JavaScript::put([
            'productPaginationData' => $data['paginationData'],
            'categoryData' => $data['category'],
            'filterUrl' => $data['filterUrl'],
            'productPrices' => $data['productPrices'],
            'filterPrices' => $data['filterPrices'],
            'brandID' => $data['productsBrandID'],
            'currentRoute' => $data['currentRoute'],
            'currenSymbol' => getStoreSettings('currency_symbol'),
            'categoryID' => (!empty($categoryID)) ?
                                                       $categoryID : '',
            'pageType' => $data['pageType'],
            'sortOrderUrl' => sortOrderUrl(null, ['orderChange' => false]),
        ]);

        return $this->loadPublicView('product.list', $data);
    }

    /**
     * disply the brnad list.
     *
     * @return view
     *---------------------------------------------------------------- */
    protected function getBrandList()
    {
        $processReaction = $this->brandEngine
                                ->fetchIsActive();

        $brands = $processReaction['data'];

        return $this->loadPublicView('brand.list', $brands);
    }

    /**
     * ChangeLocale - It also managed from index.php.
     *---------------------------------------------------------------- */
    protected function changeLocale(Request $request, $localeId = null)
    {
        if (is_string($localeId)) {
            changeAppLocale($localeId);
        }
        if ($request->has('redirectTo')) {
            header('Location: '.base64_decode($request->get('redirectTo')));
            exit();
        }

        return __('Invalid Request');
    }
}
