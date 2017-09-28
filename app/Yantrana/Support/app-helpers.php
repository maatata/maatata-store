<?php


use Carbon\Carbon;

/*
    |--------------------------------------------------------------------------
    | App Helpers
    |--------------------------------------------------------------------------
    |
    */

    /*
    * Customized GetText string
    *
    * @param string $string
    * @param array $replaceValues
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('__') and config('__tech.gettext_fallback')) {
        function __($string, $replaceValues = [])
        {
            $string = T_gettext($string);

            // Check if replaceValues exist
            if (!empty($replaceValues) and is_array($replaceValues)) {
                $string = strtr($string, $replaceValues);
            }

            return $string;
        }
    }

    /*
    * Get Locale settings
    *
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('localeConfig')) {
        function localeConfig($item = null)
        {
            if ($item) {
                return config('__tech.locale.'.$item);
            }

            return config('__tech.locale');
        }
    }

    if (!function_exists('sortOrderURL')) {
        function sortOrderURL($sortBy = null, $options = [])
        {
            $getSbid = (!empty($options['sbid'])) ? $options['sbid'] : '';

            $sortRequest = \Request::only(['sort_by', 'sort_order', 'search_term', 'sbid', 'min_price', 'max_price']);

            $sortOrder = $sortRequest['sort_order'] == 'asc' ? 'desc' : 'asc';

            if (isset($options['orderChange']) and $options['orderChange'] === false) {
                $sortOrder = $sortRequest['sort_order'];
            }

            $sortBy = empty($sortBy) ? $sortRequest['sort_by'] : $sortBy;

            $sortUrlPrefix = \Request::url().'?';

            if (!empty($sortRequest['search_term'])) {
                $sortUrlPrefix .= 'search_term='.$sortRequest['search_term'].'&';
            }

            if (!empty($sortRequest['sbid'])) {
                foreach ($sortRequest['sbid'] as $sbid) {
                    if ($sbid != $getSbid) {
                        $sortUrlPrefix .= 'sbid%5B%5D='.$sbid.'&';
                    }
                }
            }

            if (!empty($sortRequest['min_price'])) {
                $sortUrlPrefix .= 'min_price='.$sortRequest['min_price'].'&';
            }

            if (!empty($sortRequest['max_price'])) {
                $sortUrlPrefix .= 'max_price='.$sortRequest['max_price'].'&';
            }

            if (!empty($sortBy)) {
                $sortUrlPrefix .= "sort_by=$sortBy&sort_order=$sortOrder&";
            }

            return $sortUrlPrefix;
        }
    }

    /*
    * Removed Price filter
    *
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('removePriceFilter')) {
        function removePriceFilter()
        {
            $filterURL = sortOrderURL();

            $string = strchr($filterURL, 'min_price');

            return str_replace($string, ' ', $filterURL);
        }
    }

    if (!function_exists('createBreadcrumb')) {
        function createBreadcrumb($parentArray = [], $title = null)
        {
            $collectionBreadcrumb = [
                'parents' => $parentArray,
                'title' => $title,
            ];

            return $collectionBreadcrumb;
        }
    }

    /*
      * Get user ID
      * 
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('getUserID')) {
        function getUserID()
        {
            return Auth::id();
        }
    }

    /*
      * Get user ID
      * 
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('isActiveUser')) {
        function isActiveUser()
        {
            if (!empty(Auth::user())) {
                if (Auth::user()->status != 1) {
                    Session::flash('invalidUserMessage',
                        __('Invalid request please contact administrator.'));

                    Auth::logout();

                    return true;
                }
            }

            return false;
        }
    }

    /*
      * check userID
      * 
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('isLoggedInUserID')) {
        function isLoggedInUserID($fetchUserID)
        {
            $userID = Auth::user()->id;

            if ($fetchUserID == $userID) {
                return true;
            }

            return false;
        }
    }

    /*
      * Check if user logged in application
      * 
      * @return boolean
      *-------------------------------------------------------- */

    if (!function_exists('isLoggedIn')) {
        function isLoggedIn()
        {
            isActiveUser();

            return Auth::check();
        }
    }

    /*
      * Check if logged in user is admin
      * 
      * @return boolean
      *-------------------------------------------------------- */

    if (!function_exists('isAdmin')) {
        function isAdmin()
        {
            // Check if user looged in
            if (isLoggedIn()) {
                if (Auth::user()->role === 1) {
                    return true;
                }
            }

            return false;
        }
    }

    /*
      * Get user authentication 
      *
      * @return array
      *---------------------------------------------------------------- */

    if (!function_exists('getUserAuthInfo')) {
        function getUserAuthInfo($statusCode = null)
        {
            $userAuthInfo = [
                'authorized' => false,
                'reaction_code' => 9,
            ];

            if (Auth::check()) {
                $user = Auth::user();

                $role = (int) $user->role;

                $authenticationToken = md5(uniqid(true));

                $userAuthInfo = [
                    'authorization_token' => $authenticationToken,
                    'authorized' => true,
                    'reaction_code' => !empty($statusCode) ? $statusCode : 10,
                    'profile' => [
                        'full_name' => $user->fname.' '.$user->lname,
                        'email' => $user->email,
                    ],
                    'personnel' => $user->id,
                    'designation' => $role,
                ];
            }

            return $userAuthInfo;
        }
    }

    /*
    * Add activaity log entry
    *
    * @param string $activity
    * 
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('activityLog')) {
        function activityLog($activity)
        {
            App\Yantrana\Components\User\Models\ActivityLog::create([
                    'activity' => $activity,
                    'users_id' => getUserID(),
                ]);
        }
    }

     /*
    * Add order log entry
    *
    * @param string $order
    * 
    * @return void.
    *-------------------------------------------------------- */

    if (!function_exists('orderLog')) {
        function orderLog($order, $message = null)
        {
            $user = Auth::user();

            $orderUpdateBy = '';

            if ($user) {
                $orderUpdateBy = ' by '.$user->fname.' '.$user->lname;
            }

            if (is_int($order)) {
                $orderId = $order;
            } else {
                $orderId = $order['orders__id'];
                $message = $order['description'];
            }

            App\Yantrana\Components\ShoppingCart\Models\OrderLog::create([
                    'orders__id' => $orderId,
                    'description' => json_encode([
                                            'createdAt' => 'On '.formatDateTime(
                                                            Carbon::now()).$orderUpdateBy,
                                            'message' => $message,
                                        ]),
                    'users_id' => getUserID(),
                    'ip_address' => Request::ip(),
                ]);
        }
    }

    /*
    * Get formatted log data
    *
    * @param number $orderID
    * 
    * @return array.
    *-------------------------------------------------------- */

     if (!function_exists('getOrderLogFormattedData')) {
         function getOrderLogFormattedData($orderID)
         {
             $orderLogData = App\Yantrana\Components\ShoppingCart\Models\OrderLog::where('orders__id', $orderID)
                                ->orderBy('created_at', 'DESC')
                                ->select('description')
                                  ->get();

             $logDiscription = [];

             foreach ($orderLogData as $log) {

                // JSON decode of discription
                $logData = json_decode($log['description']);

                // push data into array
                $logDiscription [] = [
                    'created_at' => $logData->createdAt,
                    'description' => isset($logData->message) ? $logData->message : '',
                ];
             }

             return $logDiscription;
         }
     }

    /*
      * Generate angular app url based on route name & its segment name
      *
      * @param string $routeID
      * @param string $ngRouteID
      * @param array  $params
      * @param array  $ngParams
      * 
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('ngLink')) {
        function ngLink($routeID, $ngRouteID, $params = [], $ngParams = [])
        {
            if (!empty($params)) {
                $url = route($routeID, $params);
            } else {
                $url = route($routeID);
            }

            $url = $url.'#'.config('__ng-routes.'.$ngRouteID.'.url');

            if (!empty($ngParams)) {
                foreach ($ngParams as $ngParam => $ngParamValue) {
                    $url = str_replace($ngParam, $ngParamValue, $url);
                }
            }

            return $url;
        }
    }

    /*
      * Get Date Time Format from config file
      * 
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('formatDateTime')) {
        function formatDateTime($date)
        {
            return formatDate($date, config('__tech.day_date_time_format'));
        }
    }

    /*
      * Get formatted date.
      *
      * @param carbon object $date
      * @param string $format
      * 
      * @return date.
      *-------------------------------------------------------- */

    if (!function_exists('formatDate')) {
        function formatDate($date, $formate = 'jS F Y')
        {
            $date = accountTimezone($date);

            return $date->format($formate);
        }
    }

    /*
      * Convert date with setting time zone
      * 
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('accountTimezone')) {
        function accountTimezone($rawDate)
        {
            $carbonDate = Carbon::parse($rawDate);

            $accountTimezone = getStoreSettings('timezone');
            if (!__isEmpty($accountTimezone)) {
                $carbonDate->timezone = $accountTimezone;
            }

            return $carbonDate;
        }
    }

    /*
    |-------------------------------------------------------------------------
    | Detect Active Routes
    |--------------------------------------------------------------------------
    |
    | Compare given routes with current route and return output if they match.
    | Very useful for navigation, marking if the link is active.
    |
    **************************************************************************/

    if (!function_exists('isActiveRoute')) {
        function isActiveRoute($route, $output = 'active')
        {
            if (Route::currentRouteName() == $route) {
                return $output;
            }
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTitle')) {
        function getTitle($key, $configString)
        {
            if ($key == 0) {
                $key = 3;
            }

            $codes = Config::get($configString);

            return $codes[$key];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTypeTitle')) {
        function getTypeTitle($key)
        {
            $codes = Config('__tech.pages_types_with_system_link');

            return $codes[$key];
        }
    }

    /*
    * Get code getSysLinkId.
    *
    * @param string $string
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getSysLinkId')) {
        function getSysLinkId($string)
        {
            $codes = Config('__tech.system_links');

            return $codes[$string];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTextTitle')) {
        function getTextTitle($key, $configString)
        {
            $newKey = '';
            if (!empty($key)) {
                $newKey = $key;
            }

            $codes = Config($configString);

            return $codes[$newKey];
        }
    }

    /*
    * Get code title.
    *
    * @param number $key
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getTextMarkup')) {
        function getTextMarkup($markUpMessage, $data)
        {
            $markUpMessage = strtr($markUpMessage, $data);

            return $markUpMessage;
        }
    }

    /*
      * Get users media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUsersMediaPath')) {
        function getUsersMediaPath()
        {
            return public_path('media-storage/users/');
        }
    }

    /*
      * Get upload manager path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUploadManagerPath')) {
        function getUploadManagerPath()
        {
            return public_path('media-storage/upload-manager-assets/');
        }
    }

    /*
      * Get upload manager URL
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUploadManagerURL')) {
        function getUploadManagerURL()
        {
            return URL::to('media-storage/upload-manager-assets').'/';
        }
    }

    /*
      * Get logged in user media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLoggedInUserMediaPath')) {
        function getLoggedInUserMediaPath()
        {
            return getUsersMediaPath().'user-'.\getUserID();
        }
    }

    /*
      * Get logged in user temp media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLoggedInUserTempMediaPath')) {
        function getLoggedInUserTempMediaPath()
        {
            return getLoggedInUserMediaPath().'/temp/';
        }
    }

    /*
      * Get user temp image url
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUserTempImageURL')) {
        function getUserTempImageURL($tempImage, $userID)
        {
            $sourcePath = getUsersMediaPath().'/user-'.$userID.'/temp/'.$tempImage;

            if (File::exists($sourcePath)) {
                return getUsersMediaURL().'/user-'.$userID.'/temp/'.$tempImage;
            }

            return getNoThumbIconURL();
        }
    }

    /*
      * Get products media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductsMediaPath')) {
        function getProductsMediaPath()
        {
            return public_path('media-storage/products/');
        }
    }

    /*
      * Get users media path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getUsersMediaURL')) {
        function getUsersMediaURL()
        {
            return URL::to('media-storage/users');
        }
    }

    /*
      * Get products media storage url
      * 
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getProductsMediaURL')) {
        function getProductsMediaURL()
        {
            return URL::to('/media-storage/products/');
        }
    }

    /*
      * Get product media storage path
      *
      * @param number $productID
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductMediaPath')) {
        function getProductMediaPath($productID)
        {
            return getProductsMediaPath().'product-'.$productID;
        }
    }

    /*
      * Get brand media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandMediaPath')) {
        function getBrandMediaPath()
        {
            return public_path('media-storage/brands/logo/');
        }
    }

    /*
      * Get brand media storage path
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandMediaUrl')) {
        function getBrandMediaUrl($brandID)
        {
            return getBrandMediaPath().'brand-'.$brandID;
        }
    }

    /*
      * Get brand logo directory
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandLogoDirectory')) {
        function getBrandLogoDirectory()
        {
            return URL::to('/media-storage/brands/logo/');
        }
    }

    /*
      * Get brand logo url
      *
      * @param integer $brandID
      * @param string $fileName
      * 
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getBrandLogoURL')) {
        function getBrandLogoURL($brandID, $fileName)
        {
            $logoMediaPath = getBrandMediaUrl($brandID).'/'.$fileName;

            // If logo file exist then return logo image url
            if (File::exists($logoMediaPath)) {
                return getBrandLogoDirectory().'/brand-'.$brandID.'/'.$fileName;
            } else {
                return noThumbImageURL();
            }
        }
    }

    /*
      * Get product media storage url
      *
      * @param number $productID
      * @
      * 
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getProductMediaURL')) {
        function getProductMediaURL($productID)
        {
            return getProductsMediaURL().'/product-'.$productID;
        }
    }

    /*
      * Get product image url
      *
      * @param number $productID
      * @param string $fileName
      * 
      * @return string url.
      *-------------------------------------------------------- */

    if (!function_exists('getProductImageURL')) {
        function getProductImageURL($productID, $fileName)
        {
            $imageMediaPath = getProductMediaPath($productID).'/'.$fileName;

            // If image file exist then return image url
            if (File::exists($imageMediaPath)) {
                return getProductMediaURL($productID).'/'.$fileName;
            } else {
                return noThumbImageURL();
            }
        }
    }

    /*
      * Get current date time
      * 
      * @return void
      *-------------------------------------------------------- */

    if (!function_exists('getCurrentDateTime')) {
        function getCurrentDateTime()
        {
            return new DateTime();
        }
    }

    /*
      * Get logo media storage path
      *
      * @return string path.
      *-------------------------------------------------------- */

    if (!function_exists('getLogoMediaPath')) {
        function getLogoMediaPath()
        {
            return 'media-storage/logo/';
        }
    }

    /*
    * Get status code.
    *
    * @param number $status
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('getSelectizeSource')) {
        function getSelectizeSource()
        {
            $statusCodes = Config('__tech');

            return $statusCodes;
        }
    }

    /*
      * Get status codes.
      *
      * @param string $configKey
      * 
      * @return array.
      *-------------------------------------------------------- */

    if (!function_exists('getSelectizeOptions')) {
        function getSelectizeOptions($configArray, $configKey)
        {
            if (empty($configKey)) {
                return [];
            }

            $newStatusCodes = [];
            $statusCodes = Config($configArray);
            $requestedCodes = Config($configKey);

            if (!empty($requestedCodes)) {
                foreach ($requestedCodes as $code) {
                    if (array_key_exists($code, $statusCodes)) {
                        $newStatusCodes[] = [
                            'value' => $code,
                            'text' => $statusCodes[ $code ],
                        ];
                    }
                }
            }

            return $newStatusCodes;
        }
    }

    /*
      * Get product option required title.
      *
      * @param boolean $requiredValue
      * 
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('getProductOptionRequiredTitle')) {
        function getProductOptionRequiredTitle($requiredValue)
        {
            $title = ($requiredValue === true)
                    ? __('Yes')
                    : __('No');

            return $title;
        }
    }

    /*
    * return formated price
    *
    * @param float $amount
    *
    * @return float
    *---------------------------------------------------------------- */

    if (!function_exists('priceFormat')) {
        function priceFormat($amount = null, $currency = false)
        {
            $currencySymbol = getStoreSettings('currency_symbol');

            $formatedCurrency = html_entity_decode($currencySymbol).number_format((float) $amount, 2).($currency == true ? ' '.getStoreSettings('currency') : '');

            return $formatedCurrency;
        }
    }

    /*
    * get setting items 
    *
    * @param string $name 
    *
    * @return void
    *---------------------------------------------------------------- */

    if (!function_exists('getStoreSettings')) {
        function getStoreSettings($name, $details = false)
        {
            $settings = Cache::rememberForever('cache.storeSetting.all', function () {

                $getSettings = \App\Yantrana\Components\Store\Models\Setting::all();

                $storeSettings = [];
                $checkoutMethods = [
                    'use_paypal' => 1,
                    'payment_check' => 2,
                    'payment_bank' => 3,
                    'payment_cod' => 4,
                    'payment_other' => 5,
                ];

                $storeSettings['valid_checkout_methods'] = [];

                foreach ($getSettings as $setting) {
                    $storeSettings[$setting->name] = $setting->value;

                    if ($setting->name == 'logo_image') {
                        $storeSettings['logo_image'] = asset('media-storage/logo/'.$setting->value);
                        $storeSettings['logo_image_url'] = asset('media-storage/logo/'.$setting->value).'?logover='.@filemtime(public_path('media-storage/logo/'.$setting->value));
                    }

                    // if the item set then make it available
                    if (array_key_exists($setting->name, $checkoutMethods)) {
                        if (((int) $setting->value) === 1) {
                            array_push($storeSettings['valid_checkout_methods'], $checkoutMethods[$setting->name]);
                        }
                    }
                }

                unset($checkoutMethods, $getSettings);

                return $storeSettings;

            });

            if (array_key_exists($name, $settings)) {
                return $settings[$name];
            }

            return;
        }
    }

    /*
      * Get store auth information
      * 
      * @return array
      *-------------------------------------------------------- */

    if (!function_exists('error404')) {
        function error404()
        {
            return abort(404);
        }
    }

    /*
    * Get categories product route.
    *
    * @param number $cateID,
    *
    * @param string $categoryName
    * 
    * @return string.
    *-------------------------------------------------------- */

    if (!function_exists('categoriesProductRoute')) {
        function categoriesProductRoute($cateID, $categoryName = null)
        {
            if (!empty($categoryName)) {
                $categoryName = str_slug($categoryName);
            }

            return route('products_by_category', [$cateID, $categoryName]);
        }
    }

    /*
    * Get all product route.
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsRoute')) {
        function productsRoute()
        {
            return route('products');
        }
    }

    /*
    * Get featured product route.
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsFeatureRoute')) {
        function productsFeatureRoute()
        {
            return route('products.featured');
        }
    }

    /*
    * Get product details route.
    *
    * @param number $productID,
    *
    * @param string $productName
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsDetailsRoute')) {
        function productsDetailsRoute($productID, $productName, $categoryID = null)
        {
            return route('product.details', [
                        'productID' => $productID,
                        'productName?' => $productName,
                        'categoryID?' => $categoryID,
                        ]);
        }
    }

    /*
    * Get product search route.
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('productsSearchRoute')) {
        function productsSearchRoute()
        {
            return route('product.search');
        }
    }

    /*
    * Get page detail route.
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('pageDetailsRoute')) {
        function pageDetailsRoute($pageID, $pageName = null)
        {
            if (!empty($pageName)) {
                $pageName = str_slug($pageName);
            }

            return route('display.page.details', [$pageID, $pageName]);
        }
    }

    /*
    * Get page detail route.
    * 
    * @return string.
    *-------------------------------------------------------- */
    if (!function_exists('loginRoute')) {
        function loginRoute()
        {
            return route('user.login');
        }
    }

    /*
    * find all active parents recursively 
    * and also active parents
    *
    * @param (object) $itemCollection.  
    * @param (int) $itemID.  
    * @param (array) $activeItemsContainer.  
    *                 
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findActiveParents')) {
        function findActiveParents($itemCollection, $itemID = null, $activeItemsContainer = [])
        {
            foreach ($itemCollection as $item) {
                if ($item->id === (int) $itemID) {
                    if ($item->status === 1) {
                        $activeItemsContainer[] = $item->id;

                        if ($item->parent_id) {
                            $activeItemsContainer = findActiveParents(
                                                            $itemCollection,
                                                            $item->parent_id,
                                                            $activeItemsContainer
                                                        );
                        }
                    } else {
                        $activeItemsContainer = [];

                        break;
                    }
                }
            }

            return array_values(array_unique(array_flatten($activeItemsContainer)));
        }
    }

    /*
    * find all childrens recursively 
    * 
    * @param (object) $itemCollection.  
    * @param (int) $itemID.  
    * @param (array) $activeItemsContainer.  
    *                 
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findChilds')) {
        function findChilds($itemCollection, $itemID = null, $activeItemsContainer = [])
        {
            $itemID = (int) $itemID;

            foreach ($itemCollection as $item) {
                if (($item->id === $itemID)
                    and in_array($itemID, $activeItemsContainer) !== true) {
                    $activeItemsContainer[] = $itemID;
                }

                if ($item->parent_id == (int) $itemID) {
                    $activeItemsContainer[] = $item->id;
                    $activeItemsContainer[] = findChilds($itemCollection, $item->id, $activeItemsContainer);
                }
            }

            return array_values(array_unique(array_flatten($activeItemsContainer)));
        }
    }

    /*
    * find all active childrens recursively 
    * 
    * @param (object) $itemCollection.  
    * @param (int) $itemID.  
    * @param (array) $activeItemsContainer.  
    *                 
    * @return integer
    *------------------------------------------------------------------------ */
    if (!function_exists('findActiveChilds')) {
        function findActiveChilds($itemCollection, $itemID = null, $activeItemsContainer = [])
        {
            $itemID = (int) $itemID;

            foreach ($itemCollection as $item) {
                if (($item->id === $itemID)
                    and $item->status === 1
                    and in_array($itemID, $activeItemsContainer) !== true) {
                    $activeItemsContainer[] = $itemID;
                }

                if ($item->parent_id == (int) $itemID && $item->status == 1) {
                    $activeItemsContainer[] = $item->id;
                    $activeItemsContainer[] = findActiveChilds($itemCollection, $item->id, $activeItemsContainer);
                }
            }

            return array_values(array_unique(array_flatten($activeItemsContainer)));
        }
    }

    if (!function_exists('getProductCategory')) {
        function getProductCategory($categories, $carProductID)
        {
            $productCategories = \App\Yantrana\Components\Product\
                                    Models\ProductCategory::where(
                                        'products_id',
                                        $carProductID
                                    )->select(
                                        'products_id',
                                        'categories_id'
                                    )->get();

            foreach ($productCategories as $productCategory) {
                $categoriesID = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesID);
            }

            $checkParentStatus = false;  // invalid // deactive

            if (!empty($findActiveParents)) {
                foreach ($findActiveParents as $cateParents) {
                    if (!empty($cateParents)) {
                        $checkParentStatus = true; // valid  // active
                    }
                }
            }

            return $checkParentStatus;
        }
    }

    /*
      * find active parents 
      * return self ID  & parent_id
      * @param (object) $itemCollection.  
      * @param (int) $itemID.  
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('findActiveParentsNChilds')) {
        function findActiveParentsNChilds($itemCollection, $itemID = null)
        {
            $selfItem = $itemCollection->where('id', (int) $itemID)->first();

            return [
                'self' => $itemID,
                'parent' => isset($selfItem->parent_id),
                'status' => isset($selfItem->status),
                'parents' => findActiveParents($itemCollection, (int) $itemID) ?: false,
                'childrens' => findActiveChilds($itemCollection, (int) $itemID) ?: false,
            ];
        }
    }

    /*
      * formate fancytree source
      * @param (object) $nodesCollection.   
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('fancytreeSource')) {
        function fancytreeSource($nodesCollection)
        {
            $nodes = [];

            foreach ($nodesCollection as $node) {
                $nodes[] = [
                    'title' => isset($node->name) ? $node->name : $node->title,
                    'key' => $node->id,
                    'parent_id' => $node->parent_id,
                ];
            }

            return $nodes;
        }
    }

    /*
      * generate order id with date formate 
      *
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('generateOrderID')) {
        function generateOrderID()
        {
            $uid = uniqid();

            if (is_int($uid)) {
                $uid = generateOrderID();
            }

            return $uid;
        }
    }

    /*
      * get config
      * @param string $configPth
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('formatedConfig')) {
        function formatedConfig($configPath)
        {
            $result = [];

            foreach ($configPath as $key => $status) {
                $result[] = [
                    'value' => strtolower($key),
                    'text' => $status,
                ];
            }

            return $result;
        }
    }

    /*
      * get config
      * @param string $configPth
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('totalPrice')) {
        function totalPrice($price)
        {
            return array_sum($price);
        }
    }

    /*
      * get Refined cart items 
      * 
      * @param array $getCartItems
      * @param object $products
      * 
      * @return array
      *---------------------------------------------------------------- */
    if (!function_exists('getRefinedCart')) {
        function getRefinedCart($getCartItems, $products)
        {
            $cartTotal = 0;
            $isCartReady = true;
            $itemIsInvalid = false;// is valid product
            $cartTotalAmount = [];
            $unreadyMsg = null;

            foreach ($getCartItems as $cartItemKey => $cartItemKeyValue) {

                // verify this product with cart item
                $verifyProduct = ShoppingCart::verifyCartProduct($getCartItems[$cartItemKey], $products);

                $getCartItems[$cartItemKey]['ERROR'] = null;
                $getCartItems[$cartItemKey]['ERROR_MSG'] = null;

                $productVerificationResult = $verifyProduct['result'];

                if ($productVerificationResult !== true) {
                    $errorMsg = null;

                    if (($productVerificationResult === 'ERR_PRODUCT_OUT_OF_STOCK')
                        and getStoreSettings('show_out_of_stock')) {
                        $errorMsg = __('Out of Stock');
                    } else {
                        $errorMsg = __('Product currently not available');
                    }

                    $getCartItems[$cartItemKey]['ERROR_MSG'] = $errorMsg;
                    $getCartItems[$cartItemKey]['ERROR'] = $productVerificationResult;

                    $unreadyMsg = __('Highlighted item in the cart seems currently not available/changed, please remove it.');
                    $isCartReady = false;
                    $itemIsInvalid = true;
                }

                $addonPrice = [];

                $cartProductPrice = $cartItemKeyValue['price'];

                if (!__isEmpty($getCartItems[$cartItemKey]['options'])) {
                    foreach ($getCartItems[$cartItemKey]['options'] as $key => $option) {
                        $getCartItems[$cartItemKey]['options'][$key]['formated_addon_price']
                                    = priceFormat($option['addonPrice']);

                        $addonPrice[] = $option['addonPrice'];
                    }
                }

                $getCartItems[$cartItemKey]['formated_price'] = priceFormat($cartProductPrice);
                $getCartItems[$cartItemKey]['thumbnail_url'] = getProductImageURL($cartItemKeyValue['id'], $cartItemKeyValue['thumbnail']);
                $getCartItems[$cartItemKey]['productDetailURL'] = route('product.details', ['productID' => $cartItemKeyValue['id'], 'productName' => str_slug($cartItemKeyValue['name'])]);

                $calculatedAddonPrice = 0;

                if (!__isEmpty($addonPrice)) {
                    $calculatedAddonPrice = totalPrice($addonPrice); // array sum
                }

                $subtotal = $cartProductPrice + $calculatedAddonPrice;

                $getCartItems[$cartItemKey]['new_price'] = priceFormat($subtotal);
                $total = $subtotal * $cartItemKeyValue['qty'];
                $getCartItems[$cartItemKey]['new_subTotal'] = priceFormat($total);
                $cartTotalAmount[] = $total;
            }

            if (empty($cartTotalAmount)) {
                $isCartReady = false;
                $unreadyMsg = __('Cart is empty');
            }

            return [
                'productData' => $getCartItems,
                'cartReady' => $isCartReady,
                'itemIsInvalid' => $itemIsInvalid,
                'notReadyReason' => $unreadyMsg,
                'totalPriceItems' => $cartTotalAmount,
                'cartPriceTotal' => totalPrice($cartTotalAmount),
            ];
        }
    }

    /*
      * get all categories
      * 
      * @param string $code
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getAllCategories')) {
        function getAllCategories()
        {
            return Cache::rememberForever('cache.categories.all', function () {
                return \App\Yantrana\Components\Category\Models\Category::all();
            });
        }
    }

    /*
      * Format amount
      * 
      * @param string $code
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('numberFormatAmount')) {
        function numberFormatAmount($amount)
        {
            return (double) $amount;
        }
    }

    /*
      * get youtube url
      * 
      * @param string $code
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getYoutubeUrl')) {
        function getYoutubeUrl($code)
        {
            return 'http://www.youtube.com/embed/'.$code;
        }
    }

    /*
      * get all other code
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getAocCode')) {
        function getAocCode()
        {
            return Config('__tech.aoc');
        }
    }

    /*
      * get set currency
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCurrency')) {
        function getCurrency()
        {
            return html_entity_decode(getStoreSettings('currency'));
        }
    }

    /*
      * get set currency Symbol
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCurrencySymbol')) {
        function getCurrencySymbol()
        {
            return html_entity_decode(getStoreSettings('currency_symbol'));
        }
    }

    /*
      * get countries list
      * 
      * @param string $selectedCountries
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getCountries')) {
        function getCountries()
        {
            $countriesCollection = [];

            foreach (config('__tech.countries') as $key => $country) {
                $countriesCollection[] = [
                        'value' => $key,
                        'text' => $country,
                    ];
            }

            return [
                'countries' => $countriesCollection,
                'currencySymbol' => getCurrencySymbol(),
                'currency' => getCurrency(),
            ];
        }
    }

    /*
    * Set new orders count in session
    *
    * @param int $count
    *
    * @return void
    *-------------------------------------------------------- */

    if (!function_exists('setInSessionNewOrderPlacedCount')) {
        function setInSessionNewOrderPlacedCount($count)
        {
            if (isAdmin()) {
                $order = [
                    'orderData' => [
                        'newOrderPlacedCount' => $count,
                    ],
                ];

                Session::set('additional', $order);
            }
        }
    }

    /*
      * get timezone list
      * 
      * @param string $timezone
      * 
      * @return string
      *---------------------------------------------------------------- */
    if (!function_exists('getTimeZone')) {
        function getTimeZone()
        {
            $timezoneCollection = [];
            $timezoneList = timezone_identifiers_list();
            foreach ($timezoneList as $timezone) {
                $timezoneCollection[] = [
                        'value' => $timezone,
                        'text' => $timezone,
                    ];
            }

            return $timezoneCollection;
        }
    }

    /*
      * Get demo mode for defined admin
      * 
      * @return boolean.
      *-------------------------------------------------------- */

    if (!function_exists('isDemoForAdmin')) {
        function isDemoForAdmin()
        {
            if (isDemo()) {
                return true;
            }

            return false;
        }
    }

    /*
      * Get formatted date time from passed raw date using timezone
      * 
      * @param string $rawDateTime
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('formatStoreDateTime')) {
        function formatStoreDateTime($rawDateTime, $format = 'l jS F Y g:i:s a')
        {
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $rawDateTime);

            return $carbonDate->format($format);
        }
    }

    /*
      * Get formatted date from passed raw date using timezone
      * 
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('formatStoreDate')) {
        function formatStoreDate($rawDate, $format = 'jS F Y')
        {
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $rawDate);

            return $carbonDate->format($format);
        }
    }

    /*
      * Get formatted date time from passed current date using timezone
      * 
      * @param string $rawDate
      *
      * @return date
      *-------------------------------------------------------- */

    if (!function_exists('currentDateTime')) {
        function currentDateTime()
        {
            return Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    /*
      * Get demo mode for Demo of site
      * 
      * @return boolean.
      *-------------------------------------------------------- */

    if (!function_exists('isDemo')) {
        function isDemo()
        {
            return (env('IS_DEMO_MODE', false)) ? true : false;
        }
    }

    /*
      * Get currency symbol with price.
      *
      * @param boolean $amount
      * 
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('orderPriceFormat')) {
        function orderPriceFormat($amount = null, $currencyCode)
        {
            $currencies = config('__tech.currencies.details');

            $currencySymbol = '';

            foreach ($currencies as $key => $currency) {
                if ($key == $currencyCode) {
                    $currencySymbol = $currency['symbol'];
                }
            }

            return $currencySymbol.''.number_format((float) $amount, 2);
        }
    }

    /*
      * return formated keywords for meta data
      *
      * @param array $array
      * 
      * @return string.
      *-------------------------------------------------------- */

    if (!function_exists('getKeywords')) {
        function getKeywords($array)
        {
            if (!__isEmpty($array)) {
                foreach ($array as $value) {
                    echo array_key_exists('name', $value) ? $value['name'] : $value['title'];

                    if (end($array) !== $value) {
                        echo ', ';
                    }
                }
            }
        }
    }

    /*
      * matching current route
      *
      * @param string $routeName
      * 
      * @return bool.
      *-------------------------------------------------------- */

    if (!function_exists('isCurrentRoute')) {
        function isCurrentRoute($routeName)
        {
            $route = \Request::route()->getName();

            if ($route === $routeName) {
                return true;
            }

            return false;
        }
    }

    /*
      * Format amount in float
      *
      * @param number $amount
      * 
      * @return number.
      *-------------------------------------------------------- */

    if (!function_exists('formatAmount')) {
        function formatAmount($amount)
        {
            return round($amount, 2);
        }
    }

    /*
      * Get no thumb image URL
      * 
      * @return string
      *-------------------------------------------------------- */

    if (!function_exists('noThumbImageURL')) {
        function noThumbImageURL()
        {
            return url('/dist/imgs/no_thumb_image.jpg');
        }
    }
