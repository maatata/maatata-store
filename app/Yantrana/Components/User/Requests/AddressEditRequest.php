<?php
/*
* AddressEditRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Requests;

use App\Yantrana\Core\BaseRequest;

class AddressEditRequest extends BaseRequest
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
        return [
           'address_line_1' => 'required',
           'address_line_2' => 'required',
           'city' => 'required',
           'state' => 'required',
           'country' => 'required',
           'pin_code' => 'required',
        ];
    }
}
