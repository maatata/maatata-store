<?php
/*
* ManageOrderEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\ManageOrderEngineBlueprint;
use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\User\AddressEngine;
use Auth;

class ManageOrderEngine implements ManageOrderEngineBlueprint
{
    /**
     * @var ManageOrderRepository - ManageOrder Repository
     */
    protected $manageOrderRepository;

    /**
     * @var AddressRepository - Address Repository
     */
    protected $addressRepository;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var CouponRepository
     */
    protected $couponRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var AddressEngine - Address Repository
     */
    protected $addressEngine;

    /**
     * @var OrderEngine
     */
    protected $orderEngine;

    /**
     * Constructor.
     *
     * @param ManageOrderRepository $manageOrderRepository - ManageOrder Repository
     * @param AddressEngine         $addressEngine         - Address Engine
     *-----------------------------------------------------------------------*/
    public function __construct(
                ManageOrderRepository $manageOrderRepository,
                AddressRepository $addressRepository,
                MailService $mailService,
                UserRepository $userRepository,
                ShippingRepository $shippingRepository,
                TaxRepository $taxRepository,
                CouponRepository $couponRepository,
                SupportRepository $supportRepository,
                AddressEngine $addressEngine,
                OrderEngine  $orderEngine
            ) {
        $this->manageOrderRepository = $manageOrderRepository;
        $this->addressRepository = $addressRepository;
        $this->mailService = $mailService;
        $this->userRepository = $userRepository;
        $this->shippingRepository = $shippingRepository;
        $this->taxRepository = $taxRepository;
        $this->couponRepository = $couponRepository;
        $this->supportRepository = $supportRepository;
        $this->addressEngine = $addressEngine;
        $this->orderEngine = $orderEngine;
    }

    /**
     * Prepare order list.
     *---------------------------------------------------------------- */
    public function prepareOrderList($status, $userID)
    {
        return $this->manageOrderRepository
                        ->fetchOrdersForList($status, $userID);
    }

    /**
     * Get user details.
     * 
     * @param number $userID
     *---------------------------------------------------------------- */
    public function getUserDetails($userID)
    {
        return $this->userRepository
                    ->fetchUserFullName($userID);
    }

    /**
     * get order data.
     * 
     * @param int $orderID
     * 
     * @return object
     *---------------------------------------------------------------- */
    public function prepareOrderData($orderID)
    {
        $order = $this->manageOrderRepository->fetchById($orderID);

        //check order exist
        if (__isEmpty($order)) {
            return __engineReaction(18);
        }

        $statusCode = [];

        // Get order status from config
        $configOrder = config('__tech.orders');
        $orderStatus = $configOrder['status_codes'];
        $orderPaymentStatus = $configOrder['payment_status'];

        // create new array list of status and
        // neglect 1 (NEW) and current status 
        foreach ($orderStatus as $key => $status) {
            if ($key != 1 and $key != $order->status) {
                $statusCode [] = [
                    'id' => $key,
                    'name' => $status,
                ];
            }
        }

        // prepare order data array 
        $orderData = [
            '_id' => $order->_id,
            'orderUID' => $order->order_uid,
            'name' => $order->name,
            'status' => $order->status,
            'statusName' => $orderStatus[$order->status],
            'statusCode' => $statusCode,
            'paymentStatus' => $order->payment_status,
            'currentPaymentStatus' => $orderPaymentStatus[$order->payment_status],
        ];

        return __engineReaction(1, ['order' => $orderData]);
    }

    /**
     * prepare for update order.
     * 
     * @param int   $orderID
     * @param array $input
     * 
     * @return response
     *---------------------------------------------------------------- */
    public function processUpdateOrder($orderID, $input)
    {
        // Get order detail
        $order = $this->getOrderDetailsForMail($orderID);

        // Check if order exist
        if (empty($order)) {
            return __engineReaction(18);
        }

        // Get old status from order for maintain order Log
        $input['oldStatus'] = $order['orderStatus'];

        // Check if user select order status complete and payment status is not
        // completed then show message
        // 6 => Completed (Order Status)
        // 2 => Completed (Payment Status)
        if ($input['status'] == 6 and $order['paymentStatus'] != 2) {
            return __engineReaction(2);
        }

        $response = $this->manageOrderRepository
                         ->updateOrder($orderID, $input);

        // if order updated successfully
        if ($response) {

            // Check if check mail exist and neglect 
            // 1 (New), 
            // 8 (Cancellation Request Received) and
            // 9 (User Cancelled) status code
            if (!empty($input['checkMail'])) {
                if (($input['status'] != 1)
                or ($input['status'] != 8)
                or ($input['status'] != 9)) {

                    // Check discription exist
                    $description = '';
                    if (!empty($input['description'])) {
                        $description = $input['description'];
                    }

                    // Get Order UID
                    $orderUID = $order['orderUID'];

                    // Get email subject
                    $mailMessages = $this->createMailSubjectAndMessage(
                                                            $orderUID,
                                                            $input['status']
                                                            );
                    // get order discription message
                    $order['descriptionMessage'] = $mailMessages['descriptionMessage'];

                    // email view
                    $mailView = 'order.order-complete';

                       // Prepare mail data array for sending email
                    $mailData = [
                        'name' => $order['fullName'],
                        'orderData' => $order,
                        'description' => $description,
                         'orderDetailsUrl' => route('my_order.details', $orderUID),
                    ];

                    // send notification mail to user and admin
                    $this->notifyByMail($mailMessages['orderSubjectMessage'],
                                        $mailView,
                                        $mailData,
                                        $order['email']
                                        );
                }
            }

            // store order log data
            $orderLogData = $this->orderLogData($orderID, $input);

            orderLog($orderLogData);

            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Create subject and message for email.
     * 
     * @param array $orderID
     * @param array $status
     * 
     * @return object
     *---------------------------------------------------------------- */
    protected function createMailSubjectAndMessage($orderUID, $status)
    {
        // Get order status from config
        $orderStatus = config('__tech.orders.status_codes');

        $messageData = [
                '__orderId__' => $orderUID,
                '__orderStatus__' => $orderStatus[$status],
            ];

            // Get order subject 
            $orderSubjectMessage = __('Your order __orderId__ has been __orderStatus__.', $messageData);

            // Get order discription message for user
            $descriptionMessage = __('Your order __orderId__ status has been changed to  <strong> __orderStatus__. </strong>', $messageData);

        return [
                'orderSubjectMessage' => $orderSubjectMessage,
                'descriptionMessage' => $descriptionMessage,
            ];
    }

    /**
     * Send mail to user and admin with message.
     * 
     * @param string $message
     * @param string $view
     * @param array  $messageData
     * 
     * @return object
     *---------------------------------------------------------------- */
    protected function notifyByMail($message, $view, $messageData, $userID)
    {
        // sent mail to user
        $this->mailService->notifyCustomer($message, $view, $messageData, $userID);
    }

    /**
     * prepare Cart Orders dialog.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderDetailsDialogData($orderID)
    {
        $orderDetails = $this->orderEngine
                              ->prepareForMyOrderDetails((int) $orderID);

        // Check if order is exist
        if (__isEmpty($orderDetails)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, [
            'orderDetails' => $orderDetails,
            ]);
    }

    /**
     * prepare Cart Orders dialog.
     *
     * @param $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrdersLogDialogData($orderID)
    {
        $cartOrder = $this->manageOrderRepository->fetchById($orderID);

        // Get order log data
        $orderLog = getOrderLogFormattedData($orderID);

        //check order exist
        if (__isEmpty($orderLog)) {
            return __engineReaction(18);
        }

        $orders = [];

        // Check if cart order exist 
        // prepare orders array for order log.         
        if (!__isEmpty($cartOrder)) {
            $orders = [
                '_id' => $cartOrder->order_uid,
                'created_at' => formatDateTime($cartOrder->created_at),
            ];
        }

        return __engineReaction(1, [
                'cartOrder' => $orders,
                'orderLog' => $orderLog,
            ]);
    }

    /**
     * prepare for order log data.
     * 
     * @param int   $orderID
     * @param array $input
     * 
     * @return response
     *---------------------------------------------------------------- */
    protected function orderLogData($orderID, $input = [])
    {
        // default status is New
        $newStatus = 1;

        // Check if order status exist
        if (isset($input['status']) and !__isEmpty($input['status'])) {
            $newStatus = $input['status'];
        }

        // Assign new and old status string
        $newStatusString = getTitle($newStatus, '__tech.orders.status_codes');
        $oldStatusString = $input['oldStatus'];

        return [
               'orders__id' => $orderID,
               'description' => "Order status updated from $oldStatusString to $newStatusString",
               'role' => Auth::user()->fname.' '.Auth::user()->lname,
           ];
    }

    /**
     * Get order Details for email when status changed.
     *
     * @param $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    protected function getOrderDetailsForMail($orderID)
    {
        return $this->orderEngine
                    ->prepareOrderDataForSendMail((int) $orderID);
    }

    /**
     * Prepare data for email.
     *
     * @param $orderID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function prepareOrdersUserData($orderID)
    {
        $orderDetail = $this->manageOrderRepository
                            ->fetchUserDetailByOrderIDs($orderID);

        // Check if order details exist                  
        if (__isEmpty($orderDetail)) {
            __engineReaction(18);
        }

        // prepare data for mail                  
        $mailData = [
            'id' => $orderDetail['_id'],
            'orderUID' => $orderDetail['order_uid'],
            'email' => $orderDetail['user']['email'],
            'fullname' => $orderDetail['name'],
        ];

        return __engineReaction(1, $mailData);
    }

    /**
     * Process Send mail to user.
     *
     * @param array $input
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function processContactUser($input)
    {
        // get email ID and order UID
        $email = $input['email'];
        $orderUID = $input['orderUID'];

        $orderDetail = $this->manageOrderRepository
                            ->fetchUserDetailByOrderIDs($input['id']);

        // Check if order is exist
        if (__isEmpty($orderDetail)) {
            return __engineReaction(18);
        }

        if ($email != $orderDetail['user']['email']
            and $orderUID != $orderDetail['order_uid']) {
            return __engineReaction(2);
        }

        $fullName = 'Hi '.$input['fullName'].',';
        $subject = $input['subject'].' '.$orderUID;

        $mailData = [
            'emailMessage' => $input['message'],
            'fullName' => $fullName,
            'orderUID' => $orderUID,
        ];

        // send notification mail to user
        $this->notifyByMail($subject,
                                    'order.customer-email',
                                    $mailData,
                                    $email
                                    );

        return __engineReaction(1);
    }
}
