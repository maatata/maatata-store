<?php
/*
* ManageOrderRepository.php - Repository file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\ShoppingCart\Blueprints\ManageOrderRepositoryBlueprint;
use App\Yantrana\Components\ShoppingCart\Models\OrderTax as OrderTaxModel;
use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderLog as OrderLogModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderProduct as OrderProductModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderProductOptions as OrderProductOptionsModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderPayments as OrderPaymentsModel;
use DB;
use Carbon\Carbon;

class ManageOrderRepository extends BaseRepository
                          implements ManageOrderRepositoryBlueprint
{
    /**
     * @var OrderModel - Order Model
     */
    protected $order;

    /**
     * @var OrderLogModel - OrderLog Model
     */
    protected $orderLog;

    /**
     * @var OrderProductModel - OrderProduct Model
     */
    protected $orderProduct;

    /**
     * @var OrderProductOptionsModel - OrderProductOptions Model
     */
    protected $orderProductOptions;

    /**
     * @var OrderTaxModel - OrderTax Model
     */
    protected $orderTax;

    /**
     * @var OrderPaymentsModel - OrderPayments Model
     */
    protected $orderPayments;

    /**
     * Constructor.
     *
     * @param ManageOrderModel $manageOrderModel - ManageOrder Model
     *-----------------------------------------------------------------------*/
    public function __construct(OrderModel $order,
                        OrderLogModel $orderLog,
                        OrderProductModel $orderProduct,
                        OrderProductOptionsModel $orderProductOptions,
                        OrderTaxModel $orderTax,
                        OrderPaymentsModel $orderPayments
                        ) {
        $this->order = $order;
        $this->orderLog = $orderLog;
        $this->orderProduct = $orderProduct;
        $this->orderProductOptions = $orderProductOptions;
        $this->orderTax = $orderTax;
        $this->orderPayments = $orderPayments;
    }

    /**
     * fetch orders.
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function fetchOrdersForList($status, $userID)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => '_id',
                'creation_date' => ($status == 1) ? 'created_at' : 'updated_at',
                'name' => 'users_id',
                'paymentStatus' => 'payment_status',
            ],
            'searchable' => [
                '_id' => '_id',
                'order_uid' => 'orders.order_uid',
                'creation_date' => ($status == 1) ? 'orders.created_at' : 'orders.updated_at',
                'users_id' => 'orders.users_id',
                'fname' => 'users.fname',
                'lname' => 'users.lname',
                'payment_status' => 'orders.payment_status',
            ],
        ];

        // Check if status is active
        // then show only New, Processing, In transits, On Hold, Confirmed and 
        // Cancellation Request Received
           if ($status == 1) {
               $query = $this->order
                    ->whereNotIn('orders.status', [3, 6, 9]);
           } elseif ($status == 3) {
               $query = $this->order
                        ->whereIn('orders.status', [3, 9]);
           } else {
               $query = $this->order
                        ->where('orders.status', $status);
           }

        // if user id exist then get data by user id
        if ((int) $userID != 0) {
            $query->where('orders.users_id', (int) $userID);
        }

        return $query->join('users', 'orders.users_id', '=', 'users.id')
                     ->with('orderPayment')
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
                        'orders.total_amount',
                        'orders.payment_status',
                        'users.id as user_id',
                        'users.fname as fname',
                        'users.lname as lname'
                    )->dataTables($dataTableConfig)->toArray();
    }

    /**
     * count new order   order_uid.
     *
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function countOrderData()
    {
        return $this->order
                    ->where('status', 1)
                    ->count();
    }

    /**
     * count new order   order_uid.
     *
     * @param int $orderId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchById($orderId)
    {
        return $this->order->where('_id', $orderId)->first();
    }

    /**
     * fetch order data.
     *
     * @param int $orderID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderDetails($orderID)
    {
        return $this->order
                   ->with('user', 'address', 'address1', 'orderProduct', 'coupon')
                   ->where('_id', $orderID)
                   ->first();
    }

    /**
     * get order tax by orderID.
     *
     * @param int $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrderTax($orderID)
    {
        return $this->orderTax
                    ->where('orders__id', $orderID)
                    ->get();
    }

    /**
     * update order data.
     *
     * @param object $order
     * @param array  $input
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function updateOrder($orderID, $input)
    {
        $order = $this->order
                        ->where('_id', $orderID)
                        ->first();

        return $order->modelUpdate([
                'status' => $input['status'],
                'updated_at' => currentDateTime(),
            ]);
    }

    /**
     * get user detail by order ID.
     *
     * @param int $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchUserDetailByOrderIDs($orderID)
    {
        return $this->order
                      ->with('user')
                      ->where('_id', $orderID)->first();
    }

    /**
     * getcart order Product by user ID.
     *
     * @param int $orderID
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function getOrderProductByOrderID($orderID)
    {
        $orderProduct = [
            'status' => 1,
            'orders__id' => $orderID,
        ];

        return $this->orderProduct
                        ->with('productOption')
                        ->select(
                            '_id',
                            'name',
                            'price',
                            'status',
                            'quantity',
                            //'currency_code',
                            'orders__id'
                        )
                        ->where($orderProduct)->get()->toArray();
    }

    /**
     * get user cart order by user id.
     *
     * @param int $status
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function fetchOrders($orderID)
    {
        return  $this->order->where('_id', $orderID)->with('orderProduct')
                            ->select(
                                '_id',
                                'users_id',
                                'status',
                                'type',
                                'payment_method',
                                'addresses_id',
                                'addresses_id1',
                                'currency_code',
                                'created_at'
                            )->first();
    }

    /**
     * fetch today recived orders.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchToday()
    {
        return $this->order
                    ->where(DB::raw('DATE(updated_at)'), '>=', Carbon::now()->startOfDay())
                    ->get();
    }

    /**
     * get current month orders.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchInMonth()
    {
        return $this->order
                    ->orderBy('updated_at')
                    ->where(DB::raw('DATE(updated_at)'), '>=', Carbon::now()->startOfMonth())
                    ->get();
    }

    /**
     * fetch all orders.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrdersCount()
    {
        return $this->order->count();
    }

    /**
     * fetch all product orders
     * 7 completed orders.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductOrders()
    {
        return $this->order
                    ->with('orderProduct')
                    ->where('status', 7)
                    ->get(['_id', 'status']);
    }

    /**
     * fetch today slae products
     * 7 completed orders.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTodaySaleProducts()
    {
        return $this->order
                    ->orderBy('updated_at')
                    ->with('orderProduct')
                    ->where(DB::raw('DATE(updated_at)'), '>=', Carbon::now()->startOfDay())
                    ->where('status', 7)
                    ->get(['_id', 'status']);
    }

    /**
     * fetch all new active products.
     *
     * @return eloqunt collection object
     *---------------------------------------------------------------- */
    public function fetchNewOrderPlacedCount()
    {
        return $this->viaCache('cache.order.all.new.active.count', function () {

            return $this->order->where('status', 1)->count();

        });
    }
}
