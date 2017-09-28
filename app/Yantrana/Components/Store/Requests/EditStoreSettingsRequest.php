<?php
/*
* EditStoreSettingsRequest.php - Request file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Requests;

use App\Yantrana\Core\BaseRequest;
use Illuminate\Http\Request;

class EditStoreSettingsRequest extends BaseRequest
{
    /**
     * Set if you need form request secured.
     *------------------------------------------------------------------------ */
    protected $securedForm = true;

    /**
     * Unsecured/Unencrypted form fields.
     *------------------------------------------------------------------------ */
    protected $unsecuredFields = [
        'contact_address',
        'term_condition',
        'privacy_policy',
        'addtional_page_end_content',
        'payment_check_text',
        'payment_bank_text',
        'payment_cod_text',
        'payment_other_text',
    ];

    /**
     * Loosely sanitize fields.
     *------------------------------------------------------------------------ */
    protected $looseSanitizationFields = [
                                            'contact_address' => '',
                                            'term_condition' => '',
                                            'privacy_policy' => '',
                                            'payment_check_text' => '',
                                            'payment_bank_text' => '',
                                            'payment_cod_text' => '',
                                            'payment_other_text' => '',
                                            'addtional_page_end_content' => '<script></script>',
                                         ];

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
     * Get the validation rules that apply to the edit store settings request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $formType = Request::route()->getParameter('formType');

        $rules = [];

        if ($formType == 'general') {
            $rules = [
                'store_name' => 'required'
            ];
        }

        return $rules;
    }
}
