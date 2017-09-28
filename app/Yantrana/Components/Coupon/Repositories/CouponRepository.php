<?php
/*
* CouponRepository.php - Repository file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Coupon\Models\Coupon as CouponModel;
use App\Yantrana\Components\Coupon\Blueprints\CouponRepositoryBlueprint;
use Carbon\Carbon;

class CouponRepository extends BaseRepository
                          implements CouponRepositoryBlueprint
{
    /**
     * @var CouponModel - Coupon Model
     */
    protected $couponModel;

    /**
     * Constructor.
     *
     * @param CouponModel $couponModel - Coupon Model
     *-----------------------------------------------------------------------*/
    public function __construct(CouponModel $couponModel)
    {
        $this->couponModel = $couponModel;
    }

    /**
     * Fetch coupons datatable source.
     *
     * @param int $status
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchForList($status)
    {
        $dataTableConfig = [
            'fieldAlias' => [
                'start_date' => 'start',
                'end_date' => 'end',
            ],
            'searchable' => [
                '_id' => '_id',
                'start_date' => 'start',
                'end_date' => 'end',
                'title' => 'title',
                'code' => 'code',
            ],
        ];

        $query = $this->couponModel;

        if ((int) $status === 1) { // current

            return  $query->where('start', '<=', currentDateTime())
                         ->where('end', '>=', currentDateTime())
                         ->dataTables($dataTableConfig)
                         ->toArray();
        } elseif ((int) $status === 2) { // expired

            return $query->where('end', '<=', currentDateTime())
                         ->dataTables($dataTableConfig)
                         ->toArray();
        } elseif ((int) $status === 3) { // up-coming

            return $query->where('start', '>=', currentDateTime())
                         ->dataTables($dataTableConfig)
                         ->toArray();
        }
    }

    /**
     * Store new coupon using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function store($inputData)
    {
        $coupon = new $this->couponModel();
        $coupon->title = $inputData['title'];
        $coupon->description = (isset($inputData['description'])
                                        and !__isEmpty($inputData['description']))
                                        ? $inputData['description']
                                        : null;
        $coupon->code = $inputData['code'];
        $coupon->start = $inputData['start'];
        $coupon->end = $inputData['end'];
        $coupon->discount = $inputData['discount'];
        $coupon->discount_type = $inputData['discount_type'];
        $coupon->max_discount = (isset($inputData['max_discount'])
                                        and !__isEmpty($inputData['max_discount']))
                                        ? $inputData['max_discount']
                                        : null;
        $coupon->minimum_order_amount = $inputData['minimum_order_amount'];
        $coupon->status = ($inputData['active']) ? 1 // active 
                                                     : 2; // deactive 

        //Check if coupon added
        if ($coupon->save()) {
            activityLog('ID of '.$coupon->_id.' coupon added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch coupon by id.
     *
     * @param array $couponID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($couponID)
    {
        return $this->couponModel->fetchByID($couponID)->first();
    }

    /**
     * Fetch coupon detail.
     *
     * @param array $couponID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetailByID($couponID)
    {
        return $this->couponModel->fetchByID($couponID)
                                 ->first([
                                    'title',
                                    'description',
                                    'code',
                                    'discount',
                                    'minimum_order_amount',
                                    'max_discount',
                                    'discount_type',
                                    ]);
    }

    /**
     * Update coupon using provided data.
     *
     * @param int   $couponID
     * @param array $updateData
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function update($coupon, $updateData)
    {
        if ($coupon->modelUpdate($updateData)) {
            activityLog('ID of '.$coupon->_id.' coupon update.');

            return $coupon;
        }

        return false;
    }

    /**
     * Delet coupon using coupon.
     *
     * @param int $coupon
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function delete($coupon)
    {
        if ($coupon->delete()) {
            activityLog('ID of '.$coupon->_id.' coupon deleted.');

            return  1;
        }

        return  2;
    }

    /**
     * get coupon code.
     *
     * @param int $code
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCouponCode($code)
    {
        return $this->couponModel
                    ->code($code)
                    ->active()
                    ->where('start', '<=',  currentDateTime())
                    ->where('end', '>=',  currentDateTime())
                    ->first();
    }

    /**
     * fetch active coupons.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchActiveCount()
    {
        return $this->couponModel
                    ->where('start', '<=', currentDateTime())
                    ->where('end', '>=', currentDateTime())
                    ->count();
    }

    /**
     * fetch record of next 5 day expring.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchNextFiveDaysLiveCoupons()
    {
        return $this->couponModel
                    ->active()
                    ->whereBetween('end', [Carbon::today(), Carbon::today()->addDays(5)])
                    ->count();
    }
}
