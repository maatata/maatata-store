<?php
/*
* ReportRepository.php - Repository file
*
* This file is part of the Report component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Report\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Report\Models\Report as ReportModel;
use App\Yantrana\Components\ShoppingCart\Models\Order as OrderModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderTax as OrderTaxModel;
use App\Yantrana\Components\ShoppingCart\Models\OrderPayments as OrderPaymentsModel;
use App\Yantrana\Components\Report\Blueprints\ReportRepositoryBlueprint;
use DB;

class ReportRepository extends BaseRepository
                          implements ReportRepositoryBlueprint
{
    /**
     * @var ReportModel - Report Model
     */
    protected $reportModel;

    /**
     * @var OrderModel - Order Model
     */
    protected $order;

    /**
     * @var OrderTaxModel - OrderTax Model
     */
    protected $orderTax;

    /**
     * @var OrderPaymentsModel - OrderPayments Model
     */
    protected $orderPaymentsModel;

    /**
     * Constructor.
     *
     * @param ReportModel $reportModel - Report Model
     *-----------------------------------------------------------------------*/
    public function __construct(ReportModel $reportModel,
                         OrderModel $order,
                         OrderTaxModel $orderTax,
                         OrderPaymentsModel $orderPaymentsModel
                        ) {
        $this->reportModel = $reportModel;
        $this->order = $order;
        $this->orderTax = $orderTax;
        $this->orderPaymentsModel = $orderPaymentsModel;
    }

    /**
     * Fetch order report datatable source.
     * 
     * @param (int) $categoryID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDataTableSource($startDate, $endDate, $status, $order)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => '_id',
                'creation_date' => ($status == 1) ? 'created_at' : 'updated_at',
                'name' => 'users_id',
                'totalAmount' => 'total_amount',
            ],
            'searchable' => [
                '_id' => '_id',
                'order_uid' => 'orders.order_uid',
                'users_id' => 'orders.users_id',
                'creation_date' => ($status == 1) ? 'orders.created_at' : 'orders.updated_at',
                'totalAmount' => 'orders.total_amount',
                'fname' => 'users.fname',
                'lname' => 'users.lname',
            ],
        ];

        // If order status any and date is created_at 
        if ($status != 9 and $order == 1) { // all

            $query = $this->order
                          ->where('orders.status', $status)
                          ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);

        // If order status any and date is updated_at
        } elseif ($status != 9 and $order == 2) {
            $query = $this->order
                          ->where('orders.status', $status)
                          ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$startDate, $endDate]);

        // If order status is 9 (all) and date is created_at
        } elseif ($status == 9 and $order == 1) {
            $query = $this->order
                          ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);

        // If order status is 9 (all) and date is updated_at
        } elseif ($status == 9 and $order == 2) {
            $query = $this->order
                          ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$startDate, $endDate]);
        }

        return $query->join('users', 'orders.users_id', '=', 'users.id')
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
     * fetch total order amount by its currency code.
     *
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param $order
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchTotalAmountByCurrency($startDate, $endDate)
    {
        return $this->orderPaymentsModel
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
                    ->get();
    }

    /**
     * fetch order data for report generation.
     *
     * @param int $orderID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderDetails($orderID)
    {
        return $this->order
                   ->with('user', 'orderProduct', 'coupon', 'address', 'address1')
                   ->where('_id', $orderID)
                   ->first();
    }

    /**
     * get order tax by orderID.
     *
     * @param int $orderID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderTax($orderID)
    {
        return $this->orderTax
                    ->where('orders__id', $orderID)
                    ->get();
    }

    /**
     * fetch all order data.
     *
     * @param int $startDate
     * @param int $endData
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOrderCollection($startDate, $endDate, $status, $order)
    {
        // If order status any and date is created_at 
        if ($status != 9 and $order == 1) { // all

            $query = $this->order
                          ->where('orders.status', $status)
                          ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);

        // If order status any and date is updated_at
        } elseif ($status != 9 and $order == 2) {
            $query = $this->order
                          ->where('orders.status', $status)
                          ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$startDate, $endDate]);

        // If order status is 9 (all) and date is created_at
        } elseif ($status == 9 and $order == 1) {
            $query = $this->order
                          ->whereBetween(DB::raw('DATE(orders.created_at)'), [$startDate, $endDate]);

        // If order status is 9 (all) and date is updated_at
        } elseif ($status == 9 and $order == 2) {
            $query = $this->order
                          ->whereBetween(DB::raw('DATE(orders.updated_at)'), [$startDate, $endDate]);
        }

        return $query->join('users', 'orders.users_id', '=', 'users.id')
                     ->select(
                        'orders._id',
                        'orders.created_at',
                        'orders.status as status',
                        'orders.type',
                        'orders.payment_method',
                        'orders.currency_code',
                        'orders.users_id as users_id',
                        'orders.order_uid',
                        'orders.discount_amount',
                        'orders.shipping_amount',
                        'orders.total_amount',
                        'users.id as user_id',
                        'users.fname as fname',
                        'users.lname as lname',
                        'orders.currency_code'
                    )->get()->toArray();
    }
}
