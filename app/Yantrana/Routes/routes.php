<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/home-igniter', [
    'as' => 'public.app',
    'uses' => '__Igniter@index',
]);

Route::get('/change-language/{localeID}', [
    'as' => 'locale.change',
    'uses' => 'Home\Controllers\HomeController@changeLocale',
]);

Route::get('/error-not-found', [
    'as' => 'error.public-not-found',
    'uses' => '__Igniter@errorNotFound',
]);

// fetch products
Route::get('/products', [
    'as' => 'public.app',
    'uses' => 'ProductController@all',
]);

Route::get('/', [
    'as' => 'home.page',
    'uses' => 'Home\Controllers\HomeController@home',
]);

Route::get('/home', [
    'as' => 'home',
    'uses' => '__Igniter@index',
]);

// captcha generate url 
Route::get('/generate-captcha', [
    'as' => 'security.captcha',
    function () {
      return Captcha::create('flat');
    },
]);

// get all application template using this route
Route::get('/get-template/{viewName}', [
    'as' => 'template.get',
    'uses' => '__Igniter@getTemplate',
]);

// get all application template using this route
Route::get('/email-view/{viewName}', [
    'as' => 'template.email',
    function ($viewName) {
        //return __loadView($viewName);
        return view('emails.index', [
                'emailsTemplate' => $viewName,
            ]);
    },
]);

/* 
  Product Components Public Section Related Routes
  ------------------------------------------------------------------ */

Route::group([
        'namespace' => 'Product\Controllers',
    ], function () {
    // fetch products
    Route::get('/products', [
        'as' => 'products',
        'uses' => 'ProductController@all',
    ]);

    // fetch products
    Route::get('/products/featured', [
        'as' => 'products.featured',
        'uses' => 'ProductController@all',
    ]);

    // fetch caregory products
    Route::get('/products/{categoryID}/{categoryName?}', [
        'as' => 'products_by_category',
        'uses' => 'ProductController@all',
    ])->where('categoryID', '[0-9]+');

    // Search Products
    Route::get('/products/search', [
        'as' => 'product.search',
        'uses' => 'ProductController@search',
    ]);

    // filter supported data 
    Route::get('/product/filter/{search_term?}', [
        'as' => 'product.filter',
        'uses' => 'ProductController@filterAll',
    ]);

    // brand related filter dilaog
    Route::get('/product/filter/brand/{brandID}', [
        'as' => 'product.filter.brand',
        'uses' => 'ProductController@filterBrandRelatedProduct',
    ]);

    // get quick view support data 
    Route::get('/product/{productID}/quick-view-dialog/{categoryID?}', [
        'as' => 'product.quick.view.details.support_data',
        'uses' => 'ProductController@quickViewDetailsSupportData',
    ])->where('productID', '[0-9]+');

    // get product details
    Route::get('/product/{productID}/details-support-data', [
        'as' => 'product.details.support_data',
        'uses' => 'ProductController@detailsSupportData',
    ])->where('productID', '[0-9]+');

    Route::get('/product-details/{productID}/{productName?}/{categoryID?}', [
        'as' => 'product.details',
        'uses' => 'ProductController@details',
    ])->where('productID', '[0-9]+');

    Route::get('/product/{brandID}/{brandName?}/brand', [
        'as' => 'product.related.by.brand',
        'uses' => 'ProductController@brandRelatedProducts',
    ])->where('brandID', '[0-9]+');

});

Route::group([
        'namespace' => 'Brand\Controllers',
    ], function () {
    Route::get('/brands', [
        'as' => 'fetch.brands',
        'uses' => 'BrandController@fetchActiveRecord',
    ]);
});

/*
  Pages Components Public Section Related Routes
  ----------------------------------------------------------------------- */

Route::group([
        'namespace' => 'Pages\Controllers',
        'prefix' => 'page',
    ], function () {
    // get details of page
    Route::get('{pageID}/{pageName?}', [
        'as' => 'display.page.details',
        'uses' => 'PagesController@displayPageDetails',
    ])->where('pageID', '[0-9]+');

});

/*
  ShoppingCart Components Public Section Related Routes
  ----------------------------------------------------------------------- */

Route::group([
    'namespace' => 'ShoppingCart\Controllers',
    'prefix' => 'shopping-cart', ], function () {
    // cart view
    Route::get('', [
        'as' => 'cart.view',
        'uses' => 'ShoppingCartController@cartView',
    ]);

    // add new item in cart
    Route::post('/{productID}/add-item', [
        'as' => 'cart.add.item',
        'uses' => 'ShoppingCartController@addItem',
    ])->where('productID', '[0-9]+');

    // get cart
    Route::get('/getData', [
        'as' => 'cart.get.data',
        'uses' => 'ShoppingCartController@getCartDetails',
    ]);

    // update cart quantity
    Route::post('/qty-update/{itemID}', [
        'as' => 'cart.update.qty',
        'uses' => 'ShoppingCartController@updateItemQty',
    ]);

    // update cart quantity
    Route::post('/check-product-cart/{productID}', [
        'as' => 'cart.product.qty.update',
        'uses' => 'ShoppingCartController@checkProductCart',
    ])->where('productID', '[0-9]+');

    // remove item from cart
    Route::post('/remove-item/{itemID}', [
        'as' => 'cart.remove.item',
        'uses' => 'ShoppingCartController@removeItem',
    ]);

    // remove item from cart
    Route::post('/remove-invalid-item', [
        'as' => 'cart.remove.invalid.item',
        'uses' => 'ShoppingCartController@removeInvalidItems',
    ]);

    // remove  all items form to the cart
    Route::post('/remove-item', [
        'as' => 'cart.remove.all.items',
        'uses' => 'ShoppingCartController@removeAllItems',
    ]);

    // get cart btn string
    Route::get('/cart-string', [
        'as' => 'cart.update.cart.string',
        'uses' => 'ShoppingCartController@updateCartBtnString',
    ]);

});

/*
  UploadManager Components Related Routes
  ----------------------------------------------------------------------- */

Route::group([
        'namespace' => 'UploadManager\Controllers',
        'prefix' => 'upload-manager',
    ], function () {
    // files 
    Route::get('/files', [
        'as' => 'upload_manager.files',
        'uses' => 'UploadManagerController@files',
    ]);

    // upload 
    Route::post('/upload', [
        'as' => 'upload_manager.upload',
        'uses' => 'UploadManagerController@upload',
    ]);

    // delete 
    Route::post('/{fileName}/delete', [
        'as' => 'upload_manager.delete',
        'uses' => 'UploadManagerController@delete',
    ]);

});

    /*
      User Components Public Section Related Routes
      ----------------------------------------------------------------------- */
Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {

    // contact form
    Route::get('/contact', [
        'as' => 'get.user.contact',
        'uses' => 'UserController@contact',
    ]);

    // process contact form
    Route::post('/post-contact', [
        'as' => 'user.contact.process',
        'uses' => 'UserController@contactProcess',
    ]);

    // privacy policy
    Route::get('/privacy-policy', [
        'as' => 'privacy.policy',
        'uses' => 'UserController@privacyPolicy',
    ]);

    // terms & conditions
    Route::get('/terms-conditions', [
        'as' => 'terms.conditions',
        'uses' => 'UserController@termsAndConditions',
    ]);
});

/*
  Guest Auth Routes
  -------------------------------------------------------------------------- */

Route::group(['middleware' => 'guest'], function () {
    /*
      User Components Public Section Related Routes
      ----------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {
        // login
        Route::get('/login', [
            'as' => 'user.login',
            'uses' => 'UserController@login',
        ]);

        // login process
        Route::post('/login', [
            'as' => 'user.login.process',
            'uses' => 'UserController@loginProcess',
        ]);

        // register
        Route::get('/register', [
            'as' => 'user.register',
            'uses' => 'UserController@register',
        ]);

        // register process
        Route::post('/register', [
            'as' => 'user.register.process',
            'uses' => 'UserController@registerProcess',
        ]);

        // register success
        Route::get('/register/success', [
            'as' => 'user.register.success',
            'uses' => 'UserController@registerSuccess',
        ]);

        // account activation
        Route::get('/{userID}/{activationKey}/account-activation', [
            'as' => 'user.account.activation',
            'uses' => 'UserController@accountActivation',
        ])->where('userID', '[0-9]+');

        // login attempts
        Route::get('/login-attempts', [
            'as' => 'user.login.attempts',
            'uses' => 'UserController@loginAttempts',
        ]);

        // forgot password
        Route::get('/forgot-password', [
            'as' => 'user.forgot_password',
            'uses' => 'UserController@forgotPassword',
        ]);

        // forgot password
        Route::post('/forgot-password', [
            'as' => 'user.forgot_password.process',
            'uses' => 'UserController@forgotPasswordProcess',
        ]);

        // forgot password success
        Route::get('/forgot-password-success', [
            'as' => 'user.forgot_password.success',
            'uses' => 'UserController@forgotPasswordSuccess',
        ]);

        // reset password
        Route::get('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password',
            'uses' => 'UserController@restPassword',
        ]);

        // reset password process
        Route::post('/reset-password/{reminderToken}', [
            'as' => 'user.reset_password.process',
            'uses' => 'UserController@restPasswordProcess',
        ]);

        // resend activation email
        Route::get('/resend-activation-email', [
            'as' => 'user.resend.activation.email.fetch.view',
            'uses' => 'UserController@resendActivationEmail',
        ]);

        // resend activation email success
        Route::get('/resend-activation-email-success', [
            'as' => 'user.resend_activation_email.success',
            'uses' => 'UserController@resendActivationEmailSuccess',
        ]);

        /*new user activation process*/
        Route::post('/resend-activation-email', [
            'as' => 'user.resend.activation.email.proccess',
            'uses' => 'UserController@resendActivationEmailProccess',
        ]);

    });

});

/*
  After Authentication Accesiable Routes
  -------------------------------------------------------------------------- */

Route::group(['middleware' => 'auth'], function () {
    /*
      User Components Public Section Related Routes
      ----------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'User\Controllers',
            'prefix' => 'user',
        ], function () {
        // logout 
        Route::get('/logout', [
            'as' => 'user.logout',
            'uses' => 'UserController@logout',
        ]);

        // profile 
        Route::get('/profile', [
            'as' => 'user.profile',
            'uses' => 'UserController@profile',
        ]);

        // change password 
        Route::get('/change-password', [
            'as' => 'user.change_password',
            'uses' => 'UserController@changePassword',
        ]);

        // change password process
        Route::post('/change-password', [
            'as' => 'user.change_password.process',
            'uses' => 'UserController@changePasswordProcess',
        ]);

        // change email 
        Route::get('/change-email', [
            'as' => 'user.change_email',
            'uses' => 'UserController@changeEmail',
        ]);

        // change email process
        Route::post('/change-email', [
            'as' => 'user.change_email.process',
            'uses' => 'UserController@changeEmailProcess',
        ]);

        // new email activation 
        Route::get('/{userID}/{activationKey}/new-email-activation', [
            'as' => 'user.new_email.activation',
            'uses' => 'UserController@newEmailActivation',
        ]);

        // profile details
        Route::get('/profile-details', [
            'as' => 'user.profile.details',
            'uses' => 'UserController@profileDetails',
        ]);

        // profile update
        Route::get('/profile/edit', [
            'as' => 'user.profile.update',
            'uses' => 'UserController@updateProfile',
        ]);

        // profile update process
        Route::post('/profile/edit', [
            'as' => 'user.profile.update.process',
            'uses' => 'UserController@updateProfileProcess',
        ]);

        // process contact form
	    Route::get('/{userId}/get-user-info', [
	        'as'   => 'user.get.info',
	        'uses' => 'UserController@getInfo',
	    ]);

         // process contact form
	    Route::post('/process-contact', [
	        'as'   => 'user.contact.process',
	        'uses' => 'UserController@userContactProcess',
	    ]);

        // address list view
        Route::get('/addresses', [
            'as' => 'user.address.list',
            'uses' => 'AddressController@addressList',
        ]);

        // get list of address
        Route::get('/address/get', [
            'as' => 'user.get.addresses',
            'uses' => 'AddressController@getAddresses',
        ]);

        // address process
        Route::post('/address/add', [
            'as' => 'user.address.process',
            'uses' => 'AddressController@addProcess',
        ]);

        // edit user address
        Route::get('/address/fetch-edit-support-data/{addressID}', [
            'as' => 'user.fetch.address.support.data',
            'uses' => 'AddressController@editSupportData',
        ])->where('addressID', '[0-9]+');

        // update user address
        Route::post('/address/update/{addressID}', [
            'as' => 'user.address.update',
            'uses' => 'AddressController@update',
        ])->where('addressID', '[0-9]+');

        // delete address
        Route::post('/address/delete/{addressID}', [
            'as' => 'user.address.delete',
            'uses' => 'AddressController@delete',
        ])->where('addressID', '[0-9]+');

         // get list of address for order summary page
        Route::get('/fetch-addresses', [
            'as' => 'get.addresses.for.order',
            'uses' => 'AddressController@getAddressForOrder',
        ]);

        // make address primary
        Route::post('/address/{addressID}/primary', [
            'as' => 'user.get primary.address',
            'uses' => 'AddressController@makePrimaryAddress',
        ])->where('addressID', '[0-9]+');

    });

    Route::group([
            'namespace' => 'ShoppingCart\Controllers',
        ], function () {

        Route::group(['prefix' => 'order-process',
        ], function () {

            Route::get('', [
                'as' => 'order.summary.view',
                'uses' => 'OrderController@displayOrderSummary',
            ]);

            // get order details
            Route::get('/details/{addressID}/{addressID1}/{couponCode}', [
                'as' => 'order.summary.details',
                'uses' => 'OrderController@cartOrderDetails',
            ]);

            // coupon apply
            Route::post('/apply-coupon', [
                'as' => 'order.coupon.apply',
                'uses' => 'OrderController@applyCouponProcess',
            ]);

            // order submit
            Route::post('/submit', [
                'as' => 'order.process',
                'uses' => 'OrderController@orderProcess',
            ]);

            Route::get('/paypal-checkout/{orderUID}', [
                'as' => 'order.paypal.checkout',
                'uses' => 'OrderController@preparePaypalOrder',
            ]);

        });

        /*
          Order Components Public Section Related Routes
        ----------------------------------------------------------------------- */

        Route::group(['prefix' => '/orders'], function () {

            // my order list view
            Route::get('', [
                'as' => 'cart.order.list',
                'uses' => 'OrderController@userOrdersList',
            ]);

             // get orders related to users
            Route::get('/get-list-with-status/{status}', [
                'as' => 'cart.get.orders.data',
                'uses' => 'OrderController@index',
            ])->where('status', '[0-9]+');

            // my order view detail page
            Route::get('/{orderUID}/details', [
                'as' => 'my_order.details',
                'uses' => 'OrderController@orderDetail',
            ]);

            // shipping address change in order
            Route::get('/address/{addressID}/details', [
                'as' => 'change_address.in.order',
                'uses' => 'OrderController@changeAddressInOrder',
            ]);

            // order log dialog
            Route::get('/get/order-log-details/{orderID}', [
                'as' => 'order.log.dialog',
                'uses' => 'OrderController@userLogDetails',
            ])->where('orderID', '[0-9]+');

            // invoice download for user
            Route::get('/{orderID}/invoice-download', [
                'as' => 'order.user.invoice.download',
                'uses' => 'OrderController@invoiceDownload',
            ]);

        });

    });

    /*
      Media Components Public Section Related Routes
      ----------------------------------------------------------------------- */

    Route::group([
            'namespace' => 'Media\Controllers',
            'prefix' => 'media',
        ], function () {
        // upload image media 
        Route::post('/upload-image', [
            'as' => 'media.upload.image',
            'uses' => 'MediaController@uploadImage',
        ]);

        // delete media file
        Route::post('/{fileName}/delete', [
            'as' => 'media.delete',
            'uses' => 'MediaController@delete',
        ]);

        // delete multiple media files
        Route::post('/multiple-delete', [
            'as' => 'media.delete.multiple',
            'uses' => 'MediaController@multipleDelete',
        ]);

        // upload image media 
        Route::get('/uploaded-images-files', [
            'as' => 'media.uploaded.images',
            'uses' => 'MediaController@imagesFiles',
        ]);

    });

    /*
      After Admin Authentication Accesiable Routes
      ---------------------------------------------------------------------- */

    Route::group([
            'prefix' => 'manage',
            'middleware' => 'auth.admin',
        ], function () {

        Route::get('/', [
            'as' => 'manage.app',
            'uses' => '__Igniter@manageIndex',
        ]);

        /*
          User Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'User\Controllers',
                'prefix' => 'user',
            ], function () {

            // fetch users list for datatable
            Route::get('/{status}/fetch-list', [
                'as' => 'manage.users.list',
                'uses' => 'UserController@index',
            ])->where('status', '[0-9]+');

            // delete user
            Route::post('/{userID}/delete', [
                'as' => 'manage.user.delete',
                'uses' => 'UserController@delete',
            ])->where('userID', '[0-9]+');

            // restore user
            Route::post('/{userID}/restore', [
                'as' => 'manage.user.restore',
                'uses' => 'UserController@restore',
            ])->where('userID', '[0-9]+');

            // change password by admin process
            Route::post('/{userID}/change-password', [
                'as' => 'manage.user.change_password.process',
                'uses' => 'UserController@changePasswordByAdmin',
            ])->where('userID', '[0-9]+');

            // fetch users details
            Route::get('/{userID}/fetch-details', [
                'as' => 'manage.users.get.detail',
                'uses' => 'UserController@getUserDetails',
            ])->where('status', '[0-9]+');

            // fetch users details
            Route::get('/{userID}/contact', [
                'as' => 'manage.users.contact',
                'uses' => 'UserController@contact',
            ])->where('status', '[0-9]+');

        });

        /*
          Category Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Category\Controllers',
                'prefix' => 'category',
            ], function () {
            // add new category
            Route::post('/add', [
                'as' => 'category.add',
                'uses' => 'ManageCategoryController@add',
            ]);

            // list
            Route::get('/fetch-list/{categoryID?}', [
                'as' => 'category.list',
                'uses' => 'ManageCategoryController@index',
            ]);

            // get details of category
            Route::get('/{catID}/get/details', [
                'as' => 'category.get.details',
                'uses' => 'ManageCategoryController@getDetails',
            ])->where('catID', '[0-9]+');

            // get details of category
            Route::get('/{catID}/get/support-data', [
                'as' => 'category.get.supportData',
                'uses' => 'ManageCategoryController@getSupportData',
            ])->where('catID', '[0-9]+');

            // post update details of category
            Route::post('/{catID}/edit', [
                'as' => 'category.update',
                'uses' => 'ManageCategoryController@update',
            ])->where('catID', '[0-9]+');

            // category update status
            Route::post('/{categoryID}/status', [
                'as' => 'category.update.status',
                'uses' => 'ManageCategoryController@updateStatus',
            ])->where('categoryID', '[0-9]+');

            // category delete
            Route::post('{categoryID}/delete', [
                'as' => 'category.delete',
                'uses' => 'ManageCategoryController@delete',
            ])->where('categoryID', '[0-9]+');

            // get fancytree sourse data
            Route::get('/fancytree-support-data', [
                'as' => 'category.fancytree.support-data',
                'uses' => 'ManageCategoryController@fancytreeSupportData',
            ]);

        });

        /*
          Pages Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Pages\Controllers',
                'prefix' => 'pages',
            ], function () {
            // Get page type
            Route::get('/get/pages-type', [
                'as' => 'manage.pages.get.Type',
                'uses' => 'ManagePagesController@getPagesType',
            ]);

            // get all pages list
            Route::get('/fetch-data/{parentPageID?}', [
                'as' => 'manage.pages.fetch.datatable.source',
                'uses' => 'ManagePagesController@index',
            ]);

            // get all pages list
            Route::get('/fetch-parent-page-data/{parentPageID}', [
                'as' => 'manage.pages.get.parent.page',
                'uses' => 'ManagePagesController@getParentPage',
            ]);

            // get all pages list
            Route::post('/update/list-order', [
                'as' => 'manage.page.update.list.order',
                'uses' => 'ManagePagesController@updateListOrder',
            ]);

            // add new page
            Route::post('/add', [
                'as' => 'manage.pages.add',
                'uses' => 'ManagePagesController@add',
            ]);

            // get details of page
            Route::get('/{pageID}/get/details', [
                'as' => 'manage.pages.get.details',
                'uses' => 'ManagePagesController@getDetails',
            ])->where('pageID', '[0-9]+');

            // update page data
            Route::post('/{pageID}/edit', [
                'as' => 'manage.pages.update',
                'uses' => 'ManagePagesController@update',
            ])->where('pageID', '[0-9]+');

            // delete page
            Route::post('/{pageID}/delete', [
                'as' => 'manage.pages.delete',
                'uses' => 'ManagePagesController@delete',
            ])->where('pageID', '[0-9]+');

            // get details of page
            Route::get('/details/{pageID}', [
                'as' => 'manage.display.page.details',
                'uses' => 'ManagePagesController@displayPageDetails',
            ])->where('pageID', '[0-9]+');

        });

        /*
          Product Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Product\Controllers',
                'prefix' => 'product',
            ], function () {
            // fetch product list as datatable source
            Route::get('/products', [
                'as' => 'manage.product.list',
                'uses' => 'ManageProductController@index',
            ]);

            // fetch category products as datatable source
            Route::get('/{categoryID}/category-products', [
                'as' => 'manage.category.product.list',
                'uses' => 'ManageProductController@index',
            ])->where('categoryID', '[0-9]+');

            // fetch brand products as datatable source
            Route::get('/{brandId}/brand-products', [
                'as' => 'manage.brand.product.list',
                'uses' => 'ManageProductController@getBrands',
            ])->where('brandId', '[0-9]+');

            Route::group(['prefix' => '/product'], function () {
                Route::get('/fetch-add-support-data', [
                    'as' => 'manage.product.add.supportdata',
                    'uses' => 'ManageProductController@addSupportData',
                ]);

                // add product
                Route::post('/add', [
                    'as' => 'manage.product.add',
                    'uses' => 'ManageProductController@add',
                ]);

                Route::get('/{productID}/detail', [
                'as' => 'manage.product.detailSupportData',
                'uses' => 'ManageProductController@getDetail',
                ])->where('productID', '[0-9]+');

                // delete product
                Route::post('/{productID}/delete', [
                    'as' => 'manage.product.delete',
                    'uses' => 'ManageProductController@delete',
                ])->where('productID', '[0-9]+');

                // details for edit
                Route::get('/{productID}/details', [
                    'as' => 'manage.product.details',
                    'uses' => 'ManageProductController@details',
                ])->where('productID', '[0-9]+');

                Route::get('/{productID}/fetch-product-name', [
                    'as' => 'manage.product.fetch.name',
                    'uses' => 'ManageProductController@getProductName',
                ])->where('productID', '[0-9]+');

                // product edit details support data
                Route::get('/{productID}/fetch-edit-details-support-data', [
                    'as' => 'manage.product.edit.details.supportdata',
                    'uses' => 'ManageProductController@editDetailsSupportData',
                ])->where('productID', '[0-9]+');

                // edit product
                Route::post('/{productID}/edit', [
                    'as' => 'manage.product.edit',
                    'uses' => 'ManageProductController@edit',
                ])->where('productID', '[0-9]+');

                // update status
                Route::post('/{productID}/update-status', [
                    'as' => 'manage.product.update_status',
                    'uses' => 'ManageProductController@updateStatus',
                ])->where('productID', '[0-9]+');

                Route::group(['prefix' => '{productID}/image'], function () {
                    // add image
                    Route::post('/add', [
                        'as' => 'manage.product.image.add',
                        'uses' => 'ManageProductController@addImage',
                    ])->where('productID', '[0-9]+');

                    // images list
                    Route::get('/fetch-list', [
                        'as' => 'manage.product.image.list',
                        'uses' => 'ManageProductController@imageList',
                    ])->where('productID', '[0-9]+');

                    // delete image
                    Route::post('/{imageID}/delete', [
                        'as' => 'manage.product.image.delete',
                        'uses' => 'ManageProductController@deleteImage',
                    ])->where('productID', '[0-9]+');

                    // edit image support data
                    Route::get('/{imageID}/fetch-supportdata', [
                        'as' => 'manage.product.image.edit.supportdata',
                        'uses' => 'ManageProductController@editImageSupportData',
                    ])->where('productID', '[0-9]+');

                    // edit image
                    Route::post('/{imageID}/edit', [
                        'as' => 'manage.product.image.edit',
                        'uses' => 'ManageProductController@editImage',
                    ])->where('productID', '[0-9]+');

                });

                Route::group(['prefix' => '{productID}/specifications'], function () {
                     // specification list
                    Route::get('/fetch-list', [
                        'as' => 'manage.product.specification.list',
                        'uses' => 'ManageProductController@specificationList',
                    ])->where('productID', '[0-9]+');

                    // add specification
                    Route::post('/add', [
                        'as' => 'manage.product.specification.add',
                        'uses' => 'ManageProductController@addSpecification',
                    ])->where('productID', '[0-9]+');

                    // edit specification
                    Route::get('{specificationID}/fetch-supportdata', [
                        'as' => 'manage.product.specification.edit',
                        'uses' => 'ManageProductController@editSpecification',
                        ])->where('productID', '[0-9]+');

                    // update specification
                    Route::post('{specificationID}/update', [
                        'as' => 'manage.product.specification.update',
                        'uses' => 'ManageProductController@updateSpecification',
                    ])->where('productID', '[0-9]+');

                    // delete specification
                    Route::post('/{specificationID}/delete', [
                        'as' => 'manage.product.specification.delete',
                        'uses' => 'ManageProductController@deleteSpecification',
                    ])->where('productID', '[0-9]+');

                    // specification all data
                    Route::get('/fetch-all', [
                        'as' => 'manage.product.specification.get.all',
                        'uses' => 'ManageProductController@specificationGetAllData',
                    ])->where('productID', '[0-9]+');

                });

                Route::group(['prefix' => '{productID}/option'], function () {
                    // add option
                    Route::post('/add', [
                        'as' => 'manage.product.option.add',
                        'uses' => 'ManageProductController@addOption',
                    ])->where('productID', '[0-9]+');

                    // option list
                    Route::get('/fetch-list', [
                        'as' => 'manage.product.option.list',
                        'uses' => 'ManageProductController@optionList',
                    ])->where('productID', '[0-9]+');

                    // delete option
                    Route::post('/{optionID}/delete', [
                        'as' => 'manage.product.option.delete',
                        'uses' => 'ManageProductController@deleteOption',
                    ])->where('productID', '[0-9]+');

                    // edit option support data
                    Route::get('/{optionID}/fetch-supportdata', [
                        'as' => 'manage.product.option.edit.supportdata',
                        'uses' => 'ManageProductController@editOptionSupportData',
                    ])->where('productID', '[0-9]+');

                    // edit option
                    Route::post('/{optionID}/edit', [
                        'as' => 'manage.product.option.edit',
                        'uses' => 'ManageProductController@editOption',
                    ])->where('productID', '[0-9]+');

                    Route::group(['prefix' => '{optionID}/value'], function () {

                        // add option values
                        Route::post('/add', [
                            'as' => 'manage.product.option.value.add',
                            'uses' => 'ManageProductController@addOptionValues',
                        ])->where('productID', '[0-9]+');

                        // get option value list
                        Route::get('/fetch-list', [
                            'as' => 'manage.product.option.value.list',
                            'uses' => 'ManageProductController@optionValues',
                        ])->where('productID', '[0-9]+');

                        // update option values
                        Route::post('/multiple-edit', [
                            'as' => 'manage.product.option.value.edit',
                            'uses' => 'ManageProductController@editOptionValues',
                        ])->where('productID', '[0-9]+');

                        // delete option value delete
                        Route::post('/{optionValueID}/delete', [
                            'as' => 'manage.product.option.value.delete',
                            'uses' => 'ManageProductController@deleteOptionValue',
                        ])->where('productID', '[0-9]+');

                    });

                });

            });

        });

        /*
          Store Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Store\Controllers',
                'prefix' => 'store',
            ], function () {

            Route::get('/{formType}/edit-store-settings-support-data', [
                'as' => 'store.settings.edit.supportdata',
                'uses' => 'ManageStoreController@settingsEditSupportData',
            ]);

            Route::post('/{formType}/edit-store-settings', [
                'as' => 'store.settings.edit',
                'uses' => 'ManageStoreController@editSettings',
            ]);

        });

        /*
          Brand Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Brand\Controllers',
                'prefix' => 'brand',
            ], function () {

            Route::get('/fetch', [
                'as' => 'manage.brand.list',
                'uses' => 'BrandController@index',
            ]);

            Route::post('/add', [
                'as' => 'manage.brand.add',
                'uses' => 'BrandController@addProccess',
            ]);

            Route::get('/{brandID}/detail', [
                'as' => 'manage.brand.detailSupportData',
                'uses' => 'BrandController@getDetail',
            ])->where('brandID', '[0-9]+');

            Route::get('/{brandID}/edit', [
                'as' => 'manage.brand.editSupportData',
                'uses' => 'BrandController@editSupportData',
            ])->where('brandID', '[0-9]+');

            Route::post('/{brandID}/edit', [
                'as' => 'manage.brand.edit.process',
                'uses' => 'BrandController@editProcess',
            ])->where('brandID', '[0-9]+');

            Route::post('/{brandID}/delete', [
                'as' => 'manage.brand.delete',
                'uses' => 'BrandController@delete',
            ])->where('brandID', '[0-9]+');

        });

        /*
          Coupon Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Coupon\Controllers',
                'prefix' => 'coupon',
            ], function () {

            Route::get('/{status}/fetch', [
                'as' => 'manage.coupon.list',
                'uses' => 'CouponController@index',
            ]);

            Route::get('/add', [
                'as' => 'manage.coupon.fetch.couponDiscountType',
                'uses' => 'CouponController@getCouponDiscountType',
            ]);

            Route::post('/add', [
                'as' => 'manage.coupon.add',
                'uses' => 'CouponController@addProcess',
            ]);

            Route::get('/{couponID}/edit', [
                'as' => 'manage.coupon.editSupportData',
                'uses' => 'CouponController@editSupportData',
            ]);

            Route::get('/{couponID}/detail', [
                'as' => 'manage.coupon.detailSupportData',
                'uses' => 'CouponController@getDetail',
            ]);

            Route::post('/{couponID}/edit', [
                'as' => 'manage.coupon.edit.process',
                'uses' => 'CouponController@editProcess',
            ]);

            Route::post('/{couponID}/delete', [
                'as' => 'manage.coupon.delete',
                'uses' => 'CouponController@delete',
            ]);

        });

        /*
          Shipping Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Shipping\Controllers',
                'prefix' => 'shipping',
            ], function () {

            Route::get('/fetch', [
                'as' => 'manage.shipping.list',
                'uses' => 'ShippingController@index',
            ]);

            Route::get('/fetch/contries', [
                'as' => 'manage.shipping.fetch.contries',
                'uses' => 'ShippingController@getCountries',
            ]);

            Route::get('/{shippingID}/detail', [
                'as' => 'manage.shipping.detailSupportData',
                'uses' => 'ShippingController@getDetail',
            ])->where('shippingID', '[0-9]+');

            Route::post('/add', [
                'as' => 'manage.shipping.add',
                'uses' => 'ShippingController@addProcess',
            ]);

            Route::get('/{shippingID}/edit', [
                'as' => 'manage.shipping.editSupportData',
                'uses' => 'ShippingController@editSupportData',
            ])->where('shippingID', '[0-9]+');

            Route::post('/{shippingID}/edit', [
                'as' => 'manage.shipping.edit.process',
                'uses' => 'ShippingController@editProcess',
            ])->where('shippingID', '[0-9]+');

            Route::post('/{shippingID}/delete', [
                'as' => 'manage.shipping.delete',
                'uses' => 'ShippingController@delete',
            ])->where('shippingID', '[0-9]+');

            Route::get('/edit-aoc', [
                'as' => 'manage.aoc.shipping.editSupportData',
                'uses' => 'ShippingController@getAocSupportData',
            ]);

            Route::post('/update', [
                'as' => 'manage.aoc.shipping.update',
                'uses' => 'ShippingController@aocProcess',
            ]);

        });

        /*
          Tax Components Manage Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Tax\Controllers',
                'prefix' => 'tax',
            ], function () {

            Route::get('/fetch', [
                'as' => 'manage.tax.list',
                'uses' => 'TaxController@index',
            ]);

            Route::get('/fetch/contries', [
                'as' => 'manage.tax.fetch.contries',
                'uses' => 'TaxController@getCountries',
            ]);

            Route::get('/{taxID}/detail', [
                'as' => 'manage.tax.detailSupportData',
                'uses' => 'TaxController@getDetail',
            ])->where('taxID', '[0-9]+');

            Route::post('/add', [
                'as' => 'manage.tax.add',
                'uses' => 'TaxController@addProcess',
            ]);

            Route::get('/{taxID}/edit', [
                'as' => 'manage.tax.editSupportData',
                'uses' => 'TaxController@editSupportData',
            ])->where('taxID', '[0-9]+');

            Route::post('/{taxID}/edit', [
                'as' => 'manage.tax.edit.process',
                'uses' => 'TaxController@editProcess',
            ])->where('taxID', '[0-9]+');

            Route::post('/{taxID}/delete', [
                'as' => 'manage.tax.delete',
                'uses' => 'TaxController@delete',
            ])->where('taxID', '[0-9]+');

        });

        /*
          Report Components Section Related Routes
          ------------------------------------------------------------------- */

        Route::group([
                'namespace' => 'Report\Controllers',
                'prefix' => 'report',
            ], function () {
            // fetch order report
            Route::get('/{startDate}/{endDate}/{status}/{order}/fetch-order-report', [
                'as' => 'manage.order.report.get.data',
                'uses' => 'ReportController@index',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // order report dialog
            Route::get('/{orderID}/get/order-details', [
                'as' => 'manage.order.report.details.dialog',
                'uses' => 'ReportController@orderDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // pdf download
            Route::get('/{orderID}/pdf-download', [
                'as' => 'report.pdf_download',
                'uses' => 'ReportController@pdfDownload',
            ])->where('orderID', '[0-9]+');

            // generate excel sheet
            Route::get('/{startDate}/{endDate}/{status}/{order}/excel-download', [
                'as' => 'report.excel_download',
                'uses' => 'ReportController@excelDownload',
            ])->where(['status' => '[0-9]+', 'order' => '[0-9]+']);

            // order report config items
            Route::get('/get/order-config-items', [
                'as' => 'manage.order.get.config.items',
                'uses' => 'ReportController@orderConfigItems',
            ])->where('orderID', '[0-9]+');

        });

        /*
          Order Components Manage Section Related Routes
          ------------------------------------------------------------------- */
        Route::group([
                'namespace' => 'ShoppingCart\Controllers',
                'prefix' => 'order',
            ], function () {

            // get orders related to users
            Route::get('/get/{status}/order-list/{userID?}', [
                'as' => 'manage.get.orders.data',
                'uses' => 'ManageOrderController@index',
            ])->where('status', '[0-9]+');

            // get cart btn string
            Route::get('/get/{orderID}/order-update-data', [
                'as' => 'manage.order.update.support.data',
                'uses' => 'ManageOrderController@orderUpdateSupportData',
            ])->where('orderID', '[0-9]+');

            // order update
            Route::post('/order/{orderID}/update', [
                'as' => 'manage.order.update',
                'uses' => 'ManageOrderController@orderUpdate',
            ])->where('orderID', '[0-9]+');

            // get cart btn string
            /*Route::get('/get/{orderID}/order-cancel-data', [
                'as'    => 'manage.order.cancel.support.data', 
                'uses'  => 'ManageOrderController@orderCancelSupportData'
            ])->where('orderID', '[0-9]+');

            // order update
            Route::post('/order/{orderID}/cancel', [
                'as'    => 'manage.order.cancel', 
                'uses'  => 'ManageOrderController@orderCancel'
            ])->where('orderID', '[0-9]+');*/

            // order dialog
            Route::get('/get/{orderID}/order-details', [
                'as' => 'manage.order.details.dialog',
                'uses' => 'ManageOrderController@orderDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // order log dialog
            Route::get('/get/{orderID}/order-log-details', [
                'as' => 'manage.order.log.details.dialog',
                'uses' => 'ManageOrderController@orderLogDetailsSupportData',
            ])->where('orderID', '[0-9]+');

            // get order user data
            Route::get('/get/{orderID}/order-user-details', [
                'as' => 'manage.order.get.user.details',
                'uses' => 'ManageOrderController@getUserDetails',
            ])->where('orderID', '[0-9]+');

            // send mail to user
            Route::post('/order-user-details', [
                'as' => 'manage.order.user.contact',
                'uses' => 'ManageOrderController@prepareContactUser',
            ]);

        });

        /*
          Order Payments Components Manage Section Related Routes
          ------------------------------------------------------------------- */
        Route::group([
                'namespace' => 'ShoppingCart\Controllers',
                'prefix' => 'order',
            ], function () {

            // order payment details
            Route::get('/get/{orderPaymentID}/order-payment-details', [
                'as' => 'order.payment.detail.dialog',
                'uses' => 'OrderPaymentsController@orderPaymentDetails',
            ])->where('orderPaymentID', '[0-9]+');

            // refund order payment detail dialog
            Route::get('/get/{orderID}/order-payment-refund', [
                'as' => 'order.payment.refund.detail.dialog',
                'uses' => 'OrderPaymentsController@orderRefundDetails',
            ])->where('orderID', '[0-9]+');

            // refund order payment detail dialog
            Route::post('/{orderID}/order-payment-refund', [
                'as' => 'order.payment.refund.process',
                'uses' => 'OrderPaymentsController@orderRefund',
            ])->where('orderID', '[0-9]+');

            // update order payment detail dialog
            Route::get('/get/{orderID}/order-payment-update', [
                'as' => 'order.payment.update.detail.dialog',
                'uses' => 'OrderPaymentsController@orderPaymentUpdateDetails',
            ])->where('orderID', '[0-9]+');

            // update order payment
            Route::post('/{orderID}/order-payment-update', [
                'as' => 'order.payment.update.process',
                'uses' => 'OrderPaymentsController@orderPaymentUpdate',
            ])->where('orderID', '[0-9]+');

            // Order payment List
            Route::get('/get/{startDate}/{endDate}/', [
                'as' => 'order.payment.list',
                'uses' => 'OrderPaymentsController@index',
            ]);

            // generate excel sheet
            Route::get('/{startDate}/{endDate}/excel-download', [
                'as' => 'payment.excel_download',
                'uses' => 'OrderPaymentsController@excelDownload',
            ]);

           });

    });

});

Route::group([
    'namespace' => 'ShoppingCart\Controllers',
    'prefix' => 'order/',
], function () {

    // PayPal IPN Request
    Route::post('ipn-request', [
        'as' => 'order.ipn_request',
        'uses' => 'OrderPaymentsController@processPaypalIpnRequest',
    ]);

    // PayPal Payment Thanks
    Route::post('thank-you', [
        'as' => 'order.thank_you',
        'uses' => 'OrderController@thanksOnPayPalOrder',
    ]);

    // PayPal Payment Cancelled by user
    Route::get('payment-cancelled/{orderToken}', [
        'as' => 'order.payment_cancelled',
        'uses' => 'OrderController@paymentCancel',
    ]);

});

// LFM Routes
//$middlewares = \Config::get('lfm.middlewares');
//array_push($middlewares, '\Yesteamtech\Laravelfilemanager\middleware\MultiUser');
$middlewares = \Config::get('lfm.middlewares');
array_push($middlewares, '\Yesteamtech\Laravelfilemanager\middleware\MultiUser');

// make sure authenticated
Route::group(array('middleware' => $middlewares,
    'prefix' => 'upload-filemanager', ), function () {

    // Show LFM
    Route::get('/', [
        'as' => 'yesteamtech.lfm.show',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\LfmController@show',
    ]);

    // upload
    Route::post('/upload', [
        'as' => 'yesteamtech.lfm.upload',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\UploadController@upload',
    ]);

    // list images & files
    Route::get('/jsonitems', [
        'as' => 'yesteamtech.lfm.getItems',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\ItemsController@getItems',
    ]);

    // folders
    Route::get('/newfolder', [
        'as' => 'yesteamtech.lfm.getAddfolder',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\FolderController@getAddfolder',
    ]);
    Route::get('/deletefolder', [
        'as' => 'yesteamtech.lfm.getDeletefolder',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\FolderController@getDeletefolder',
    ]);

    Route::get('/folders', [
        'as' => 'yesteamtech.lfm.getFolders',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\FolderController@getFolders',
    ]);

    // crop
    Route::get('/crop', [
        'as' => 'yesteamtech.lfm.getCrop',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\CropController@getCrop',
    ]);
    Route::get('/cropimage', [
        'as' => 'yesteamtech.lfm.getCropimage',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\CropController@getCropimage',
    ]);

    // rename
    Route::get('/rename', [
        'as' => 'yesteamtech.lfm.getRename',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\RenameController@getRename',
    ]);

    // scale/resize
    Route::get('/resize', [
        'as' => 'yesteamtech.lfm.getResize',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\ResizeController@getResize',
    ]);
    Route::get('/doresize', [
        'as' => 'yesteamtech.lfm.performResize',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\ResizeController@performResize',
    ]);

    // download
    Route::get('/download', [
        'as' => 'yesteamtech.lfm.getDownload',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\DownloadController@getDownload',
    ]);

    // delete
    Route::get('/delete', [
        'as' => 'yesteamtech.lfm.getDelete',
        'uses' => '\Yesteamtech\Laravelfilemanager\controllers\DeleteController@getDelete',
    ]);
});
