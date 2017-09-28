<?php
/*
* UpdateItemInCartRequest.php - Request file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use Cart;

class UpdateItemInCartRequest extends BaseRequest
{
    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * Constructor.
     *
     * @param ManageProductRepository $manageProductRepository - ManageProduct Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ManageProductRepository $manageProductRepository)
    {
        $this->manageProductRepository = $manageProductRepository;
    }

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
     * Get the validation rules that apply to the add item in cart post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $rules = [];

        $values = Request::input('items');

        if (is_array($values) and !empty($values)) {
            foreach ($values as $key => $value) {
                $rules['items.'.$key.'.qty'] = 'required|min:1|max:9999|integer';
            }
        }

        return $rules;
    }

    /**
     * set custom messeges for update qty.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function messages()
    {
        $messages = [];

        $values = Request::get('items');

        if (is_array($values) and !empty($values)) {
            foreach ($values as $key => $value) {
                $messages['items.'.$key.'.qty'.'.max'] = __('The quantity may not be greater than 9999');
                $messages['items.'.$key.'.qty'.'.min'] = __('The quantity must be at least 1');
                $messages['items.'.$key.'.qty'.'.required'] = __('The quantity field is required.');
                $messages['items.'.$key.'.qty'.'.integer'] = __('The quantity must be an integer.');
            }
        }

        return $messages;
    }
}
