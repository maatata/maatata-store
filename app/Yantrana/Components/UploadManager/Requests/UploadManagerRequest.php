<?php
/*
* UploadManagerRequest.php - Request file
*
* This file is part of the UploadManager component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\UploadManager\Requests;

use App\Yantrana\Core\BaseRequest;

class UploadManagerRequest extends BaseRequest
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
