<?php
/*
* OrderController.php - Controller file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShoppingCart\OrderEngine;

// form Requests
use App\Yantrana\Support\CommonPostRequest;
use Session;
use JavaScript;
use Breadcrumb;
use NativeSession;

class OrderController extends BaseController
{
    /**
     * @var OrderEngine - Order Engine
     */
    protected $orderEngine;

    /**
     * Constructor.
     *
     * @param OrderEngine $orderEngine - Order Engine
     *-----------------------------------------------------------------------*/
    public function __construct(OrderEngine $orderEngine)
    {
        $this->orderEngine = $orderEngine;
    }

    /**
     * To display order summary view.
     *---------------------------------------------------------------- */
    public function displayOrderSummary()
    {
        if (isActiveUser() || !isLoggedIn()) {
            Session::put('redirect_intended', true);

            return redirect()->route('user.login');
        }

        // if seesion have order summary details then delete it
        if (Session::has('ordeSummaryDataIds')) {
            Session::forget('ordeSummaryDataIds');
        }

        return $this->loadPublicView('order.user.summary', [
                'breadCrumb' => Breadcrumb::generate('cart-order'),
            ]);
    }

    /**
     * This function use for get cart details of data for order.
     *
     * @param string $country
     * @param float  $discountAddedPrice
     *
     * @return json data
     *---------------------------------------------------------------- */
    public function cartOrderDetails($addressID, $addressID1, $couponCode)
    {
        if (Session::has('redirect_intended')) {
            Session::forget('redirect_intended');
        }

        $engineReaction = $this->orderEngine->prepareOrderSummaryData($addressID, $addressID1, $couponCode);

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
     * order submit process.
     *
     * @param CommonPostRequest $request
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function orderProcess(CommonPostRequest $request)
    {
        $engineReaction = $this->orderEngine
                               ->prepareOrderProcess($request->all());

        return __processResponse($engineReaction, [], $engineReaction['data']);
    }

    /**
     * Prepare PayPal Order.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function preparePaypalOrder($orderUID)
    {
        $this->orderEngine->createPaypalOrder($orderUID);
    }

    /**
     * Thanks page.
     *
     * @param string $orderUID
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function thanksOnPayPalOrder(CommonPostRequest $paypalRequest)
    {
        if ((__ifIsset($paypalRequest) == false)
                or ($paypalRequest->has('invoice') == false)) {
            return 'invalid request';
        }

        if ($this->orderEngine->processThanksPayPalOrder(
                $paypalRequest->get('invoice')) == false) {
            return 'invalid request';
        }

        return $this->loadPublicView('order.user.thank-you',
                $paypalRequest->all());
    }

    /**
     * Thanks page.
     *
     * @return json response
     *---------------------------------------------------------------- */
    public function paymentCancel($orderToken)
    {
        // check if recent order
        $orderUid = NativeSession::pullIfHas('RECENT_PAYPAL_ORDER_'.$orderToken);

        if ($orderUid) {
            $this->orderEngine->updatePaymentFailed($orderUid);
        }

        return $this->loadPublicView('order.user.payment-cancel',
                                        compact('orderUid'));
    }

    /**
     * order coupon process.
     * 
     * @param string $code
     * @param float  $cartTotalPrice
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function applyCouponProcess(CommonPostRequest $request)
    {
        $processReaction = $this->orderEngine
                                ->processApplyCoupon($request->input('orderData.code'));

        // get engine reaction                      
        return __processResponse($processReaction, [
                    1 => __('Coupon apply successfully.'),
                    2 => __('Please Enter valid coupon code.'),
                    9 => __('Your Coupon not valid for this Order Amount.'),
                ], $processReaction['data']);
    }

    /**
     * check order.
     *---------------------------------------------------------------- */
    public function checkOrder()
    {
        if (!isLoggedIn()) {
            Session::put('redirect_intended', true);

            return __apiResponse([
                'success' => false,
                'error' => true,
                ]);
        }

        if (isActiveUser()) {
            Session::flash('invalidUserMessage', __('Invalid request please contact administrator.'));

            return __apiResponse([
                'success' => false,
                'error' => true,
                ]);
        }

        return __apiResponse([
                'success' => true,
                'error' => false,
            ]);
    }

    /**
     * View shopping cart order success page.
     *---------------------------------------------------------------- */
    public function orderSuccess()
    {
        $success = Session::get('successMessage');

        if (__isEmpty(Session::get('successMessage'))) {
            return redirect()->route('home.page');
        }

        return $this->loadPublicView('order.user.success-page', ['success' => $success]);
    }

    /**
     * My order list view.
     *---------------------------------------------------------------- */
    public function userOrdersList()
    {
        // Check if user is logged in 
        if (isActiveUser()) {
            return redirect()->route('user.login');
        }

        $breadCrumb = $this->orderEngine
                           ->breadcrumbGenerate('orders');

        // Load user list view
        return $this->loadPublicView('order.user.list', $breadCrumb['data']);
    }

    /**
     * Create a list of my-order view.
     *
     * @param number $status
     *---------------------------------------------------------------- */
    public function index($status)
    {
        $engineReaction = $this->orderEngine
                               ->prepareOrderList($status);

        $requireColumns = [

            'creation_date' => function ($key) use ($status) {
                return ($status == 1)
                    ? formatStoreDateTime($key['created_at'])
                    : formatStoreDateTime($key['updated_at']);
            },
            'formated_status' => function ($key) {

                return $this->findStatus($key['status']);
            },
            'get_order_details_Route' => function ($key) {
                    return route('my_order.details', ['orderUID' => $key['order_uid']]);
            },
            'formated_name' => function ($key) {
                return $key['fname'].' '.$key['lname'];
            },
            'invoiceDownloadURL' => function ($key) {
                return route('order.user.invoice.download', $key['order_uid']);
            },
            '_id',
            'status',
            'users_id',
            'order_uid',
            'payment_status',
            'fname',
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * return mathching status.
     *
     * @param int $ID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findStatus($ID)
    {
        $status = config('__tech.orders.status_codes');

        return $status[$ID];
    }

    /**
     * Order detail view.
     *
     * @param int $orderUID
     *
     * @return string
     *---------------------------------------------------------------- */
    public function orderDetail($orderUID)
    {
        // Check if user is active or not
        if (isActiveUser()) {
            return redirect()->route('user.login');
        }

        // Check if user logged in 
        if (!isLoggedIn()) {
            return redirect()->route('user.login');
        }

        $orderDetails = $this->orderEngine
                             ->getMyOrderDetails($orderUID);

        // If reaction code not exist then redirect on product page
        if ($orderDetails['reaction_code'] == 18) {
            return redirect()->route('home.page')
                        ->with([
                                'error' => true,
                                'message' => __('Requested order details not exist'),
                            ]);
        }

        JavaScript::put([
            'orderDetails' => $orderDetails,
        ]);

        // session have success message data then remove it
        if (NativeSession::has('orderSuccessMessage')) {
            NativeSession::remove('orderSuccessMessage');
        }

        $breadCrumb = $this->orderEngine
                           ->breadcrumbGenerate('order-details');

        return $this->loadPublicView('order.user.details', $breadCrumb['data']);
    }

    /**
     * Change address in order then get shipping, tax and coupon amount.
     *
     * @param int $countryCode
     *---------------------------------------------------------------- */
    public function changeAddressInOrder($addressID)
    {
        $processReaction = $this->orderEngine
                                  ->changeAddressInOrderDetails($addressID);

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * user Log detial dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function userLogDetails($orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareOrderLogDetailDiallog($orderID);

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * User Cancel order dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function cancelOrderSupportData($orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareCancelOrderData($orderID);

        return __processResponse($processReaction, [
                 2 => __('You are not authenticate to cancel order.'),
                18 => __('Order does not exist.'),
            ], null, true);
    }

    /**
     * User Cancel order dialog.
     *
     * @param int $orderID
     *---------------------------------------------------------------- */
    public function orderCancel(CommonPostRequest $request, $orderID)
    {
        $processReaction = $this->orderEngine
                                ->prepareCancelOrderProcess($request->all(), $orderID);

        return __processResponse($processReaction, [
                    1 => __('Order cancelled successfully.'),
                    18 => __('Order does not exist.'),
                    2 => __('Invalid request.'),
                    9 => __('Invalid request please contact administrator.'),
                ], $processReaction['data']);
    }

    /**
     * Download Invoice for order by user.
     *
     * @param number $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function invoiceDownload($orderID)
    {
        return  $this->orderEngine->processInvoiceDownload($orderID);
    }
}
