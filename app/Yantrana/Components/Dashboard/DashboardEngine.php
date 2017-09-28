<?php
/*
* DashboardEngine.php - Main component file
*
* This file is part of the Dashboard component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Dashboard;

use App\Yantrana\Components\Dashboard\Repositories\DashboardRepository;
use App\Yantrana\Components\ShoppingCart\Repositories\ManageOrderRepository;
use App\Yantrana\Components\Dashboard\Blueprints\DashboardEngineBlueprint;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\User\Repositories\UserRepository;
use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;

class DashboardEngine implements DashboardEngineBlueprint
{
    /**
     * @var DashboardRepository - Dashboard Repository
     */
    protected $dashboardRepository;

    /**
     * @var ManageOrderRepository - ManageOrder Repository
     */
    protected $manageOrderRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var UserRepository - User Repository
     */
    protected $userRepository;

    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * Constructor.
     *
     * @param DashboardRepository $dashboardRepository - Dashboard Repository
     *-----------------------------------------------------------------------*/
    public function __construct(
                    DashboardRepository $dashboardRepository,
                    ManageOrderRepository $manageOrderRepository,
                    ManageProductRepository $manageProductRepository,
                    UserRepository $userRepository,
                    CouponRepository $couponRepository,
                    BrandRepository $brandRepository,
                    ShippingRepository $shippingRepository
                ) {
        $this->dashboardRepository = $dashboardRepository;
        $this->manageOrderRepository = $manageOrderRepository;
        $this->manageProductRepository = $manageProductRepository;
        $this->userRepository = $userRepository;
        $this->couponRepository = $couponRepository;
        $this->brandRepository = $brandRepository;
        $this->shippingRepository = $shippingRepository;
    }

    /**
     * get orders count.
     *
     * @param object $orders
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getOrders($orders)
    {
        $countNewStatus = [];
        $countCompletedStatus = [];
        $countPendingStatus = [];
        $countProcessStatus = [];
        $countCancelStatus = [];

        foreach ($orders as $order) {

            // New orders recived
            if ($order->status == 1) {
                $countNewStatus[] = $order;
            }

            // order completed
            if ($order->status == 7) {
                $countCompletedStatus[] = $order;
            }

            // order in onhold
            if ($order->status == 4) {
                $countPendingStatus[] = $order;
            }

            // order in processing
            if ($order->status == 2) {
                $countProcessStatus[] = $order;
            }

            // order cancelled by admin or user
            if (($order->status == 3) or ($order->status == 10)) {
                $countCancelStatus[] = $order;
            }
        }

        return [
            'received' => count($countNewStatus),
            'completed' => count($countCompletedStatus),
            'pending' => count($countPendingStatus),
            'processing' => count($countProcessStatus),
            'cancelled' => count($countCancelStatus),
        ];
    }

    /**
     * check status of product.
     *
     * @param int $status
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function isActiveProduct($status)
    {
        return ($status) ? 1 : 2;
    }

    /**
     * check status is deactive product.
     *
     * @param int $status
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function isDeactiveProduct($status)
    {
        return ($status) ? 2 : 1;
    }

    /**
     * check is outOfStock product.
     *
     * @param int $outOfStock
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function isOutOfStockProduct($outOfStock)
    {
        return ($outOfStock) ? 1 : 0;
    }

    /**
     * get products count.
     *
     * @param object $products
     *
     * @return array
     *---------------------------------------------------------------- */
    protected function getProducts()
    {
        $products = $this->manageProductRepository->fetchAllProducts();

        $active = 0;
        $deactive = 0;
        $isOutOfStock = 0;

        foreach ($products as $product) {
            if ($product->status == $this->isActiveProduct($product->status)) {
                $active = $product->productCount;
            }

            if ($product->status == $this->isDeactiveProduct($product->status)) {
                $deactive = $product->productCount;
            }
        }

        return [
            'active' => $active,
            'deactive' => $deactive,
            'outOfStock' => $this->manageProductRepository->fetchOutOfStockCount(),
            'allProducts' => $active + $deactive,
        ];
    }

    /**
     * get dashboard list.
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDashboardSupportData()
    {
        // order related
        $todayOrders = $this->manageOrderRepository->fetchToday();
        $inMonthOrders = $this->manageOrderRepository->fetchInMonth();
        $totalOrders = $this->manageOrderRepository->fetchOrdersCount();
        $orders = $this->manageOrderRepository->fetchProductOrders();
        $todayCompletedOrders = $this->manageOrderRepository->fetchTodaySaleProducts();

        // fetch all user
        $getUsersCount = $this->userRepository->fetchUsersCount();
        // fetch today registered users
        $todayRegisterUsersCount = $this->userRepository
                                        ->fetchTodayRegisteredUsersCount();

        // get total sale
        $totalSaleProducts = $this->totalSale($orders);
        $todaySaleProducts = $this->totalSale($todayCompletedOrders);

        // fetch all brands
        $brands = $this->brandRepository->fetchCount();

        // coupon expiring in next 5 days
        $nextSomeDaysLiveCoupons = $this->couponRepository->fetchNextFiveDaysLiveCoupons();
        // active coupons
        $activeCoupons = $this->couponRepository->fetchActiveCount();

        // shipping count
        $shipping = $this->shippingRepository->fetchWithoutAoc();

        $dashboard = [
            'today' => $this->getOrders($todayOrders),
            'inMonth' => $this->getOrders($inMonthOrders),
            'totalOrders' => $totalOrders,
        ];

        return __engineReaction(1, [
            'orders' => $dashboard,
            'products' => $this->getProducts(),
            'totalSale' => ($totalSaleProducts) ? priceFormat($totalSaleProducts) : priceFormat(0),
            'todaySale' => ($todaySaleProducts) ? priceFormat($todaySaleProducts) : priceFormat(0),
            'users' => $getUsersCount,
            'todayRegisteredUsers' => $todayRegisterUsersCount,
            'brands' => $brands,
            'shipping' => $shipping,
            'coupons' => [
                'activeCoupons' => $activeCoupons,
                'nextSomeDaysLiveCoupons' => $nextSomeDaysLiveCoupons,
            ],
        ]);
    }

    /**
     * get total sale calculation.
     *
     * @param array $orders
     *
     * @return number
     *---------------------------------------------------------------- */
    public function totalSale($orders)
    {
        $productPrice = [];

        $totalSaleProducts = 0;

        if (!__isEmpty($orders)) {
            foreach ($orders as $order) {
                foreach ($order->orderProduct as $product) {
                    $addonPrice = [];

                    if (isset($product->productOption) and !__isEmpty($product->productOption)) {
                        foreach ($product->productOption as $option) {
                            $addonPrice[] = $option->addon_price;
                        }
                    }

                    $productPriceWithAddonPrice = (!empty($addonPrice)) ? array_sum($addonPrice) + $product->price : $product->price;

                    $productPrice[] = $productPriceWithAddonPrice * $product->quantity;
                }
            }
        }

        $totalSaleProducts = array_sum($productPrice);

        return $totalSaleProducts;
    }
}
