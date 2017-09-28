<?php

namespace App\Yantrana\Support\Breadcrumb;

use App\Yantrana\Components\Category\Models\Category as CategoryModel;
use App\Yantrana\Components\Product\Models\Product as ProductModel;
use App\Yantrana\Components\Pages\Models\Page as PageModel;
use App\Yantrana\Components\Brand\Models\Brand as BrandModel;

/**
 * This BreadCrumb class for manage breadCrumb globally -.
 *---------------------------------------------------------------- */
class Breadcrumb
{
    /**
     * This method use for display breadCrumb.
     *
     * @param array $mailData
     * 
     * @return bool
     *---------------------------------------------------------------- */
    public function generate($dataType = null, $id = null, $options = [])
    {
        $breadCrumb = [];

        switch (true) {

            case $dataType == 'products':
                $breadCrumb = $this->products($dataType);
            break;

            case $dataType == 'home':
                $breadCrumb = $this->home($dataType);
            break;

            case $dataType == 'categories':
                $breadCrumb = $this->categoryProduct($dataType, $id);
            break;

            case $dataType == 'featured':
                $breadCrumb = $this->featuredProducts($dataType);
            break;

            case $dataType == 'productSearch':
                $breadCrumb = $this->productSearch($dataType, $options['searchTerm']);
            break;

            case $dataType == 'brandProduct':
                $breadCrumb = $this->brandRelatedProduct($dataType, $id);
            break;

            case $dataType == 'brand':
                $breadCrumb = $this->brand($dataType);
            break;

            case $dataType == 'productDetails':
                $breadCrumb = $this->productDetails($dataType, $id, $options);
            break;

            case $dataType == 'pages':
                $breadCrumb = $this->pages($dataType, $id);
            break;

            case $dataType == 'address':
                $breadCrumb = $this->addresses($dataType);
            break;

            case $dataType == 'order':
                $breadCrumb = $this->orders($dataType);
            break;

            case $dataType == 'orderDetail':
                $breadCrumb = $this->orderDetail($dataType);
            break;

            case $dataType == 'cart-order':
                $breadCrumb = $this->cartOrders($dataType);
            break;

            case $dataType == 'shopping-cart':
                $breadCrumb = $this->shoppingCart($dataType);
            break;

            default :
                $breadCrumb = $this->users($dataType);
        }

        return $breadCrumb;
    }

    /**
     * This method use for display home page breadCrumb.
     *
     * @param string $homeType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function home($homeType)
    {
        if (!empty($homeType)) {
            $collectionBreadcrumb = [
                'title' => __('Home'),
            ];

            return $collectionBreadcrumb;
        }
    }

    /**
     * This method use for display product breadCrumb.
     *
     * @param string $productType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function products($productType)
    {
        if (!empty($productType)) {
            $collectionBreadcrumb = [
                'parents' => [],
                'title' => __('Products'),
            ];

            return $collectionBreadcrumb;
        }
    }

    /**
     * This method use for display featured product breadCrumb.
     *
     * @param string $featuredType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function featuredProducts($featuredType)
    {
        if (!empty($featuredType)) {
            $product = $this->getProductBreadcrumb();

            $collectionBreadcrumb = [
                'parents' => $product,
                'title' => __('Featured Products'),
            ];

            return $collectionBreadcrumb;
        }
    }

    /**
     * This method use for display product search breadCrumb.
     *
     * @param string $searchType
     * @param string $searchTerm
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function productSearch($searchType, $searchTerm)
    {
        if (!empty($searchType)) {
            $searchTerm = $searchTerm;

            $product = $this->getProductBreadcrumb();

            if (empty($searchTerm)) {
                $searchTerm = __('All Product');
            }

            $collectionBreadcrumb = [
                'parents' => $product,
                'title' => __('Search Result'),
            ];

            return $collectionBreadcrumb;
        }
    }

    private function getProductBreadcrumb()
    {
        return $allProduct = [
            [
                'name' => __('Products'),
                'url' => productsRoute(),
            ],
        ];
    }

    public function brandRelatedProduct($dataType, $brandID)
    {
        if ($dataType == 'brandProduct') {
            $brand = [
                'name' => __('Brands'),
                'url' => route('fetch.brands'),
            ];

            $brandName = $this->getBrandnNameByID($brandID);

            $collectionBreadcrumb = [
                'parents' => [$brand],
                'title' => $brandName,
            ];

            return $collectionBreadcrumb;
        }

        return [];
    }

    protected function getBrandnNameByID($brandID)
    {
        return BrandModel::fetchByID($brandID)->value('name');
    }

    public function brand()
    {
        $collectionBreadcrumb = [
            'parents' => [],
            'title' => __('Brands'),
        ];

        return $collectionBreadcrumb;
    }

    /**
     * This method use for display product details breadCrumb.
     *
     * @param string $featuredType
     * @param number $productID
     * @param string $pageType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function productDetails($featuredType, $productID, $options)
    {
        $product = $this->getProductDetails($productID);
        $pageType = $options['pageType'];

        if (!empty($product)) {
            switch (true) {

            case $pageType == 'featured':

                $pageTypeData[] =
                [
                    'name' => __('Featured Products'),
                    'url' => productsFeatureRoute(),
                ];

                $pageTypeData = array_merge(
                                    $this->getProductBreadcrumb(),
                                    $pageTypeData
                                );
            break;

            case $pageType == 'products':

                $pageTypeData = $this->getProductBreadcrumb();

            break;

            case $pageType == 'r-product':

                $category = $this->getProductWithCategories($product['id']);

                $pageTypeData = [];
                if (!empty($category)) {
                    $pageTypeData[] = [
                        'name' => $category['name'],
                        'url' => categoriesProductRoute(
                                        $category['id'],
                                        $category['slugName']
                                    ),
                    ];
                    $pageTypeData = array_merge(
                                    $this->getProductBreadcrumb(),
                                    $pageTypeData
                                );
                }

            break;

            case $pageType == 'categories':
                $pageTypeData = [];

                $getCategory = $this->getCategoryByID($options['categoryID']);

                if (!empty($options['categoryID']) and (!empty($getCategory))) {
                    $pageTypeData[] = [
                            'name' => $getCategory['name'],
                            'url' => categoriesProductRoute(
                                        $getCategory['id'],
                                        $getCategory['slugName']
                                    ),
                        ];
                } else {
                    $category = $this->getProductWithCategories($product['id']);

                    if (!empty($category)) {
                        $pageTypeData[] = [
                            'name' => $category['name'],
                            'url' => categoriesProductRoute(
                                            $category['id'],
                                            $category['slugName']
                                        ),
                        ];
                    }
                }

                $pageTypeData = array_merge(
                                    $this->getProductBreadcrumb(),
                                    $pageTypeData
                                );
            break;

            default:
                $pageTypeData = [];
            }

            $collectionBreadcrumb = [
                'parents' => $pageTypeData,
                'title' => $product['name'],
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for get product details.
     *
     * @param number $productID
     *---------------------------------------------------------------- */
    private function getProductDetails($productID)
    {
        $productData = [];
        $product = ProductModel::find($productID);

        if (!empty($product)) {
            $productData = [
                'id' => $product->id,
                'slugName' => str_slug($product->name),
                'name' => $product->name,
            ];
        }

        return $productData;
    }

    /**
     * This method use for get categoory by id.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    private function getCategoryByID($categoryID = null)
    {
        $getCategory = CategoryModel::where('id', $categoryID)
                                        ->where('status', 1)->first();

        $categoryData = [];
        if (!empty($getCategory)) {
            $categoryData = [
                'id' => $getCategory->id,
                'slugName' => str_slug($getCategory->name),
                'name' => $getCategory->name,
            ];
        }

        return $categoryData;
    }

    /**
     * This method use for get categoory by id.
     *
     * @param number $categoryID
     *---------------------------------------------------------------- */
    private function getProductWithCategories($productID = null)
    {
        $product = ProductModel::with('categories')
                                ->where([
                                    'id' => $productID,
                                    'status' => 1, // active
                                ])->select(
                                    'id',
                                    'name'
                                )->first();

        $categoriesData = [];
        if (!empty($product->categories)) {
            foreach ($product->categories as $categories) {
                $categoriesData[] = [
                    'id' => $categories->id,
                    'name' => $categories->name,
                    'slugName' => str_slug($categories->name),
                ];
            }

            return array_shift($categoriesData);
        }

        return;
    }

    /**
     * This method use for get categories products.
     *
     * @param string $categoryType
     * @param number $categoryID
     *---------------------------------------------------------------- */
    private function categoryProduct($categoryType, $categoryID)
    {
        $categories = CategoryModel::where('status', 1)
                                    ->get([
                                        'id',
                                        'name',
                                        'parent_id',
                                    ])->toArray();

        $getRelatedItems = $this->getRelatedItems($categories, $categoryID);

        if (!empty($getRelatedItems)) {
            // return breadcrumb items
            return $this->getCategoriesItems($getRelatedItems, $categoryID);
        }

        return;
    }

    /**
     * This method use for get categories related product items.
     *
     * @param array  $categories
     * @param number $categoryID
     * @param array  $items
     *---------------------------------------------------------------- */
    private function getRelatedItems($categories, $categoryID = null, $items = [])
    {
        foreach ($categories as $row) {
            if ($row['id'] == $categoryID) {
                $items[] = $row;

                if ($row['parent_id']) {
                    $items = $this->getRelatedItems($categories, $row['parent_id'], $items);
                }

                break;
            }
        }

        return $items;
    }

    /**
     * This method use for get categories items breadCrumb.
     *
     * @param array  $relatedItems
     * @param number $currentItemID
     *---------------------------------------------------------------- */
    private function getCategoriesItems($relatedItems, $currentItemID)
    {
        $relatedItems = array_reverse($relatedItems);
        $categorysTitle = end($relatedItems);
        $title = $categorysTitle['name'];
        $product = [];
        $product = $this->getProductBreadcrumb();
        $categoryData = [];

        foreach ($relatedItems as $relatedItem) {
            if ($relatedItem['id'] != $currentItemID) {
                $categoryData[] = [
                    'name' => $relatedItem['name'],
                    'url' => categoriesProductRoute($relatedItem['id'],
                                        $relatedItem['name']),
                ];
            }
        }

        $newCategories = array_merge($product, $categoryData);

        $parentData = [
            'parents' => $newCategories,
            'title' => $title,
        ];

        return $parentData;
    }

    /**
     * This method use for get pages.
     *
     * @param string $pageType
     * @param number $pageID
     *---------------------------------------------------------------- */
    private function pages($pageType, $pageID)
    {
        $productsWhere = [
            'type' => 1,
        ];

        $pages = PageModel::/*where($productsWhere)
                                    ->*/get([
                                        'id',
                                        'title',
                                        'parent_id',
                                        'type',
                                        'link_details'
                                    ])->toArray();

        $getRelatedItems = $this->getRelatedPageItems($pages, $pageID);
        if (!empty($getRelatedItems)) {
            // return breadcrumb items
            return $this->getParentPages($getRelatedItems, $pageID);
        }

        return;
    }

    /**
     * This method use for get pages related items.
     *
     * @param array  $pages
     * @param number $pageID
     * @param array  $pageItems
     *---------------------------------------------------------------- */
    private function getRelatedPageItems($pages = [],  $pageID, $pageItems = [])
    {
        foreach ($pages as $row) {
            if ($row['id'] == $pageID) {
                $pageItems[] = $row;

                if ($row['parent_id']) {
                    $pageItems = $this->getRelatedItems($pages, $row['parent_id'], $pageItems);
                }

                break;
            }
        }

        return $pageItems;
    }

    /**
     * This method use for get parent pages.
     *
     * @param array  $relatedItems
     * @param number $currentItemID
     *---------------------------------------------------------------- */
    private function getParentPages($relatedItems, $currentItemID)
    {
        $relatedItems = array_reverse($relatedItems);
        $pageTitle = end($relatedItems);
        $title = $pageTitle['title'];
        $pagesData = [];

        foreach ($relatedItems as $relatedItem) {

            if ($relatedItem['id'] != $currentItemID) {

            	$url    = '';
            	$target = '';

            	if ($relatedItem['type'] == 2) {

            		$linkArray  = json_decode($relatedItem['link_details'], true);
            		$url    	=  $linkArray['value'];
            		$target 	= $linkArray['type'];

            	} else {

            		$url =  pageDetailsRoute(
                                $relatedItem['id'],
                                str_slug($relatedItem['title'])
                            );
            	}

                $pagesData[] = [
                    'name' 		=> $relatedItem['title'],
                    'target' 	=> $target,
                    'url'  		=> $url,
                ];
            }
        }

        $parentData = [
            'parents' => $pagesData,
            'title'   => $title,
        ];

        return $parentData;
    }

    /**
     * This method use for display address breadCrumb.
     *
     * @param string $addressType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function addresses($addressType)
    {
        if (!empty($addressType)) {
            $collectionBreadcrumb = [
                'parents' => [],
                'title' => __('Addresses'),
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for display order breadCrumb.
     *
     * @param string $orderType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function orders($orderType)
    {
        if (!empty($orderType)) {
            $pageTypeData[] = [
                'name' => __('Shopping Cart'),
                'url' => route('cart.view'),
            ];

            $collectionBreadcrumb = [
                'parents' => [],
                'title' => __('My Orders'),
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for display order-detail breadCrumb.
     *
     * @param string $orderType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function orderDetail($orderType)
    {
        if (!empty($orderType)) {
            $pageTypeData[] = [
                'name' => __('My Order'),
                'url' => route('cart.order.list'),
            ];

            $collectionBreadcrumb = [
                'parents' => $pageTypeData,
                'title' => __('Details'),
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for display cart order breadCrumb.
     *
     * @param string $cartOrderType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function cartOrders($cartOrderType)
    {
        if (!empty($cartOrderType)) {
            $pageTypeData[] = [
                'name' => __('Shopping Cart'),
                'url' => route('cart.view'),
            ];

            $collectionBreadcrumb = [
                'parents' => $pageTypeData,
                'title' => __('Order Summary'),
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for display shopping cart breadCrumb.
     *
     * @param string $shoppingCartType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function shoppingCart($shoppingCartType)
    {
        if (!empty($shoppingCartType)) {
            $collectionBreadcrumb = [
                'parents' => [],
                'title' => __('Shopping Cart'),
            ];

            return $collectionBreadcrumb;
        }

        return;
    }

    /**
     * This method use for display users related breadCrumb.
     *
     * @param string $profileType
     * 
     * @return array
     *---------------------------------------------------------------- */
    private function users($userType)
    {
        $title = '';

        if (!empty($userType)) {
            switch (true) {
                case $userType == 'profile':
                    $title = __('Profile');
                break;

                case $userType == 'profileEdit':
                    $title = __('Edit');
                    $parents[] = [
                        'name' => __('Profile'),
                        'url' => route('user.profile'),
                    ];

                break;

                case $userType == 'change-password':
                    $title = __('Change Password');
                break;

                case $userType == 'change-email':
                    $title = __('Change Email');
                break;

                case $userType == 'login':
                    $title = __('Login');
                break;

                case $userType == 'register':
                    $title = __('Register');
                break;

                case $userType == 'forgot-password':
                    $title = __('Forgot Password');
                break;

                case $userType == 'contact':
                    $title = __('Contact');
                break;

                case $userType == 'privacyPolicy':
                    $title = __('Privacy Policy');
                break;

                case $userType == 'termsAndCondition':
                    $title = __('Terms & Condition');
                break;

                case $userType == 'resend-activation-email':
                    $title = __('Resend Activation Email');
                break;

                case $userType == 'reset-password':
                    $title = __('Reset Password');
                break;

                case $userType == 'orders':
                    $title = __('Orders');
                break;

                case $userType == 'order-details':
                    $title = __('Details');
                    $parents[] = [
                        'name' => __('Orders'),
                        'url' => route('cart.order.list'),
                    ];

                break;

                case $userType == 'shopping-cart':
                    $title = __('Shopping-Cart');
                break;
            }

            return $collectionBreadcrumb = [
                'parents' => !empty($parents) ? $parents : [],
                'title' => $title,
            ];
        }

        return;
    }
}
