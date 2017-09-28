<?php
/*
* ManageOrderController.php - Controller file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\ShoppingCart\Requests\OrderUpdateRequest;
use App\Yantrana\Components\ShoppingCart\Requests\OrderCancelRequest;
use App\Yantrana\Components\ShoppingCart\Requests\OrderUserMailRequest;
use App\Yantrana\Components\ShoppingCart\ManageOrderEngine;
use Illuminate\Http\Request;

class ManageOrderController extends BaseController
{
    /**
     * @var ManageOrderEngine - ManageOrder Engine
     */
    protected $manageOrderEngine;

    /**
     * Constructor.
     *
     * @param ManageOrderEngine $manageOrderEngine - ManageOrder Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageOrderEngine $manageOrderEngine)
    {
        $this->manageOrderEngine = $manageOrderEngine;
    }

    /**
     * Handle product order request.
     *
     * @param number $categoryID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function index($status, $userID)
    {
        $engineReaction = $this->manageOrderEngine
                                ->prepareOrderList($status, $userID);

        $requireColumns = [

            'creation_date' => function ($key) use ($status) {
                return ($status == 1)
                    ? formatStoreDateTime($key['created_at'])
                    : formatStoreDateTime($key['updated_at']);
            },
            'formated_status' => function ($key) {

                return $this->findStatus($key['status']);

            },
            'formated_name' => function ($key) {
                return $key['fname'].' '.$key['lname'];
            },
            'paymentMethod' => function ($key) {
                return $this->findPaymentMethod($key['payment_method']);

            },
            'paymentStatus' => function ($key) {
                return $this->findPaymentStatus($key['payment_status']);

            },
            'totalAmount' => function ($key) {
                return orderPriceFormat($key['total_amount'], $key['currency_code']);
            },
            'orderPaymentID' => function ($key) {
                return $key['order_payment']['_id'];
            },
            'pfdDownloadURL' => function ($key) {
                return route('report.pdf_download', $key['_id']);
            },
            '_id',
            'status',
            'users_id',
            'order_uid',
            'fname',
            'payment_status',
            'type',
            'payment_method',
            'total_amount', /*
            'order_payment_id'*/
        ];

        $userFullName = '';

        // Check if user id exist
        if (!__isEmpty($userID)) {
            $getUserData = $this->manageOrderEngine
                                ->getUserDetails($userID);

            $userFullName = $getUserData['fname'].' '.$getUserData['lname'];
        }

        return __dataTable($engineReaction, $requireColumns, [
            'userFullName' => $userFullName,
            ]);
    }

    /**
     * find payment type.
     *
     * @param int $type
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findPaymentMethod($type)
    {
        if (__isEmpty($type)) {
            return '';
        }

        $orderType = config('__tech.orders.payment_methods');

        return $orderType[$type];
    }

    /**
     * find payment status.
     *
     * @param int $type
     *
     * @return string
     *---------------------------------------------------------------- */
    public function findPaymentStatus($statusID)
    {
        if (__isEmpty($statusID)) {
            return '';
        }

        $orderType = config('__tech.orders.payment_status');

        return $orderType[$statusID];
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
     * Get orders data of user.
     * 
     * @param int $orderID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function getUsersOrder($status, $userID)
    {
        $engineReaction = $this->manageOrderEngine
                                ->prepareOrderList($status, $userID);
    }

    /**
     * update order support data.
     * 
     * @param int $orderID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function orderUpdateSupportData($orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageOrderEngine
                                ->prepareOrderData($orderID);

       // get engine reaction                      
        return __processResponse($processReaction, [
                    18 => __('Order does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * update order request.
     * 
     * @param int                      $orderID
     * @param array OrderUpdateRequest $request
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function orderUpdate($orderID, OrderUpdateRequest $request)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageOrderEngine
                                ->processUpdateOrder($orderID, $request->all());

       // get engine reaction                      
        return __processResponse($processReaction, [
                    1 => __('Order status updated successfully.'),
                    2 => __('Your Order payment not received yet.'),
                    18 => __('Order does not exist.'),
                    14 => __('Nothing updated.'),
                ], $processReaction['data']);
    }

    /**
     * cancel order support data.
     * 
     * @param int $orderID
     * 
     * @return json object
     *---------------------------------------------------------------- */

    /*public function orderCancelSupportData($orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageOrderEngine
                                ->prepareOrderData($orderID);

       // get engine reaction                      
        return __processResponse($processReaction, [
                    18  => __('Order does not exist.')
                ], $processReaction['data']);
    }*/

    /**
     * order cancel request.
     * 
     * @param int                      $orderID
     * @param array OrderCancelRequest $request
     * 
     * @return json object
     *---------------------------------------------------------------- */

    /*public function orderCancel($orderID, OrderCancelRequest $request)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }
        
        $processReaction = $this->manageOrderEngine
                                ->processCancelOrder($orderID, $request->all());

       // get engine reaction                      
        return __processResponse($processReaction, [
                    1   => __('Order status updated successfully.'),
                    18  => __('Order does not exist.'),
                    14  => __('Nothing updated.'),
                ], $processReaction['data']);
    }*/

    /**
     * order dialog.
     *
     * @param int $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function orderDetailsSupportData($orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageOrderEngine
                                ->prepareOrderDetailsDialogData($orderID);

       // get engine reaction                      
        return __processResponse($processReaction, [
                    18 => __('Order does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * order log dialog.
     *
     * @param $orderID
     *---------------------------------------------------------------- */
    public function orderLogDetailsSupportData($orderID)
    {
        if (empty($orderID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->manageOrderEngine
                                ->prepareOrdersLogDialogData($orderID);

       // get engine reaction                      
        return __processResponse($processReaction, [
                    18 => __('Order does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Get user details of order.
     *
     * @param $orderID
     *---------------------------------------------------------------- */
    public function getUserDetails($orderID)
    {
        $processReaction = $this->manageOrderEngine
                                ->prepareOrdersUserData($orderID);

        return __processResponse($processReaction, [
                    18 => __('Order does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Process to send mail to user.
     *
     * @param object OrderUserMailRequest $request
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareContactUser(OrderUserMailRequest $request)
    {
        $processReaction = $this->manageOrderEngine
                                ->processContactUser($request->all());

        // get engine reaction                      
        return __processResponse($processReaction, [
                    1 => __('Mail send to user successfully.'),
                    2 => __('Please check email and orderID.'),
                    18 => __('Order does not exist'),
                ], $processReaction['data']);
    }
}
