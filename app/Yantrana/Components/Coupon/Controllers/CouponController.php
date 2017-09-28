<?php
/*
* CouponController.php - Controller file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Coupon\CouponEngine;
use App\Yantrana\Components\Coupon\Requests\CouponAddRequest;
use App\Yantrana\Components\Coupon\Requests\CouponEditRequest;

class CouponController extends BaseController
{
    /**
     * @var CouponEngine - Coupon Engine
     */
    protected $couponEngine;

    /**
     * Constructor.
     *
     * @param CouponEngine $couponEngine - Coupon Engine
     *-----------------------------------------------------------------------*/
    public function __construct(CouponEngine $couponEngine)
    {
        $this->couponEngine = $couponEngine;
    }

    /**
     * get all coupons.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index($status)
    {
        $engineReaction = $this->couponEngine
                                ->prepareList($status);
        $requireColumns = [
            'start_date' => function ($key) {
                return formatStoreDateTime($key['start']);
            },
            'end_date' => function ($key) {
                return formatStoreDateTime($key['end']);
            },
            '_id', 'title', 'status', 'code',
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * Handle add coupon request.
     *
     * @param object CouponAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(CouponAddRequest $request)
    {
        $processReaction = $this->couponEngine
                                ->addProcess($request->all());

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Coupon added successfully.'),
                    2 => __('oh..no. error.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details edit request.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->fetchData($couponID);
        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details of coupon.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->fetchDetail($couponID);
        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle edit coupon request.
     *
     * @param object BrandEditRequest $request
     * @param int                     $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editProcess(CouponEditRequest $request, $couponID)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->processUpdate($couponID, $request->all());

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Coupon updated successfully.'),
                    14 => __('Nothing update.'),
                    18 => __('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle delete coupon request.
     *
     * @param int $couponID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($couponID, Request $request)
    {
        // check if couponID is empty
        if (empty($couponID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->couponEngine
                                ->processDelete($couponID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Coupon deleted successfully.'),
                    2 => __('Something went wrong.'),
                    18 => __('Coupon does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Get coupon discount tpe request.
     *
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getCouponDiscountType()
    {
        $processReaction = $this->couponEngine
                                ->prepareDiscountType();

        // get engine reaction						
        return __processResponse($processReaction, [], $processReaction['data']);
    }
}
