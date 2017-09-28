<?php
/*
* PaymentUpdateRequest.php - Request file
*
* This file is part of the ShoppingCart order component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Requests;

use App\Yantrana\Core\BaseRequest;

class PaymentUpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the add product post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        return [
               'txn' => 'required|unique:order_payments,txn',
               'paymentMethod' => 'required',
               'fee' => 'numeric',
               'comment' => 'required|min:6',
       ];
    }
}
