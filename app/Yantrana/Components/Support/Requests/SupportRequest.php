<?php
/*
* SupportRequest.php - Request file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support\Requests;

use App\Yantrana\Core\BaseRequest;

class SupportRequest extends BaseRequest
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
