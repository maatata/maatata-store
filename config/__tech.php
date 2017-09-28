<?php
// Response Codes & other global configurations
$techConfig = require app_path('Yantrana/__Laraware/Config/tech-config.php');

$techAppConfig = [
     "gettext_fallback" => true,

    /* Paths
    ------------------------------------------------------------------------- */

    "custom_pages"        => "external-pages/",
    "product_assets"      => "media-storage/products/product-",
    "product_user_assets" => "media-storage/users/user-",
    'day_date_time_format'=> 'l jS F Y  g:ia',
    'account_activation'  => (60*60*48),
    'cart_expiration_time' => 60*60*24*365, // shoppingCart expiration in 1 year

    /* pagination
    ------------------------------------------------------------------------- */

    'pagination_count'  => 5,

    /* character limit
    ------------------------------------------------------------------------- */

    'character_limit'  => 16,

    /* quantity limit
    ------------------------------------------------------------------------- */

    'qty_limit'  => 99999,

    /* international shipping - All Other Countries
    ------------------------------------------------------------------------- */

    'aoc'  => 'AOC', // treated as Country Code
    'aoc_id'  => 999, // id in countries table 

    /* shop logo name
    ------------------------------------------------------------------------- */

    'logoName'  => 'logo.png',

    /* Email Config
    ------------------------------------------------------------------------- */

    'mail_from'         =>  [ 
        env('MAIL_FROM_ADD', 'your@domain.com'),
        env('MAIL_FROM_NAME', 'E-Mail Service')
    ],

    /* Account related 
    ------------------------------------------------------------------------- */

    'account' => [
        'activation_expiry'         => 24 * 2, // hours
        'change_email_expiry'       => 24 * 2, // hours
        'password_reminder_expiry'  => 24 * 2, // hours
        'passwordless_login_expiry' => 5, // minutes
    ],

    'login_attempts'    =>  5,

    /* Status Code Multiple Uses
    ------------------------------------------------------------------------- */

    'status_codes' => [
        1 => ('Active'),
        2 => ('Inactive'),
        3 => ('Banned'),
        4 => ('Never Activated'),
        5 => ('Deleted'),
        6 => ('Suspended'),
        7 => ('On Hold'),
        8 => ('Completed'),
        9 => ('Invite')
    ],

    /* User Roles
    ------------------------------------------------------------------------- */

    'roles' => [
        1 => ('Admin'),
        2 => ('User'),
    ],

    /* Assigned user status codes
    ------------------------------------------------------------------------- */

    'user' => [
        'status_codes' => [ 
            1, // active
            2, // deactive
            3, // banned
            4, // never activated
            5  // deleted
        ]
    ],

    /* Order Status
    ------------------------------------------------------------------------- */

    'orders' => [
        'type' => [ 
            1 => ('Order by Email'),
            2 => ('PayPal')
        ],
        'payment_methods' => [ 
            1 => ('PayPal'), // PayPal IPN Payments
            2 => ('Check'),
            3 => ('Bank Transfer'),
            4 => ('COD'),    
            5 => ('Other')  
        ],
        'payment_status' => [ 
            1 => ('Awaiting Payment'), // PayPal IPN Payments
            2 => ('Completed'),
            3 => ('Payment Failed'),
            4 => ('Pending'),
            5 => ('Refunded')
        ],
        'payment_type' => [ 
            1 => ('Deposit'),
            2 => ('Refund')
        ],
        'products' => [ 
            1 => ('Ordered'),
            2 => ('Confirmed & Available'),
            3 => ('Cancelled'),
            4 => ('Not Available'),
            5 => ('Not Shippable')
        ],
        'status_codes' => [ 
            1 => ('New'),
            2 => ('Processing'),
            3 => ('Cancelled'),
            4 => ('On Hold'),
            5 => ('In Transit'),
            6 => ('Completed'),
            7 => ('Confirmed'),
            // 8 => ('Cancellation Request Received'),
            // 9 => ('User Cancelled'),
            //10 => ('Invalid'),
            11 => ('Delivered')
        ],
        'date_filter_code' => [ 
            1 => ('Placed'),
            2 => ('Updated')
        ],
    ],


    /* Manage Pages related
    --------------------------------------------------------------------------*/

    'pages_status_codes' => [
        1 => ('Yes'),
        2 => ('No')
    ],

    'pages_types' => [
        1 => ('Page'),
        2 => ('Link')
    ],

    'pages_types_with_system_link' => [
        1 => ('Page'),
        2 => ('Link'),
        3 => ('System Link')
    ],


    /* Reserve page id
    ------------------------------------------------------------------------- */
    'reserve_pages_ids'    =>  [/*1,*/ 2, 3, 4, 5, 6],
    'reserve_pages'        =>  [/*1,*/ 2, 3, 4, 5, 6],

    'system_links'  => [
        //'home'         => 1,
        'categories' => 2,
        'brand'      => 3,
        'login'      => 4,
        'register'   => 5,
        'contact'    => 6
    ],

    'pages_type_codes' => [1,2,3],

    'link_target' => [
        '_blank'  => ('_blank'),
        '_self'   => ('_self') ,
        '_parent' => ('_parent')
    ],

    'link_target_array' => ['_blank','_self','_parent'],

    /* Manage categories related
    --------------------------------------------------------------------------*/

    'categories_status_codes' => [
        1 => ('Active'),
        2 => ('Deactive')
    ],


    /* Store Related Config Values
    --------------------------------------------------------------------------*/

    'currencies'         => [
        'options'   => [
            'AUD' => ('Australian Dollar'),
            'CAD' => ('Canadian Dollar'),
            'EUR' => ('Euro'),
            'GBP' => ('British Pound'),
            'USD' => ('U.S. Dollar'),
            'NZD' => ('New Zealand Dollar'),
            'CHF' => ('Swiss Franc'),
            'HKD' => ('Hong Kong Dollar'),
            'SGD' => ('Singapore Dollar'),
            'SEK' => ('Swedish Krona'),
            'DKK' => ('Danish Krone'),
            'PLN' => ('Polish Zloty'),
            'NOK' => ('Norwegian Krone'),
            'HUF' => ('Hungarian Forint'),
            'CZK' => ('Czech Koruna'),
            'ILS' => ('Israeli New Shekel'),
            'MXN' => ('Mexican Peso'),
            'BRL' => ('Brazilian Real (only for Brazilian members)'),
            'MYR' => ('Malaysian Ringgit (only for Malaysian members)'),
            'PHP' => ('Philippine Peso'),
            'TWD' => ('New Taiwan Dollar'),
            'THB' => ('Thai Baht'),
            'TRY' => ('Turkish Lira (only for Turkish members)'),
            ''    => ('Other')
        ],
        'details'    => [

            'AUD' => [
                'name'   => ("Australian Dollar"), 
                'symbol' => "A$", 
                'ASCII'  => "A&#36;"
            ],
                 
            'CAD' => [
                'name'   => ("Canadian Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'CZK' => [
                'name'   => ("Czech Koruna"), 
                'symbol' => "Kč", 
                'ASCII'  => "K&#x10d;"
            ],

            'DKK' => [
                'name'   => ("Danish Krone"), 
                'symbol' => "Kr", 
                'ASCII'  => "K&#x72;"
            ],

            'EUR' => [
                'name'   => ("Euro"), 
                'symbol' => "€", 
                'ASCII'  => "&euro;"
             ],

            'HKD' => [
                'name'   => ("Hong Kong Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'HUF' => [
                'name'   => ("Hungarian Forint"), 
                'symbol' => "Ft", 
                'ASCII'  => "F&#x74;"
            ],

            'ILS' => [
                'name'   => ("Israeli New Sheqel"), 
                'symbol' => "₪", 
                'ASCII'  => "&#8361;"
            ],

            'JPY' => [
                'name'   => ("Japanese Yen"), 
                'symbol' => "¥", 
                'ASCII'  => "&#165;"
            ],

            'MXN' => [
                'name'   => ("Mexican Peso"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'NOK' => [
                'name'   => ("Norwegian Krone"), 
                'symbol' => "Kr", 
                'ASCII'  => "K&#x72;"
            ],

            'NZD' => [
                'name'   => ("New Zealand Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'PHP' => [
                'name'   => ("Philippine Peso"), 
                'symbol' => "₱", 
                'ASCII'  => "&#8369;"
            ],

            'PLN' => [
                'name'   => ("Polish Zloty"), 
                'symbol' => "zł", 
                'ASCII'  => "z&#x142;"
            ],

            'GBP' => [
                'name'   => ("Pound Sterling"), 
                'symbol' => "£", 
                'ASCII'  => "&#163;"
            ],

            'SGD' => [
                'name'   => ("Singapore Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ],

            'SEK' => [
                'name'   => ("Swedish Krona"), 
                'symbol' => "kr", 
                'ASCII'  => "K&#x72;"
            ],

            'CHF' => [
                'name'   => ("Swiss Franc"), 
                'symbol' => "CHF", 
                'ASCII'  => "&#x43;&#x48;&#x46;"
            ],

            'TWD' => [
                'name'   => ("Taiwan New Dollar"), 
                'symbol' => "NT$", 
                'ASCII'  => "NT&#36;"
            ],

            'THB' => [
                'name'   => ("Thai Baht"), 
                'symbol' => "฿", 
                'ASCII'  => "&#3647;"
            ],

            'USD' => [
                'name'   => ("U.S. Dollar"), 
                'symbol' => "$", 
                'ASCII'  => "&#36;"
            ]
        ],
    ],

    'menu_placement' =>  [
        [
            'value'    => 1,
            'name'  => ('Sidebar')
        ],
        [
            'value'    => 2,
            'name'  => ('Top Menu')
        ],
        [
            'value'    => 3,
            'name'  => ('Both')
        ],
        [
            'value'    => 4,
            'name'  => ('Dont Show')
        ]
    ],

    'store_settings' => [
    // General Tab
        'store_name',
        'logo_image',
        'logo_background_color',
        'business_email',
        'home_page',
        'timezone',
    // Currency Tab
        'currency',
        'currency_symbol',
        'currency_value',

        //'allow_customer_order', // Allow customer to cancel order if its new & payment not completed
                                // or raise request for cancellation if its pending and payment is 
                                //completed.
        // Order & Payments Tab
        'payment_other',
        'payment_other_text',
        'hide_sidebar_on_order_page',
        'use_paypal',
        'paypal_email',
        'payment_check',
        'payment_check_text',
        'payment_bank',
        'payment_bank_text',
        'payment_cod',
        'payment_cod_text',
    // Products Tab
        'show_out_of_stock', // Show out of stock products to user
        'pagination_count',
    // Placement & Other Elements
        'categories_menu_placement',
        'brand_menu_placement',
        'credit_info',
        'addtional_page_end_content', 
        'footer_text',
        'show_language_menu',
    // Contact
        'contact_email',
        'contact_address',
    // Terms and Condition Tab
        'term_condition', 
    // Privacy policy Tab
        'privacy_policy',
    // Social Tab
        'social_facebook',
        'social_twitter',
    ],

    'address_type' => [
        1 => ('Home'),
        2 => ('Office'),
        3 => ('Other Address')
    ],

    /*
    ------------------------------------------------------------------------- */
    'home_page_setting' => [
        1 => ('Home page'),
        2 => ('All Products'),
        3 => ('Featured Products'),
        4 => ('Brands')
    ],

    'address_type_list' =>  [
        [
            'id'    => 1,
            'name'  => ('Home')
        ],
        [
            'id'    => 2,
            'name'  => ('Office')
        ],
        [
            'id'    => 3,
            'name'  => ('Other Address')
        ]
    ],

    'payment_methods_list' =>  [
        [
            'id'    => 1,
            'name'  => ('PayPal')
        ],
        [
            'id'    => 2,
            'name'  => ('Check')
        ],
        [
            'id'    => 3,
            'name'  => ('Bank Transfer')
        ],
        [
            'id'    => 4,
            'name'  => ('COD')
        ],
        [
            'id'    => 5,
            'name'  =>  ('Other')
        ]
    ],

    // Brand Status
    'brand_status' => [
        1 => ('Active'),
        2 => ('Deactive')
    ],

    // Coupon Discount Type
    'coupon_type' => [
        1 => ('Amount'),
        2 => ('Percentage')
    ],

    'coupon_discount_type' =>  [
        [
            'id'    => 1,
            'name'  => ('Amount')
        ],
        [
            'id'    => 2,
            'name'  => ('Percentage')
        ]
    ],

    /* Shipping 
    ------------------------------------------------------------------------- */
    'shipping' => [
        'type' => [
        
            [
                'id'    => 1,
                'name'  => ('Flat')
            ],
            [
                'id'    => 2,
                'name'  => ('Percentage')
            ],
            [
                'id'    => 3,
                'name'  => ('Free')
            ],
            [
                'id'    => 4,
                'name'  => ('Not Shippable')
            ]
        ],
        'typeShow' => [
            1 => ('Flat'),
            2 => ('Percentage'),
            3 => ('Free'),
            4 => ('Not Shippable')
        ],
        'status' => [
            1 => ('Active'),
            2 => ('Deactive')
        ]
    ],

    /* Tax 
    ------------------------------------------------------------------------- */
    'tax' => [
        'type' => [
            1 => ('Flat'),
            2 => ('Percentage')/*,
            3 => ('No Tax')*/
        ],
        'status' => [
            1 => ('Active'),
            2 => ('Deactive')
        ]
    ],

    /* Report duration 
    ------------------------------------------------------------------------- */
    'report_duration' => [
            1 => ('Current Month'),
            2 => ('Last Month'),
            3 => ('Current Week'),
            4 => ('Last Week'),
            5 => ('Today'),
            6 => ('Yesterday'),
            7 => ('Custom')
    ],
    /* PayPal URLs
    ------------------------------------------------------------------------- */
    "paypal_urls" => [
        "production" => "https://www.paypal.com/cgi-bin/webscr",
        "sandbox" => "https://www.sandbox.paypal.com/cgi-bin/webscr",
    ],
];

return array_merge( $techConfig, $techAppConfig );