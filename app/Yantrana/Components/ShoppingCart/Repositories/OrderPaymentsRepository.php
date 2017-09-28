<?php
/*
* OrderPaymentsRepository.php - Repository file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\ShoppingCart\Models\OrderPayments as OrderPaymentsModel;
use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderPaymentsRepositoryBlueprint;
use DB;

class OrderPaymentsRepository extends BaseRepository
                          implements OrderPaymentsRepositoryBlueprint
{
    /**
     * @var OrderPaymentsModel - OrderPayments Model
     */
    protected $orderPaymentsModel;

    /**
     * @var OrderModel orderModel - Order Model
     */
    protected $orderModel;

    /**
     * Constructor.
     *
     * @param OrderPaymentsModel $orderPaymentsModel - OrderPayments Model
     *-----------------------------------------------------------------------*/
    public function __construct(OrderPaymentsModel $orderPaymentsModel,
                        OrderModel $orderModel)
    {
        $this->orderPaymentsModel = $orderPaymentsModel;
        $this->orderModel = $orderModel;
    }

    /**
     * Store new coupon using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storePayPalPayment($orderId, $inputData)
    {
        $orderPayment = new $this->orderPaymentsModel();
        $orderPayment->payment_method = 1; // PayPal
        $orderPayment->type = 1; // Credit
        $orderPayment->txn = $inputData['txn_id'];
        $orderPayment->currency_code = $inputData['mc_currency'];
        $orderPayment->gross_amount = $inputData['mc_gross'];
        $orderPayment->fee = $inputData['mc_fee'];
        $orderPayment->orders__id = $orderId;
        $orderPayment->raw_data = json_encode($inputData);

        // Save Payment Information
        if ($orderPayment->save()) {
            orderLog($orderId, 'Payment for Order '.$orderId.' has been recorded from PayPal.');

            return true;
        }

        return false;
    }

    /**
     * Fetch Payment by TXN.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchByTxn($txnId, $paymentMethod)
    {
        return $this->orderPaymentsModel->where(['txn' => $txnId, 'payment_method' => $paymentMethod])->first();
    }

    /**
     * Fetch Payment Details.
     *
     * @param array $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchDetails($orderPaymentID)
    {
        $paymentDetails = $this->orderPaymentsModel
                                 ->where('_id', $orderPaymentID)
                                 ->first([
                                    'currency_code',
                                    'fee',
                                    'gross_amount',
                                    'payment_method',
                                    'raw_data',
                                    'txn',
                                    'type',
                                    'raw_data',
                                    'created_at',
                                    ]);

        $paymentData = [];

        // Check if payment detail exist
        if (!__isEmpty($paymentDetails)) {
            $paymentData = [
                    'paymentDetails' => $paymentDetails,
                    'rawData' => json_decode($paymentDetails['raw_data']),
            ];
        }

        return $paymentData;
    }

    /**
     * Fetch Order Payment Details.
     *
     * @param array $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrderPayments($orderID)
    {
        return $this->orderPaymentsModel
                    ->where('orders__id', $orderID)
                    ->first();
    }

    /**
     * Fetch order details.
     *
     * @param int $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrderDetails($orderID)
    {
        return $this->orderModel
                      ->where('_id', $orderID)
                      ->select(
                           '_id',
                           'type',
                           'payment_method',
                           'total_amount',
                           'currency_code',
                           'status',
                           'payment_status'
                       )
                      ->first();
    }

    /**
     * Update order payment.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function updateOrderRefundPayment($inputs)
    {
        $orderPayment = new $this->orderPaymentsModel();

        $dataToStore = [
            'txn',
            'type' => 2, // Refund
            'payment_method' => $inputs['paymentMethod'],
            'currency_code' => $inputs['currencyCode'],
            'gross_amount' => $inputs['grossAmount'],
            'fee' => $inputs['orderPaymentFee'],
            'orders__id' => $inputs['orderID'],
            'raw_data' => json_encode(['comment' => $inputs['comment']]),
        ];

        // Check if data store or not
        if ($orderPayment->assignInputsAndSave($inputs, $dataToStore)) {

            // Maintain activity log
            orderLog($orderPayment->orders__id, 'ID of '.$orderPayment->_id.' order refund payment updated.');

            return true;
        }

        return false;
    }

    /**
     * Store order payment if payment details not exist.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function storeOrderPayment($inputs)
    {
        $orderPayment = new $this->orderPaymentsModel();

        $dataToStore = [
            'txn',
            'type' => 1, // Deposit
            'payment_method' => $inputs['paymentMethod'],
            'currency_code' => $inputs['currencyCode'],
            'gross_amount' => $inputs['totalAmount'],
            'fee' => $inputs['orderPaymentFee'],
            'orders__id' => $inputs['orderID'],
            'raw_data' => json_encode(['comment' => $inputs['comment']]),
        ];

        // Check if data store or not
        if ($orderPayment->assignInputsAndSave($inputs, $dataToStore)) {

            // Maintain activity log
            orderLog($orderPayment->orders__id, 'Payment for Order '.$orderPayment->orders__id.' has been recorded.');

            return true;
        }

        return false;
    }

    /** Fetch order and update status
     * @param int $paymentStatus
     * @param int $orderID
     * 
     * @return number
     *-----------------------------------------------------------------------*/
    public function updateOrder($paymentStatus, $orderID)
    {
        $order = $this->orderModel
                      ->where('_id', $orderID)
                      ->first();

        if ($order->modelUpdate(['payment_status' => $paymentStatus])) {
            return true;
        }

        return false;
    }

    /** Fetch order payments and order
     * @return array
     *-----------------------------------------------------------------------*/
    public function fetchOrderPaymentList($startDate, $endDate)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => '_id',
                'totalAmount' => 'total_amount',
                'formattedFee' => 'fee',
                'formattedPaymentMethod' => 'payment_method',
                'formattedPaymentOn' => 'created_at',
            ],
            'searchable' => [
                'order_uid' => 'orders.order_uid',
                'total_amount' => 'orders.total_amount',
                'txn' => 'order_payments.txn',
                'fee' => 'order_payments.fee',
                'payment_method' => 'order_payments.payment_method',
                'created_at' => 'order_payments.created_at',
                ],
        ];

        return $this->orderPaymentsModel
                    ->whereBetween(DB::raw('DATE(order_payments.created_at)'), [$startDate, $endDate])
                    ->join('orders', 'order_payments.orders__id', '=', 'orders._id')
                    ->select(
                        'orders._id',
                        'orders.currency_code',
                        'order_payments._id as order_payment_id',
                        'order_payments.txn',
                        'order_payments.payment_method',
                        'order_payments.gross_amount',
                        'order_payments.fee',
                        'order_payments.orders__id',
                        'order_payments.created_at',
                        'order_payments.type',
                        'orders.order_uid',
                        'orders.total_amount'
                    )->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Fetch order payment details for excel sheet.
     *
     * @param $startDate
     * @param $endDate
     *---------------------------------------------------------------- */
    public function fetchOrderPaymentDetails($startDate, $endDate)
    {
        return $this->orderPaymentsModel
                    ->with('order')
                    ->whereBetween(DB::raw('DATE(order_payments.created_at)'), [$startDate, $endDate])
                    ->get();
    }

    /**
     * Fetch user ID from Order table.
     *
     * @param $orderID
     *
     * @return int
     *---------------------------------------------------------------- */
    public function fetchUserID($orderID)
    {
        return $this->orderModel
                    ->where('_id', $orderID)
                    ->first(['users_id']);
    }
}
