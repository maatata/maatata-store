<?php
/*
* OrderRepository.php - Repository file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Components\ShoppingCart\Blueprints\OrderRepositoryBlueprint;
use App\Yantrana\Components\ShoppingCart\Models\OrderProduct as OrderProductModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderProductOptions as OrderProductOptionsModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderTax as OrderTaxModel;
use Auth;

class OrderRepository extends BaseRepository
                          implements OrderRepositoryBlueprint
{
    /**
     * @var OrderModel - Order Model
     */
    protected $orderModel;

    /**
     * @var OrderProductModel - OrderProduct Model
     */
    protected $orderProduct;

    /**
     * @var OrderProductOptions - OrderProductOptions Model
     */
    protected $orderProductOption;

    /**
     * Constructor.
     *
     * @param OrderModel $orderModel - Order Model
     *-----------------------------------------------------------------------*/
    public function __construct(OrderModel $orderModel,
        OrderProductModel $orderProduct,
        OrderProductOptionsModel $orderProductOption,
        OrderTaxModel $orderTax)
    {
        $this->orderModel = $orderModel;
        $this->orderProduct = $orderProduct;
        $this->orderProductOption = $orderProductOption;
        $this->orderTax = $orderTax;
    }

    /**
     * Fetch order details using id or _uid.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetch($id)
    {
        if (is_int($id)) { // if the integer treat as db id
            return $this->orderModel->find($id);
        }
        // if not the integer treat as db uid
        return $this->orderModel->where('order_uid', $id)->first();
    }
    /**
     * Store new cart order using provided data.
     *
     * @param array $inputs
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function orderProcess($inputs)
    {
        $paymentMethod = $inputs['payment_method'];

        $order = new $this->orderModel();

        $dataToStore = [
            'type',
            'status',
            'payment_method',
            'payment_status',
            'users_id' => Auth::id(),
            'order_uid',
            'name',
            'coupons__id',
            'currency_code',
            'addresses_id',
            'addresses_id1',
            'shipping_amount',
            'discount_amount',
            'total_amount',
            'business_email' => $paymentMethod == 1
                                    ? getStoreSettings('paypal_email')
                                    : getStoreSettings('business_email'),
        ];

        if ($order->assignInputsAndSave($inputs, $dataToStore)) {
            $orderID = $order->_id;

            $addOrderLog = [
                   'orders__id' => $orderID,
                   'description' => 'New Order Submitted',
               ];

            orderLog($addOrderLog);

            if (!__isEmpty($inputs['taxses']) and is_array($inputs['taxses'])) {
                $taxesData = [];

                foreach ($inputs['taxses'] as $tax) {
                    $taxesData[] = [
                        'tax__id' => $tax['id'],
                        'orders__id' => $orderID,
                        'amount' => $this->getFloatFormattedValue($tax['amount']),
                    ];
                }

                // If order Taxes Not Saved Then Return False
                if (!$this->orderTax->prepareAndInsert($taxesData)) {
                    return false;
                }
            }

            if (!__isEmpty($inputs['cartItems'])) {
                foreach ($inputs['cartItems'] as $cartItem) {
                    $orderProduct = new $this->orderProduct();
                    $orderProduct->products_id = $cartItem['id'];
                    $orderProduct->orders__id = $orderID;
                    $orderProduct->quantity = $cartItem['qty'];
                    $orderProduct->name = $cartItem['name'];
                    $orderProduct->custom_product_id = $cartItem['customProductId'];
                    $orderProduct->price = $this->getFloatFormattedValue($cartItem['price']);
                    $orderProduct->status = 1;

                    if ($orderProduct->save()) {
                        if (__ifIsset($cartItem['options']) and is_array($cartItem['options'])) {
                            $optionsData = [];

                            $orderProductId = $orderProduct->_id;

                            foreach ($cartItem['options'] as $option) {
                                $optionsData[] = [
                                    'ordered_products__id' => $orderProductId,
                                    'name' => $option['optionName'],
                                    'value_name' => $option['valueName'],
                                    'addon_price' => $this->getFloatFormattedValue($option['addonPrice']),
                                ];
                            }

                            // If Order Product Options Saved Then Return False
                            if (!$this->orderProductOption->prepareAndInsert($optionsData)) {
                                return false;
                            }
                        }
                    }
                }
            }

            return $orderData = [
                'created_at' => $order->created_at,
                'orderID' => $orderID,
                ];
        }
    }

    /**
     * Get 2 Place Decimal value for float Amount.
     *
     * @param number $Amount
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function getFloatFormattedValue($Amount)
    {
        return round($Amount, 2);
    }

    /**
     * fetch list of orders.
     *
     * @param number $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchOrdersForList($status)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => '_id',
                'creation_date' => ($status == 1) ? 'created_at' : 'updated_at',
                'name' => 'users_id',
            ],
            'searchable' => [
                '_id' => '_id',
                'order_uid' => 'orders.order_uid',
                'creation_date' => ($status == 1) ? 'orders.created_at' : 'orders.updated_at',
                'users_id' => 'orders.users_id',
                'fname' => 'users.fname',
                'lname' => 'users.lname',
            ],
        ];

        if ($status == 1) {
            $query = $this->orderModel
                          ->whereNotIn('orders.status', [3, 6, 9, 10]);
        } elseif ($status == 3) {
            $query = $this->orderModel
                          ->whereIn('orders.status', [3, 9, 10]);
        } else {
            $query = $this->orderModel
                          ->where('orders.status', $status);
        }

        return $query->where('orders.users_id', getUserID())
                     ->join('users', 'orders.users_id', '=', 'users.id')
                     ->select(
                        'orders._id',
                        'orders.created_at',
                        'orders.updated_at',
                        'orders.status as status',
                        'orders.type',
                        'orders.payment_method',
                        'orders.addresses_id',
                        'orders.addresses_id1',
                        'orders.currency_code',
                        'orders.users_id as users_id',
                        'orders.order_uid',
                        'orders.payment_status',
                        'users.id as user_id',
                        'users.fname as fname',
                        'users.lname as lname'
                    )->dataTables($dataTableConfig)->toArray();
    }

    /**
     * fetch order details.
     *
     * @param number $orderUID
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function fetchOrderDetails($id)
    {
        $query = $this->orderModel
                      ->with('user',
                        'address',
                        'address1',
                        'orderProduct',
                        'coupon',
                        'orderTaxes'
                      );

        if (is_int($id)) { // if the integer treat as db id
            $order = $query->find($id);
        } else { // if not the integer treat as db uid
            $order = $query->where('order_uid', $id)->first();
        }

        unset($query);

        return __ifIsset($order, function ($order) {
            return $order->toArray();
        }, []);
    }

    /**
     * get order UID and user name.
     *
     * @param number $orderID
     *
     * @return eloqunr collection object
     *---------------------------------------------------------------- */
    public function getOrderData($orderID)
    {
        return $this->orderModel
                    ->where('_id', $orderID)
                    ->where(function ($query) {

                        // if is not admin then
                        if (isAdmin() === false) {
                            return $query->where('users_id', getUserID());
                        }

                    })->select('_id', 'order_uid', 'name', 'status')
                    ->first();
    }

    /**
     * fetch Order.
     *
     * @param number $orderID
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function fetchOrder($orderID)
    {
        return $this->orderModel
                    ->where(function ($query) {

                        // if is not admin then
                        if (isAdmin() === false) {
                            return $query->where('users_id', getUserID());
                        }

                    })
                    ->where('_id', $orderID)
                    ->first();
    }

    /**
     * update order Status.
     *
     * @param object $order
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateOrderStatus($order, $statusId)
    {
        $orderStatusString = $this->getOrderStatus($statusId);

        orderLog($order->_id, 'Order Status for '.$order->_id.' has been updated to '.$orderStatusString);

        return $order->modelUpdate(['status' => $statusId]);
    }

    /**
     * update order payment status.
     *
     * @param object $order
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateOrderPaymentStatus($order, $statusId)
    {
        $paymentStatusString = $this->getPaymentStatus($statusId);

        orderLog($order->_id, 'Payment Status for Order '.$order->_id.' has been updated to '.$paymentStatusString);

        return $order->modelUpdate(['payment_status' => $statusId]);
    }

    /**
     * update order & order payment status.
     *
     * @param object $order
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateOrderAndPaymentStatus($orderUid, $orderStatus, $paymentStatus)
    {
        // Get order Id by order UID
        $orderId = $this->orderModel
                        ->where('order_uid', $orderUid)
                        ->first(['_id']);

        // Get string for order and payment status
        $orderStatusString = $this->getOrderStatus($orderStatus);
        $paymentStatusString = $this->getPaymentStatus($paymentStatus);

        // Maintain order log for order and payment status changed.
        orderLog($orderId['_id'], 'Order Status Changed to '.$orderStatusString.' and Payment status changed to '.$paymentStatusString);

        return $this->orderModel->where('order_uid', $orderUid)->update([
                'status' => $orderStatus,
                'payment_status' => $paymentStatus,
            ]);
    }

    /**
     * Get formatted order status from config.
     *
     * @param object $orderStatus
     *
     * @return string
     *---------------------------------------------------------------- */
    protected function getOrderStatus($orderStatus)
    {
        // Get order status from config.
        $orderStatusConfig = config('__tech.orders.status_codes');

        return $orderStatusConfig[$orderStatus];
    }

    /**
     * Get formatted payment status from config.
     *
     * @param object $paymentStatus
     *
     * @return string
     *---------------------------------------------------------------- */
    protected function getPaymentStatus($paymentStatus)
    {
        // Get payment status from config.
        $paymentStatusConfig = config('__tech.orders.payment_status');

        return $paymentStatusConfig[$paymentStatus];
    }

    /**
     * fetch order taxes data.
     *
     * @param int $order
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderTaxDetails($orderId)
    {
        return $this->orderTax
                    ->where('orders__id', $orderId)
                    ->select('tax__id', 'amount')
                    ->get();
    }

    /**
     * fetch order related to specific user.
     *
     * @param int $userID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderByUserID($userID)
    {
        $orderCount = $this->orderModel
                            ->where('users_id', $userID)
                            ->count();

        $lastOrder = $this->orderModel
                            ->where('users_id', $userID)
                            ->orderBy('created_at', 'desc')
                            ->first(['order_uid', 'created_at']);

        return $orderDetails = [
                'orderCount' => $orderCount,
                'lastOrder' => $lastOrder,
            ];
    }
}
