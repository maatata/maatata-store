<?php
/*
* UserRegisterRequest.php - Request file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Requests;

use App\Yantrana\Core\BaseRequest;

class UserRegisterRequest extends BaseRequest
{
    /**
     * Secure form.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

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
     * Get the validation rules that apply to the user register request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [
            'first_name' => 'required|min:2|max:30',
            'last_name' => 'required|min:2|max:30',
            'email' => 'required|email|unique:users,email,NULL,id,status,!5',
            'password' => 'required|min:6|max:30',
            'password_confirmation' => 'required|min:6|max:30|same:password',
            'confirmation_code' => 'required|captcha',
        ];

        // If term & contiotions exist Then add term_condition validation
        if (getStoreSettings('term_condition')) {
            $rules['term_condition'] = 'required';
        }

        return $rules;
    }
}
