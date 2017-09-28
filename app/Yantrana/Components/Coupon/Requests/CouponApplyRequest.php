<?php
/*
* CouponApplyRequest.php - Request file
*
* This file is part of the Coupon component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Coupon\Requests;

use App\Yantrana\Core\BaseRequest;

class CouponApplyRequest extends BaseRequest
{
    /**
     * Authorization for request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [];
    }
}
