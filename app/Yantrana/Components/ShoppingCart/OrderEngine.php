<?php
/*
* OrderEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

// services
use App\Yantrana\Support\MailService;

// supported Repository to order
use App\Yantrana\Components\ShoppingCart\Repositories\OrderRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderEngineBlueprint;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderPaymentsRepository;

// supported engine to order
use App\Yantrana\Components\User\AddressEngine;
use App\Yantrana\Components\Coupon\CouponEngine;
use App\Yantrana\Components\Shipping\ShippingEngine;
use App\Yantrana\Components\Tax\TaxEngine;
use Auth;
use ShoppingCart; // custom shopping cart
use NativeSession; // custom Session
use PDF;
use Breadcrumb;

class OrderEngine implements OrderEngineBlueprint
{
    /**
     * @var OrderRepository - Order Repository
     */
    protected $orderRepository;

    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * @var AddressEngine - Address Repository
     */
    protected $addressEngine;

    /**
     * @var CouponEngine - Coupon Engine
     */
    protected $couponEngine;

    /**
     * @var ShippingEngine - Shipping Engine
     */
    protected $shippingEngine;

    /**
     * @var TaxEngine - Tax Engine
     */
    protected $taxEngine;

    /**
     * @var TaxRepository - Tax Repository
     */
    protected $taxRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderPaymentsRepository
     */
    protected $orderPaymentsRepository;

    /**
     * Constructor.
     *
     * @param ShoppingCartEngine $shoppingCartEngine - ShoppingCart Engine
     * @param OrderRepository    $orderRepository    - Order Repository
     * @param AddressEngine      $addressEngine      - Address Engine
     * @param CouponEngine       $CouponEngine       - Coupon Engine
     * @param ShippingEngine     $shippingEngine     - Shipping Engine
     * @param TaxEngine          $taxEngine          - Tax Engine
     *-----------------------------------------------------------------------*/
    public function __construct(OrderRepository $orderRepository,
        CouponRepository $couponRepository,
        ShippingRepository $shippingRepository, TaxRepository $taxRepository,
        ShoppingCartEngine $shoppingCartEngine,
        AddressEngine $addressEngine,
        AddressRepository $addressRepository, CouponEngine $couponEngine,
        ShippingEngine $shippingEngine, TaxEngine $taxEngine,
        MailService $mailService, OrderPaymentsRepository $orderPaymentsRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->couponRepository = $couponRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->shoppingCartEngine = $shoppingCartEngine;
        $this->addressEngine = $addressEngine;
        $this->addressRepository = $addressRepository;
        $this->couponEngine = $couponEngine;
        $this->shippingEngine = $shippingEngine;
        $this->taxEngine = $taxEngine;
        $this->mailService = $mailService;
        $this->orderPaymentsRepository = $orderPaymentsRepository;
    }

    /**
     * Get Order Details using _UID or id.
     *
     * @param string/int $id - Order UID/id
     *-----------------------------------------------------------------------*/
    public function getByIdOrUid($id)
    {
        return $this->orderRepository->fetch($id);
    }

    /**
     * Fetch cart data from to cart.
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function fetchCartData()
    {
        return ShoppingCart::fetch();
    }

    /**
     * coupon apply process request.
     *
     * @param string $code
     * @param $cartTotalPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processApplyCoupon($code)
    {
        return $this->couponEngine->applyCouponProcess($code);
    }

    /**
     * This page return order summary page data.
     *
     * @param string $country
     * @param float  $discountAddedPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderSummaryData($addressID, $addressID1, $couponCode)
    {
        // this function use for get cart details
        $orderDetails = $this->shoppingCartEngine->getCartDetails();

        $totalPayableAmountFormated = 0;
        $countryCode = '';

        $sameAddres = false;

        if (__isEmpty($addressID) and __isEmpty($addressID1)) {
            $sameAddres = true;
        } elseif ((int) $addressID  === (int) $addressID1) {
            $sameAddres = true;
        } elseif ($addressID1 == 'null') {
            $sameAddres = true;
        }

        if (!__isEmpty($orderDetails)) {
            $cartTotalPrice = $orderDetails['data']['total']['totalBasePrice'];

            // taken primary address of customer
            if ($addressID == 'null') {
                $address = $this->addressEngine->getUserPrimaryAddress();
            } elseif ($addressID != 'null') {
                $address = $this->addressEngine->getUserAddress($addressID);
            }

            if (__isEmpty($address['address'])) {
                $billing = $this->addressEngine->getUserAddress($addressID1);

                $orderDetails['data']['billingAddress'] = $billing['address'];
            } else {
                $orderDetails['data']['shippingAddress'] = $address['address'];

                if ($sameAddres == false) {
                    $billing = $this->addressEngine->getUserAddress($addressID1);

                    $orderDetails['data']['billingAddress'] = $billing['address'];
                } else {
                    $billing = $this->addressEngine->getUserAddress($addressID1);

                    $orderDetails['data']['billingAddress'] = $billing['address'];
                }

                $countryCode = $address['address']['countryCode'];
            }

            $discount = null;
            $couponData = [];

            // Check if coupon code is exist
            if ($couponCode != 'null') {
                $couponData = $this->couponEngine->applyCouponProcess($couponCode);

                if (!__isEmpty($couponData) and $couponData['reaction_code'] === 1) {
                    $discount = $couponData['data']['couponData']['discount'];
                }
            } else {
                $couponCode = null;
            }

            // add shipping base on country in total order amount
            $shipping = $this->shippingEngine->addShipping($countryCode, $cartTotalPrice, $discount);

            $orderDetails['data']['shipping'] = $shipping;

            // add taxses base on country with cart cart amount
            $orderDetails['data']['taxses'] = $this->taxEngine->additionOfTaxses($countryCode, $cartTotalPrice, $shipping['totalPrice']);

            // total amount should be pay by customer
            $totalPayableAmountFormated = $orderDetails['data']['taxses']['totalPrice'];
        }

        $taxIDs = [];
        $shippingID = null;

        // Check if taxes is exist
        if (!__isEmpty($orderDetails['data']['taxses']['info'])) {
            $taxData = $orderDetails['data']['taxses']['info'];

            foreach ($taxData as $key => $tax) {
                $taxIDs[] = $tax['id'];
            }
        }

        // Check if shipping exist
        if (!__isEmpty($shipping['info'])) {
            $shippingID = $shipping['info']['_id'];
        }

        $user = Auth::user();

        // set login user full name
        $orderDetails['data']['user'] = [
            'fullName' => $user->fname.' '.$user->lname,
        ];

        $orderDetails['data']['orderRoute'] = route('order.summary.view');
        $orderDetails['data']['totalPayableAmountFormated'] = priceFormat($totalPayableAmountFormated);
        $orderDetails['data']['totalPayableAmount'] = $totalPayableAmountFormated;
        $orderDetails['data']['discountAddedPrice'] = isset(
                                                                $shipping['discountAddedPrice'])
                                                                and !__isEmpty($shipping['discountAddedPrice'])
                                                                ? $shipping['discountAddedPrice']
                                                                : null;

        // prepare session data
        $sessionData = [
            'addressID' => $addressID,
            'shippingID' => $shippingID,
            'couponCode' => $couponCode,
            'taxID' => $taxIDs,
            'addressID1' => $addressID1,
            'sameAddress' => $sameAddres, // same address 
        ];

        $orderDetails['data']['sameAddress'] = $sameAddres; // same address 
        $orderDetails['data']['checkoutMethod'] = getStoreSettings('valid_checkout_methods'); // payment methods
        $orderDetails['data']['checkoutMethodInfo'] = [
            'checkText' => getStoreSettings('payment_check_text'),
            'bankText' => getStoreSettings('payment_bank_text'),
            'codText' => getStoreSettings('payment_cod_text'),
            'otherText' => getStoreSettings('payment_other_text'),
        ]; // payment methods text

        // create new session of order summary details
        NativeSession::set('orderSummaryDataIds', $sessionData);

        return __engineReaction(1, ['orderSummaryData' => $orderDetails]);
    }

    /**
     * update order summary page data.
     *
     * @param array $inputs
     *---------------------------------------------------------------- */
    protected function updateOrderSummaryData($inputs)
    {
        $orderSumamryData = $this->prepareOrderSummaryData(
            __isEmpty($inputs['addressID']) ? 'null' : $inputs['addressID'],
            __isEmpty($inputs['addressID1']) ? 'null' : $inputs['addressID1'],
            $inputs['couponCode']
        );

        return $orderSumamryData['data']['orderSummaryData']['data'];
    }

    /**
     * process order request for place order.
     *
     * @param array $inputs
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderProcess($inputs)
    {
        $orderUID = generateOrderID();

        $reactionCode = $this->orderRepository
                              ->processTransaction(function () use ($inputs, $orderUID) {

            $getCartItems = $this->fetchCartData();

               // Check if cart content empty
            if (__isEmpty($getCartItems)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('Your cart is empty.'));
            }

            // fetch cart data from to the session $this->fetchCartData();
            // check this cart item available in database 
            $productsDataForComapre = $this->shoppingCartEngine->getProductForCart($getCartItems);

            // this function match the value of database & cart data.
            // and return in to set key isValidItem or not
            $afterMatchCartItemsData = getRefinedCart(
                                            $getCartItems,
                                            $productsDataForComapre['products']
                                        );

            // if any item of cart is invlid then return error
            if ($afterMatchCartItemsData['cartReady'] === false) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],
                    __("We're sorry. The highlighted item(s) in your Shopping Cart are currently unavailable. Please remove the item(s) to proceed."));
            }

            $cartTotalAmount = $afterMatchCartItemsData['cartPriceTotal'];

            $orderSessionData = [];

            // Check if order summary data ids exist
            if (NativeSession::has('orderSummaryDataIds')) {
                $orderSessionData = NativeSession::get('orderSummaryDataIds');
            }

            // check address is selected or not
            $isValidAddress = $this->addressEngine->checkIsValidAddress($orderSessionData, $inputs);

            // this address is empty
            if ($isValidAddress === 3) {
                return $this->orderRepository->transactionResponse(2, null, __('Please select your shipping address.')); // address not selected
            }

            // this address1 is empty
            if ($isValidAddress === 4) {
                return $this->orderRepository->transactionResponse(2, null, __('Please select your billing address'));
                // billing address not selected
            }

            $prepareOrderData['addresses_id'] = $isValidAddress['addresses_id'];
            $prepareOrderData['addresses_id1'] = $isValidAddress['addresses_id1'];
            $country = $isValidAddress['country'];

            $discount = null;

            // check if the coupon is applied then check this coupon is valid
            if (!__isEmpty($orderSessionData['couponCode'])) {
                $couponData = $this->couponEngine->applyCouponProcess($orderSessionData['couponCode']);

                // check applied coupon
                if ($couponData['reaction_code'] == 2) {
                    return $this->orderRepository->transactionResponse(2, null, __('Your coupon may be inactive / expire / invalid, please remove this coupon and try another code.')); //coupon is expired
                }

                // set coupon data for order storing
                $coupon = $couponData['data']['couponData'];
                $discount = $coupon['discount'];
                $prepareOrderData['coupons__id'] = $coupon['couponID'];
                $prepareOrderData['discount_amount'] = $discount;
            }

            // check if shipping is valid take calculated data
            $shipping = $this->shippingEngine
                             ->getShipping($country);

            // Check if shipping is exist
            if (__isEmpty($shipping)) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('Invalid shipping rule.'));
            }

            // if shipping is empty or type 4 means [ not shipable ]
            if ($shipping->type == 4) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('We are sorry currently shipping not available for your country.'));
            }

            // Get calculation of shipping 
            $calculatedShipping = $this->shippingEngine
                                       ->addShipping(
                                            $shipping->country,
                                            $cartTotalAmount,
                                            $discount
                                        );

               $prepareOrderData['shipping_amount'] = $calculatedShipping['info']['shippingAmt'];

            // add taxses base on country with cart amount
            $taxData = $this->taxEngine
                            ->additionOfTaxses(
                                    $country,
                                    $cartTotalAmount,
                                    $calculatedShipping['totalPrice']
                                );

            $totalPayableAmount = $taxData['totalPrice'];

            // for check out perticular method for order payment
            if (!__ifIsset($inputs['checkout_method']) or !$this->isValidOrderMethod($inputs['checkout_method'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('Invalid Checkout Method'));
            }

            // if the currency is change the show this msg
            if (isset($inputs['currency']) and $inputs['currency'] != getStoreSettings('currency')) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('Some information may be updated from server, Please review your order again carefully.'));
            }

            $checkoutMethod = $inputs['checkout_method'];

            // if check the order price & total order price 
            if (formatAmount($totalPayableAmount) != formatAmount($inputs['totalPayableAmount'])) {
                return $this->orderRepository->transactionResponse(3, [
                            'orderSummaryData' => $this->updateOrderSummaryData($inputs),
                        ],  __('Some information may be updated from server, Please review your order again carefully.'));
            }

            // if set payment method 1 for paypal,
            $prepareOrderData['payment_method'] = $checkoutMethod;
            $prepareOrderData['total_amount'] = $totalPayableAmount;    // total amount of order
            $prepareOrderData['taxses'] = $taxData['info'];
            $prepareOrderData['type'] = $checkoutMethod == 1 ? 2 : 1; // offline
            $prepareOrderData['name'] = $inputs['fullName'];
            $prepareOrderData['status'] = 1;
            $prepareOrderData['payment_status'] = 1; // Avaiting Payment
            $prepareOrderData['order_uid'] = $orderUID;
            $prepareOrderData['currency_code'] = getStoreSettings('currency');
            $prepareOrderData['cartItems'] = $afterMatchCartItemsData['productData'];

               // save order request in database & return created order id
               if (!$storedOrder = $this->orderRepository->orderProcess(
                                                $prepareOrderData
                                    )) {
                   return $this->orderRepository->transactionResponse(2, null, __('oh..no. error..'));
               }

             // Get order details from database for latest placed order
             $order = $this->prepareOrderDataForSendMail($orderUID);

             // send mail of this order which is sucessfully placed order.
            if ($checkoutMethod != 1) { // not paypal

                $paymentDetails = [];

                if ($checkoutMethod == 2) { // order by Check

                    $paymentDetails = getStoreSettings('payment_check_text');
                } elseif ($checkoutMethod == 3) { // order by bank

                    $paymentDetails = getStoreSettings('payment_bank_text');
                } elseif ($checkoutMethod == 4) { // order by COD

                    $paymentDetails = getStoreSettings('payment_cod_text');
                }

                $messageData = [
                    'orderData' => $order,
                    'paymentDetails' => $paymentDetails,
                    'orderConfig' => config('__tech.address_type'),
                    'orderDetailsUrl' => route('my_order.details', $orderUID),
                ];

                $this->mailService->notifyCustomer('Your Order has been Submitted', 'order.customer-order', $messageData);
                $this->mailService->notifyAdmin('New Order Received', 'order.customer-order', $messageData);
            }

            // to display the msg of successfully order placed & provide facility ot user 
            // track this order.
            NativeSession::set('orderSuccessMessage', ['successStatus' => true]);

            if ($checkoutMethod != 1) { // remove items if Payment method is other than paypal
                // after send this mail the cart is empty
               $this->shoppingCartEngine->processRemoveAlItems();
            }

            return 1;

        });

        return __engineReaction($reactionCode, [
                    'orderID' => $orderUID,
                    'ckMethod' => __ifIsset($inputs['checkout_method'], $inputs['checkout_method'], 1),
                    'cartItems' => $this->shoppingCartEngine->updateCartString(),
                ]);
    }

   /**
    * prepare order products.
    *
    * @param array  $products
    * @param string $currency
    *
    * @return array
    *---------------------------------------------------------------- */
   protected function prepareOrderProducts($products, $currency)
   {
       // calculate product prices
        $subtotal = $orderProducts = [];

       foreach ($products as $pKey => $product) {
           $orderProducts['orderProducts'][$pKey] = [
                'productName' => str_limit($product['name'], $limit = 30, $end = '...'),
                '_id' => $product['_id'],
                'customProductId' => $product['custom_product_id'],
                'quantity' => $product['quantity'],
                'formatedPrice' => orderPriceFormat(
                                        $product['price'],
                                        $currency
                                    ),
                'detailsURL' => route('product.details', [
                                        'productID' => $product['products_id'],
                                        'productName' => str_slug($product['name']),
                                    ]),
                'imagePath' => getProductImageURL($product['products_id'], $product['product']['thumbnail']),
            ];

           $addonPrice = [];

            // Check if product option is exist
            if (!__isEmpty($product['product_option'])) {
                foreach ($product['product_option'] as $opKey => $options) {
                    $additionalPrice = $options['addon_price'];

                    // addon formated addon price push
                    $orderProducts['orderProducts'][$pKey]['option'][$opKey] = [

                        'formatedOptionPrice' => orderPriceFormat($options['addon_price'], $currency),
                        'addonPrice' => $additionalPrice,
                        'optionName' => $options['name'],
                        'valueName' => $options['value_name'],
                    ];

                    $addonPrice[] = $additionalPrice;
                }
            }

            // get add price total
            $totalAddonPrice = array_sum($addonPrice);

            // price and addon price total
            $priceAddInAddonPrice = $product['price'] + $totalAddonPrice;

            //add price formate
            $orderProducts['orderProducts'][$pKey]['formatedProductPrice'] = orderPriceFormat(
                                                                                $priceAddInAddonPrice,
                                                                                $currency
                                                                            );
           $orderProducts['orderProducts'][$pKey]['productWithAddonPrice'] = $priceAddInAddonPrice;
            // add quantity and price
            $multQtyWithPrice = $priceAddInAddonPrice * $product['quantity'];

            // add sub total price
            $orderProducts['orderProducts'][$pKey]['formatedTotal'] = orderPriceFormat($multQtyWithPrice, $currency);
           $orderProducts['orderProducts'][$pKey]['total'] = $multQtyWithPrice;

           $subtotal[] = $multQtyWithPrice;
       }

       $sumTotal = array_sum($subtotal);

       $orderProducts['subtotal'] = $sumTotal;
       $orderProducts['formatedSubtotal'] = orderPriceFormat($sumTotal, $currency);

       return $orderProducts;
   }

   /**
    * prepare data for order details.
    *
    * @param string / int $orderUidOrId
    *---------------------------------------------------------------- */
   protected function prepareOrderDetails($orderUidOrId)
   {
       $order = $this->orderRepository->fetchOrderDetails($orderUidOrId);

        // If order does not exist then return not found reaction code
        if (__isEmpty($order)) {
            return false;
        }

        // match key and get value 
        $orderConfigItems = config('__tech.orders');
       $orderStatus = $orderConfigItems['status_codes'][$order['status']];
       $orderType = $orderConfigItems['type'][$order['type']];
       $paymentStatus = $orderConfigItems['payment_status'][$order['payment_status']];
       $paymentMethod = $orderConfigItems['payment_methods'][$order['payment_method']];

        // get currency code 
        $currency = $order['currency_code'];

        // manipulate order products data like calculation, price formatting etc.
        $orderProducts = $this->prepareOrderProducts($order['order_product'], $currency);

        // take shipping & billing address
        $address = $this->addressEngine->getAddress($order['addresses_id'], $order['addresses_id1']);

        // take shipping data
        $shippingData = $this->shippingEngine
                             ->getShippingInformation($address['shippingAddress']['countryCode']);

        // take order taxes data
        $orderTaxes = $this->orderRepository->fetchOrderTaxDetails($order['_id']);

        // take shipping data
        $taxesData = $this->taxEngine->getTaxInformation($orderTaxes, $currency);

        // take coupon information
        $couponData = $this->couponEngine->getCouponInformation($order['coupons__id'], $currency);

        // get order payments
        $payment = $this->orderPaymentsRepository->fetchOrderPayments($order['_id']);

       $orderPayment = __ifIsset($payment, function ($payment) {
            return formatStoreDateTime($payment->created_at);
        }, null);

       return [
            'orderPlacedOn' => formatStoreDateTime($order['created_at']), // formatted placed on time
            'orderStatus' => $orderStatus,
            'status' => $order['status'],
            'businessEmail' => $order['business_email'],
            'orderUID' => $order['order_uid'],
            'userId' => $order['users_id'],
            'orderType' => $orderType,
            'orderDiscount' => isset($order['discount_amount']) ? $order['discount_amount'] : '',
            'currencyCode' => $order['currency_code'],
            'orderShippingAmount' => $order['shipping_amount'],
            'totalOrderAmount' => $order['total_amount'],
            'formatedPaymentStatus' => $paymentStatus,
            'formatedPaymentMethod' => $paymentMethod,
            'paymentStatus' => $order['payment_status'],
            'paymentMethod' => $order['payment_method'],
            'paymentCompletedOn' => $orderPayment,
            'orderProducts' => [
                'products' => $orderProducts['orderProducts'],
                'subtotal' => $orderProducts['subtotal'],
                'formatedSubtotal' => $orderProducts['formatedSubtotal'],
            ],
            'address' => $address,
            'user' => [
                'id' => $order['user']['id'],
                'email' => $order['user']['email'],
                'fullName' => $order['name'],
            ],
            'shipping' => $shippingData['info'],
            'taxes' => $taxesData['info'],
            'coupon' => $couponData,
        ];
   }

   /**
    * prepare order data for send mail.
    *
    * @param string $orderUID
    *
    * @return array
    *---------------------------------------------------------------- */
   public function prepareOrderDataForSendMail($orderUID)
   {
       $prepareForSendData = $this->prepareOrderDetails($orderUID);
       $currencyCode = $prepareForSendData['currencyCode'];

       return [
            'orderPlacedOn' => $prepareForSendData['orderPlacedOn'],
            'orderUID' => $prepareForSendData['orderUID'],
            'userId' => $prepareForSendData['userId'],
            'orderStatus' => $prepareForSendData['orderStatus'],
            'orderType' => $prepareForSendData['orderType'],
            'orderDiscount' => $prepareForSendData['orderDiscount'],
            'formatedOrderDiscount' => orderPriceFormat(
                                            $prepareForSendData['orderDiscount'],
                                            $currencyCode
                                        ),
            'currencyCode' => $currencyCode,
            'shippingAmount' => $prepareForSendData['orderShippingAmount'],
            'formatedShippingAmount' => orderPriceFormat(
                                            $prepareForSendData['orderShippingAmount'],
                                            $currencyCode
                                        ),
            'formatedTotalOrderAmount' => orderPriceFormat(
                                            $prepareForSendData['totalOrderAmount'],
                                            $currencyCode
                                        ),
            'formatedPaymentStatus' => $prepareForSendData['formatedPaymentStatus'],
            'formatedPaymentMethod' => $prepareForSendData['formatedPaymentMethod'],
            'paymentStatus' => $prepareForSendData['paymentStatus'],
            'paymentMethod' => $prepareForSendData['paymentMethod'],
            'orderProducts' => $prepareForSendData['orderProducts'],
            'address' => $prepareForSendData['address'],
            'fullName' => $prepareForSendData['user']['fullName'],
            'email' => $prepareForSendData['user']['email'],
            'taxes' => $prepareForSendData['taxes'],
        ];
   }

    /**
     * Send PayPal for Payment.
     *---------------------------------------------------------------- */
    public function createPaypalOrder($orderUID)
    {
        $orderDetails = $this->prepareOrderDetails($orderUID);
        $orderToken = uniqid(rand(111111, 999999));

        $current_locale = CURRENT_LOCALE;

        $cancelReturn = route('order.payment_cancelled', [
                'orderToken' => $orderToken,
            ]);
        $notifyUrl = route('order.ipn_request');
        $shoppingUrl = route('home');
        $returnTo = route('order.thank_you');
        $business = $orderDetails['businessEmail'];
        $currency = $orderDetails['currencyCode'];
        $shippingCharges = $orderDetails['orderShippingAmount'];
        $orderProducts = $orderDetails['orderProducts']['products'];
        $couponDiscount = $orderDetails['orderDiscount'] ?: 0;

        NativeSession::set('RECENT_PAYPAL_ORDER_'.$orderToken, $orderUID);

        $paypalUrl = '';

        if (env('USE_PAYPAL_SANDBOX', false) == true) {
            $paypalUrl .= 'Location: '.config('__tech.paypal_urls.sandbox');
        } else {
            $paypalUrl .= 'Location: '.config('__tech.paypal_urls.production');
        }

        $taxAmount = 0;

        if (isset($orderDetails['taxes']) and !__isEmpty($orderDetails['taxes'])) {
            foreach ($orderDetails['taxes'] as $taxItem) {
                $taxAmount = $taxAmount + $taxItem['taxAmount'];
            }
        }

        $paypalUrl     .= "?cmd=_cart&upload=1&charset=utf-8&currency_code=$currency&business=$business&cancel_return=$cancelReturn&notify_url=$notifyUrl&rm=2&handling_cart=$shippingCharges&discount_amount_cart=$couponDiscount&tax_cart=$taxAmount";

        $i = 1;

        foreach ($orderProducts as $items) {
            $paypalUrl .= '&item_name_'.$i.'='.$items['productName'];

            if (isset($items['option']) and !empty($items['option'])) {
                $paypalUrl .= ' - ';

                foreach ($items['option'] as $productOption) {
                    if (!empty($productOption)) {
                        $paypalUrl .= $productOption['optionName'].' : '.$productOption['valueName'].' ';
                    }
                }
            }

            $paypalUrl .= '&item_number_'.$i.'='.$items['customProductId'];
            $paypalUrl .= '&quantity_'.$i.'='.$items['quantity'];
            $paypalUrl .= '&amount_'.$i.'='.$items['productWithAddonPrice'];
            ++$i;
        }

        $paypalUrl .= "&return=$returnTo&custom=$orderUID&invoice=$orderUID";

        header($paypalUrl);
        exit();
    }

    /**
     * Process Thank you data for Paypal Order.
     *---------------------------------------------------------------- */
    public function processThanksPayPalOrder($orderUid)
    {
        $order = $this->getByIdOrUid($orderUid);

        if ((__ifIsset($order) == false)) {
            return false;
        }

        $this->shoppingCartEngine->processRemoveAlItems();

        return $orderUid;
    }

    /**
     * Prepare order list.
     *
     * @param int $methodId
     *---------------------------------------------------------------- */
    protected function isValidOrderMethod($methodId)
    {
        $validMethod = true;

        if (!in_array($methodId, getStoreSettings('valid_checkout_methods'))) {
            $validMethod = false;
        }

        return $validMethod;
    }

    /**
     * Prepare order list.
     *---------------------------------------------------------------- */
    public function prepareOrderList($status)
    {
        return $this->orderRepository
                    ->fetchOrdersForList($status);
    }

    /**
     * Prepare for My order details data and check user authentication.
     *
     * @param string $orderUID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getMyOrderDetails($orderUID)
    {
        // Get order details
        $orderDetails = $this->prepareForMyOrderDetails($orderUID);

        // Get user Id
        $userId = $orderDetails['data']['user']['id'];

        // check if login user Id and order user id 
        if (!isAdmin() and $userId != getUserID()) {
            return __engineReaction(18);
        }

        return $orderDetails;
    }

    /**
     * Prepare for to show order details data.
     *
     * @param string $orderUID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareForMyOrderDetails($orderUID)
    {
        $prepareOrderData = $this->prepareOrderDetails($orderUID);

        // Check if order data empty then return not exist reaction code
        if (__isEmpty($prepareOrderData)) {
            return __engineReaction(18);
        }

        $newOrderStatus = false;

        // Check if session have new order status
        if (NativeSession::has('orderSuccessMessage')) {
            $orderStatus = NativeSession::get('orderSuccessMessage');
            $newOrderStatus = $orderStatus['successStatus'];
        }

        $currencyCode = $prepareOrderData['currencyCode'];

        return __engineReaction(1, [
            'order' => [
                'formatedOrderPlacedOn' => $prepareOrderData['orderPlacedOn'],
                'orderUID' => $prepareOrderData['orderUID'],
                'formatedOrderStatus' => $prepareOrderData['orderStatus'],
                'orderStatus' => $prepareOrderData['status'],
                'formatedOrderType' => $prepareOrderData['orderType'],
                'orderDiscount' => $prepareOrderData['orderDiscount'],
                'formatedOrderDiscount' => orderPriceFormat(
                                                    $prepareOrderData['orderDiscount'],
                                                    $currencyCode
                                            ),
                'currencyCode' => $currencyCode,
                'shippingAmount' => $prepareOrderData['orderShippingAmount'],
                'formatedShippingAmount' => orderPriceFormat(
                                                    $prepareOrderData['orderShippingAmount'],
                                                    $currencyCode
                                            ),
                'formatedTotalOrderAmount' => orderPriceFormat(
                                                $prepareOrderData['totalOrderAmount'],
                                                $currencyCode
                                            ),
                'formatedPaymentStatus' => $prepareOrderData['formatedPaymentStatus'],
                'formatedPaymentMethod' => $prepareOrderData['formatedPaymentMethod'],
                'newOrderStatus' => $newOrderStatus,
                'paymentStatus' => $prepareOrderData['paymentStatus'],
                'paymentCompletedOn' => $prepareOrderData['paymentCompletedOn'],
            ],
            'orderProducts' => $prepareOrderData['orderProducts'],
            'address' => $prepareOrderData['address'],
            'user' => $prepareOrderData['user'],
            'taxes' => $prepareOrderData['taxes'],
            'coupon' => $prepareOrderData['coupon']['info'],
            'shipping' => $prepareOrderData['shipping'],
        ]);
    }

    /**
     * Change address in order then get shipping, tax and coupon amount.
     *
     * @param int   $countryCode
     * @param array $couponDetail
     *---------------------------------------------------------------- */
    public function changeAddressInOrderDetails($addressID)
    {
        // Get order details when address changed
        $orderDetails = $this->prepareOrderSummaryData($addressID);

        return __engineReaction(1, $orderDetails['data']);
    }

    /**
     * Process to Download Invoice for order by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processInvoiceDownload($orderID)
    {
        // get order detail by order ID
        $orderDetails = $this->prepareForMyOrderDetails($orderID);

        // array data for creation of string for pdf
        $arrayData = [
            ':currentDate' => formatStoreDateTime(currentDateTime()),
        ];

        // generated on string 
        $orderDetails['currentDateTime'] = __('Generated on :currentDate', $arrayData);

        // download pdf
        $orderInvoice = PDF::loadView('report.manage.pdf-report', ['orderDetails' => $orderDetails]);
        //$orderInvoice->stream();
        return $orderInvoice->download(str_slug($orderDetails['data']['order']['orderUID']).'.pdf');
    }

    /**
     * Update Order as Cancelled as PayPal payment cancelled by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function updatePaymentFailed($orderID)
    {
        return $this->orderRepository->updateOrderAndPaymentStatus($orderID, 3, 3);
    }

    /**
     * create breadcrumb for orders.
     *
     * @param string $breadcrumbType
     *
     * @return array
     *---------------------------------------------------------------- */
    public function breadcrumbGenerate($breadcrumbType)
    {
        $breadCrumb = Breadcrumb::generate($breadcrumbType);

        // Check if breadcrumb not empty
        if (!__isEmpty($breadCrumb)) {
            return __engineReaction(1, [
                'breadCrumb' => $breadCrumb,
            ]);
        }

        return __engineReaction(2, [
                'breadCrumb' => null,
            ]);
    }
}
