<?php
/*
* CouponEngine.php - Main component file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon;

use App\Yantrana\Components\Coupon\Repositories\CouponRepository;
use App\Yantrana\Components\Coupon\Blueprints\CouponEngineBlueprint;
use App\Yantrana\Components\ShoppingCart\ShoppingCartEngine;

class CouponEngine implements CouponEngineBlueprint
{
    /**
     * @var CouponRepository - Coupon Repository
     */
    protected $couponRepository;

    /**
     * @var ShoppingCartEngine - ShoppingCart Engine
     */
    protected $shoppingCartEngine;

    /**
     * Constructor.
     *
     * @param CouponRepository $couponRepository - Coupon Repository
     *-----------------------------------------------------------------------*/
    public function __construct(CouponRepository $couponRepository,
                        ShoppingCartEngine $shoppingCartEngine)
    {
        $this->couponRepository = $couponRepository;
        $this->shoppingCartEngine = $shoppingCartEngine;
    }

    /**
     * get prepare coupons list.
     *
     * @param int $status
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList($status)
    {
        return $this->couponRepository
                    ->fetchForList($status);
    }

    /**
     * add new coupon.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addProcess($inputData)
    {
        $coupon = $this->couponRepository->store($inputData);

        // Check if coupon addded
        if ($coupon) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * get edit data.
     *
     * @param array $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($couponID)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchByID($couponID);

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // Prepare coupon data
        $couponData = [
            '_id' => $coupon->_id,
            'title' => $coupon->title,
            'description' => $coupon->description,
            'code' => $coupon->code,
            'start' => $coupon->start,
            'end' => $coupon->end,
            'discount' => $coupon->discount,
            'discount_type' => $coupon->discount_type,
            'max_discount' => $coupon->max_discount,
            'minimum_order_amount' => $coupon->minimum_order_amount,
            'active' => ($coupon->status == 1) ? true : false,
        ];

        // Get discount type config items and currency code & symbol
        $configItems = [
            'discountType' => $discountType = config('__tech.coupon_discount_type'),
            'currencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency(),
        ];

        return __engineReaction(1, [
            'couponData' => $couponData,
            'configItems' => $configItems,
        ]);
    }

    /**
     * get coupon detail.
     *
     * @param array $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchDetail($couponID)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchDetailByID($couponID);

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

           // Prepare couponData array
        $couponData = [
            'title' => $coupon->title,
            'description' => $coupon->description,
            'code' => $coupon->code,
            'discount' => $coupon->discount,
            'minimum_order_amount' => $coupon->minimum_order_amount,
            'max_discount' => $coupon->max_discount,
            'discount_type' => $coupon->discount_type,
            'currencySymbol' => getCurrencySymbol(),
        ];

        return __engineReaction(1, $couponData);
    }

    /**
     * update coupon.
     *
     * @param int   $couponID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($couponID, $inputData)
    {
        // Get coupon detail
        $coupon = $this->couponRepository->fetchByID($couponID);

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // Check status active or not
        $status = 1;
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        // Prepare updateData array for update coupon detail 
        $updateData = [
            'title' => $inputData['title'],
            'description' => $inputData['description'],
            'code' => $inputData['code'],
            'start' => $inputData['start'],
            'end' => $inputData['end'],
            'discount' => $inputData['discount'],
            'discount_type' => $inputData['discount_type'],
            'max_discount' => $inputData['max_discount'],
            'minimum_order_amount' => $inputData['minimum_order_amount'],
            'status' => $status,
        ];

        $reponseData = $this->couponRepository->update($coupon, $updateData);

        if ($reponseData) {
            return __engineReaction(1, $reponseData);
        }

        return __engineReaction(14);
    }

    /**
     * delete coupon.
     *
     * @param int $couponID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($couponID)
    {
        // find record
        $coupon = $this->couponRepository->fetchByID($couponID);

        // Check if coupon is empty
        if (empty($coupon)) {
            return __engineReaction(18);
        }

        // delete coupon request
        $response = $this->couponRepository->delete($coupon);

        if ($response) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * apply coupon process.
     *
     * @param int $code
     * @param int $cartTotalPrice
     *---------------------------------------------------------------- */
    public function applyCouponProcess($code = null)
    {
        // this function use for get cart details
        $orderDetails = $this->shoppingCartEngine->getCartDetails();

        $cartTotalPrice = null;

        if (!__isEmpty($orderDetails)) {
            $cartTotalPrice = $orderDetails['data']['total']['totalBasePrice'];
        }

        $couponData = [];

        $coupon = $this->couponRepository->fetchCouponCode($code);

        if (__isEmpty($coupon)) {
            $data['couponData'] = $code;
            $data['totalPrice'] = $cartTotalPrice;

            return __engineReaction(2, $data);
        }

        if ($coupon->minimum_order_amount > $cartTotalPrice) {
            $data['couponData'] = priceFormat($coupon->minimum_order_amount);
            $data['couponCode'] = $code;
            $data['totalPrice'] = $cartTotalPrice;

            return __engineReaction(9, $data);
        }

        $discount = 0;

        if ($coupon->discount_type == 2) {
            $discount = ($coupon->discount / 100) * $cartTotalPrice;

            if ($coupon->max_discount < $discount) {
                $discount = $coupon->max_discount;
            } else {
                $discount = $discount;
            }
        }

        if ($coupon->discount_type == 1) {
            $discount = ($coupon->max_discount / 100) * $cartTotalPrice;

            if ($coupon->discount < $discount) {
                $discount = $coupon->discount;
            } else {
                $discount = $discount;
            }
        }

        $couponData = [
            'couponID' => $coupon->_id,
            'couponCode' => $coupon->code,
            'discount' => $discount,
            'formattedDiscount' => priceFormat($discount),
            'title' => $coupon->title,
            'description' => $coupon->description,
            'cartPrice' => $cartTotalPrice,
        ];

        $data['totalPrice'] = $cartTotalPrice - $discount; // subtract discount amount from order amount
        $data['couponData'] = $couponData;

        return __engineReaction(1, $data);
    }

    /**
     * fetch coupon by code.
     *
     * @param string $code
     *
     * @return object
     *---------------------------------------------------------------- */
    public function getCouponByCode($code)
    {
        return $this->couponRepository->fetchCouponCode($code);
    }

    /**
     * get coupon information.
     *
     * @param int $couponId
     * @param int $currency
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCouponInformation($couponId, $currency)
    {
        $coupon = $this->couponRepository->fetchByID($couponId);

        if (__isEmpty($coupon)) {
            return [
                'info' => '',
            ];
        }

        return [
            'info' => [
                'code' => $coupon->code,
                'discount' => $coupon->discount,
                'formatedCouponDiscount' => orderPriceFormat(
                                                $coupon->discount,
                                                $currency
                                            ),
                'title' => $coupon->title,
                'description' => $coupon->description,
            ],
        ];
    }

    /**
     * Get and Prepare Discount type.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDiscountType()
    {
        $configItems = [
            'discountType' => $discountType = config('__tech.coupon_discount_type'),
            'currencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency(),
        ];

        return __engineReaction(1, $configItems);
    }
}
