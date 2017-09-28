<?php
/*
* OrderPaymentsEngine.php - Main component file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart;

use App\Yantrana\Support\MailService;
use App\Yantrana\Components\ShoppingCart\Repositories\OrderPaymentsRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderPaymentsEngineBlueprint;
use Excel;
use App;

class OrderPaymentsEngine implements OrderPaymentsEngineBlueprint
{
    /**
     * @var OrderEngine
     */
    protected $orderEngine;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var OrderPaymentsRepository - OrderPayments Repository
     */
    protected $orderPaymentsRepository;

    /**
     * Constructor.
     *
     * @param OrderPaymentsRepository $orderPaymentsRepository - OrderPayments Repository
     *-----------------------------------------------------------------------*/
    public function __construct(OrderEngine  $orderEngine,
        MailService $mailService,
        OrderPaymentsRepository $orderPaymentsRepository)
    {
        $this->mailService = $mailService;
        $this->orderEngine = $orderEngine;
        $this->orderPaymentsRepository = $orderPaymentsRepository;
    }

    /**
     * Check that txn_id has not been previously processed.
     *
     * @param int $txnID - PayPal Txn ID
     * 
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function isTxnExists($txnID, $paymentMethod)
    {
        // check if this txn is already been processed
        return __isEmpty($this->orderPaymentsRepository->fetchByTxn($txnID, $paymentMethod)) ? false : true;
    }

    /**
     * Notify Payment Confirmation.
     *
     * @param int $txnID - PayPal Txn ID
     * 
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function notifyPaymentConfirmation($orderDetails, $ipnInformation)
    {
        $orderUid = $orderDetails['order_uid'];
        $customerId = $orderDetails['users_id'];

        // Get order details from database for latest placed order
        $updatedOrder = $this->orderEngine->prepareOrderDataForSendMail($orderUid);

        $messageData = [
            'orderData' => $updatedOrder,
            'oldPaymentStatus' => $orderDetails['payment_status'],
            'orderConfig' => config('__tech.address_type'),
            'orderDetailsUrl' => route('my_order.details', $orderUid),
        ];

        // if order payment status updated from pending
        if ($orderDetails['payment_status'] == 4) {
            $updatedPaymentSubject = 'Payment Confirmed';

            // notify customer
            $this->mailService->notifyCustomer($updatedPaymentSubject, 'order.customer-order',
                $messageData, $customerId);
            // notify store admin
            return $this->mailService->notifyAdmin($updatedPaymentSubject, 'order.customer-order', $messageData);
        }

        // notify customer
        $this->mailService->notifyCustomer('Your Order has been Submitted & Payment Confirmed',
                'order.customer-order', $messageData, $customerId);
        // notify store admin
        return $this->mailService->notifyAdmin('New Order Received & Payment Confirmed', 'order.customer-order', $messageData);
    }

    /**
     * Notify Payment Failure.
     *
     * @param int $txnID - PayPal Txn ID
     * 
     * @return mixed
     *-----------------------------------------------------------------------*/
    public function notifyPaymentFailure($requestResponse, $ipnData)
    {
        if ($requestResponse === 'ERR_IPN_FAILD'
                or $requestResponse === 'ERR_IPN_INVALID'
                or $requestResponse === 'ERR_IPN_NOTHING') {
            return false;
        }

        if ($requestResponse === 'ERR_IPN_ORDER_NOT_FOUND') {
            return false;
        }

        if (in_array('ERR_IPN_TXN_EXIST', $requestResponse)) {
            return false;
        }

        $orderUid = $ipnData['invoice'];

        // Get order details from database for latest placed order
        $orderDetails = $this->orderEngine->prepareOrderDataForSendMail($orderUid);
        $customerId = $orderDetails['userId'];

        $messageData = [
            'orderData' => $orderDetails,
            'orderConfig' => config('__tech.address_type'),
            'orderDetailsUrl' => route('my_order.details', $orderUid),
            'ipnData' => $ipnData,
            'requestResponse' => $requestResponse,
        ];

        // notify customer
        $this->mailService->notifyCustomer('Order Submitted but PayPal Payment not Completed', 'order.paypal-ipn-payment-failed',
            $messageData, $customerId);
        // notify admin
        return $this->mailService->notifyAdmin('New Order Received but PayPal Payment not Completed',
                    'order.paypal-ipn-payment-failed', $messageData);
    }

    /** 
     * Prepare order payment detail dialog data.
     *
     * @param int $orderID
     *-----------------------------------------------------------------------*/
    public function preparePaymentDetailsDialog($orderPaymentID)
    {
        $orderPaymentDetails = $this->orderPaymentsRepository
                                    ->fetchDetails($orderPaymentID);

        // Check if order payment detail is exist
        if (__isEmpty($orderPaymentDetails)) {
            return __engineReaction(18);
        }

        // Get payment method from config
        $paymentMethod = config('__tech.orders.payment_methods');

        $paymentData = [];

        $paymentData = [
            'currencyCode' => $orderPaymentDetails['paymentDetails']['currency_code'],
            'fee' => orderPriceFormat($orderPaymentDetails['paymentDetails']
                                    ['fee'],
                                    $orderPaymentDetails['paymentDetails']['currency_code']),
            'grossAmount' => orderPriceFormat($orderPaymentDetails['paymentDetails']
                                    ['gross_amount'],
                                    $orderPaymentDetails['paymentDetails']['currency_code']),
            'paymentMethod' => $paymentMethod[$orderPaymentDetails['paymentDetails']
                                    ['payment_method']],
            'txn' => $orderPaymentDetails['paymentDetails']['txn'],
            'type' => $orderPaymentDetails['paymentDetails']['type'],
            'formatedPaymentOn' => formatStoreDateTime($orderPaymentDetails['paymentDetails']
                                    ['created_at']),
            'rawData' => $orderPaymentDetails['rawData'],
        ];

        return __engineReaction(1, [
            'orderPaymentDetails' => $paymentData,
            ]);
    }

    /** Prepare order payment update detail dialog data
     * @param int $orderID
     *-----------------------------------------------------------------------*/
    public function prepareOrderPaymentUpdateDialog($orderID)
    {
        // Get order details
        $orderDetails = $this->orderPaymentsRepository->fetchOrderDetails($orderID);

        // Get payment method from config
        $config = config('__tech');
        $orderStatus = $config['orders']['status_codes'];
        $paymentMethod = $config['payment_methods_list'];

        // prepare order data
        $orderData = [
            'totalAmount' => $orderDetails['total_amount'],
            'orderID' => $orderDetails['_id'],
            'currencyCode' => $orderDetails['currency_code'],
            'paymentStatus' => $orderDetails['payment_status'],
            'orderStatus' => $orderStatus[$orderDetails['status']],
        ];

        return __engineReaction(1, [
            'orderDetails' => $orderData,
            'paymentMethod' => $paymentMethod,
        ]);
    }

    /** Process order payment update
     * @param int   $orderID
     * @param array $inputs
     *-----------------------------------------------------------------------*/
    public function processUpdateOrderPayment($inputs)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($inputs) {

            // update order status
            $response = $this->orderPaymentsRepository
                             ->updateOrder(2, $inputs['orderID']);

            $inputs['orderPaymentFee'] = null;

            // Check if order payment fee exist
            if (isset($inputs['fee']) and !__isEmpty($inputs['fee'])) {
                $inputs['orderPaymentFee'] = $inputs['fee'];
            }

            // Check if order payment store in DB
            if ($this->orderPaymentsRepository->storeOrderPayment($inputs)) {
                return $this->orderPaymentsRepository->transactionResponse(1, null, __('Order payment stored successfully.'));
            }

            return $this->orderPaymentsRepository->transactionResponse(2, null, __('Order payment not stored.'));

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare order refund dialog data.
     *
     * @param int $orderID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderRefundDialog($orderID)
    {
        $orderPaymentDetails = $this->orderPaymentsRepository
                                   ->fetchOrderPayments($orderID);

        // Check if order payment detail is exist
        if (__isEmpty($orderPaymentDetails)) {
            return __engineReaction(18);
        }

        // get list of payment list
        $paymentMethodList = config('__tech.payment_methods_list');

        // prepare array for refund dialog data
        $orderPaymentData = [
            'orderID' => $orderPaymentDetails['orders__id'],
            'txn' => $orderPaymentDetails['txn'],
            'currencyCode' => $orderPaymentDetails['currency_code'],
            'grossAmount' => orderPriceFormat($orderPaymentDetails['gross_amount'],
                                $orderPaymentDetails['currency_code']),
            'fee' => orderPriceFormat($orderPaymentDetails['fee'],
                                $orderPaymentDetails['currency_code']),
            'paymentOn' => formatStoreDateTime($orderPaymentDetails['created_at']),
        ];

        return __engineReaction(1, [
                'orderPaymentDetails' => $orderPaymentData,
                'paymentMethodList' => $paymentMethodList,
        ]);
    }

    /**
     * Process order payment refund.
     *
     * @param array $orderID
     * @param array $inputs
     *
     * @return reaction code
     *---------------------------------------------------------------- */
    public function processRefundOrderPayment($inputs, $orderID)
    {
        $reactionCode = $this->orderPaymentsRepository
                              ->processTransaction(function () use ($inputs, $orderID) {

            // Get order payment details
            $paymentDetails = $this->orderPaymentsRepository
                                   ->fetchOrderPayments($orderID);

            // update order status
            // 5 (Refunded) payment Status
            $response = $this->orderPaymentsRepository
                             ->updateOrder(5, $orderID);

            $inputData = [
                'txn' => $paymentDetails['txn'],
                'paymentMethod' => $inputs['paymentMethod'],
                'currencyCode' => $paymentDetails['currency_code'],
                'grossAmount' => $paymentDetails['gross_amount'],
                'orderPaymentFee' => $paymentDetails['fee'],
                'orderID' => $paymentDetails['orders__id'],
                'comment' => __ifIsset($inputs['description'], $inputs['description'], ''),
            ];

            // update order payment details
            $responseData = $this->orderPaymentsRepository
                                 ->updateOrderRefundPayment($inputData);

            // if order payment refunded successfully
            if ($responseData) {

                // Check if notify customer check box true or false
                if (__ifIsset($inputs['checkMail'])) {

                    // Check description exist
                    $additionalNotes = '';
                    if (!empty($inputs['description'])) {
                        $additionalNotes = $inputs['description'];
                    }

                    $order = $this->orderPaymentsRepository
                                   ->fetchUserID($orderID);

                    // order UID array for markup text 
                    $orderUID = [
                        '__orderUID__' => $inputs['orderUID'],
                    ];

                    // make a subject text for refund order mail
                    $subjectText = __('Payment Refund Process for __orderUID__ order.');

                    // get a markup string 
                    $subject = getTextMarkup($subjectText, $orderUID);

                    // description message for refund mail
                    $discriptionMarkup = __('Payment refund has been process for __orderUID__ order');

                    $messageData = [
                        'discription' => getTextMarkup($discriptionMarkup, $orderUID),
                        'additionalNotes' => $additionalNotes,
                    ];

                    // Notify customer about refund by email
                    $this->mailService->notifyCustomer($subject, 'order.order-refund', $messageData, $order['users_id']);
                }

                return $this->orderPaymentsRepository->transactionResponse(1, null, __('Order Refund Successfully.'));
            }

            return $this->orderPaymentsRepository->transactionResponse(14, null, __('Nothing Update'));

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare order payment list.
     *---------------------------------------------------------------- */
    public function preparePaymentOrderList($startDate, $endDate)
    {
        return $this->orderPaymentsRepository
                    ->fetchOrderPaymentList($startDate, $endDate);
    }

    /**
     * Process for Excel sheet download.
     *
     * @param $startDate
     * @param $endDate
     *---------------------------------------------------------------- */
    public function processExcelSheetDownload($startDate, $endDate)
    {
        $paymentDetails = $this->orderPaymentsRepository
                               ->fetchOrderPaymentDetails($startDate, $endDate);

        // Check if order payment details exist
        if (__isEmpty($paymentDetails)) {
            App:abort(404);
        }

        $paymentData = [];
        $totalOrderAmount = [];

        // get payment details and prepare array
        foreach ($paymentDetails as $key => $paymentDetail) {
            $totalOrderAmount[$key] = $paymentDetail['gross_amount'];

            $paymentData [] = [
                'orderUID' => $paymentDetail['order']['order_uid'],
                'txn' => $paymentDetail['txn'],
                'fee' => $paymentDetail['fee'],
                'paymentMethod' => getTitle($paymentDetail['payment_method'],
                                    '__tech.orders.payment_methods'),
                'totalAmt' => $paymentDetail['order']['total_amount'],
                'currencyCode' => $paymentDetail['currency_code'],
            ];
        }

        // Excel title, date and total amount data
        $excelData = [
            'excelFileName' => 'Payments-'.''.$startDate.'-'.$endDate,
            'title' => 'Payment Details'.' '.formatStoreDateTime(currentDateTime()),
            'startEndDate' => 'From'.' '.$startDate.' to '.$endDate,
            'total' => array_sum($totalOrderAmount),
        ];

        return Excel::create($excelData['excelFileName'], function ($excel) use ($excelData,
            $paymentData) {

            $excel->sheet('payments', function ($sheet) use ($excelData, $paymentData) {

                //merge cells
                $sheet->mergeCells('A1:F1');//merge for store name
                $sheet->mergeCells('A2:F2');//merge for title
                $sheet->mergeCells('A3:F3');//merge for start and end date

                // set styling for first 3 rows
                $sheet->cells('A1:F3', function ($cells) {
                    $cells->setAlignment('center');// alignment center
                    $cells->setFontSize(14);// font size
                    $cells->setFontWeight('bold');// bold text
                });

                // store name
                $sheet->row(1, [getStoreSettings('store_name')])->setHeight(1, 30);

                // current date and time
                $sheet->row(2, [$excelData['title']]);

                // set start And EndDate for excel sheet
                $sheet->row(3, [$excelData['startEndDate']]);

                // Heading column for excel sheet
                $sheet->row(4, [
                    'OrderUID',
                    'Transaction ID',
                    'Fee',
                    'Payment Method',
                    'Total Amount',
                    'Currency Code',
                ]);

                // count all order and set border for it
                $paymentCount = count($paymentData);
                $rowCount = 4 + $paymentCount;
                $cellRange = 'A1:F'.$rowCount;

                // set border 
                $sheet->setBorder($cellRange, 'thin');

                // set bold style to heading row
                $sheet->row(4, function ($row) {

                    $row->setFontWeight('bold');
                });

                // set right alignment for amount
                $feeCellRange = 'C5:C'.$rowCount;

                $sheet->cells($feeCellRange, function ($cells) {
                    $cells->setAlignment('right');//alignment center
                });

                $amountCellRange = 'E5:E'.$rowCount;

                $sheet->cells($amountCellRange, function ($cells) {
                    $cells->setAlignment('right');//alignment center
                });

                // calculate end of the row
                $rowID = $rowCount + 1;
                $selectedCell = 'A'.$rowID.':'.'E'.$rowID;
                $sheet->mergeCells($selectedCell);

                //set alignment of total order Amount
                $sheet->cells($selectedCell, function ($cells) {
                    $cells->setAlignment('right');//alignment center
                    $cells->setFontSize(14);//font size
                    $cells->setFontWeight('bold');//bold text
                });

                // Total orders Amount data
                $sheet->row($rowID, ['Total '.' '.$excelData['total']]);

                $sheet->fromArray($paymentData, null, 'A5', true, false);

            });

        })->export('xls');
    }
}
