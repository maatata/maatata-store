<?php
/*
* ManagePagesAddRequest.php - Request file
*
* This file is part of the Pages component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Pages\Requests;

use App\Yantrana\Core\BaseRequest;

class ManagePagesAddRequest extends BaseRequest
{
    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = ['description' => ''];

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
     * Get the validation rules that apply to the add author client post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        extract(\Request::all());

        $rules = [
               'title' => 'required',
            'type' => 'required|numeric',
        ];

        if ($type == 1) {
            if (empty($description)) {
                $rules['description'] = 'required|min:6';
            }
        } elseif ($type == 3) {
            if (empty($external_page)) {
                $rules['external_page'] = 'required|numeric';
            }

            if (empty($open_as)) {
                $rules['open_as'] = 'required|numeric';
            }
        } elseif ($type == 2) {
            if (empty($open_as)) {
                $rules['open_as'] = 'required|numeric';
            }

            if (empty($link)) {
                $rules['link'] = 'required';
            } else {
                //$rules['link']      = 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
            $rules['link'] = 'url';
            }
        }

        return $rules;
    }
}
