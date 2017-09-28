<?php
/*
* AddItemInCartRequest.php - Request file
*
* This file is part of the ShoppingCart component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\ShoppingCart\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;

class AddItemInCartRequest extends BaseRequest
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
        return ['quantity' => 'required|min:1|integer|max:99999'];
    }
}
