<?php
/*
* CategoryAddRequest .php - Request file
*
* This file is part of the Category component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Category\Requests;

use Illuminate\Http\Request;
use App\Yantrana\Core\BaseRequest;

class CategoryAddRequest  extends BaseRequest
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
     * Get the validation rules that apply to the add author client post request.
     *
     * @return bool
     *-----------------------------------------------------------------------*/
    public function rules()
    {
        $parentCategoryID = (Request::has('parent_cat')
                            and !__isEmpty(Request::input('parent_cat'))
                            and Request::input('parent_cat') != 0)
                            ? Request::input('parent_cat')
                            : 'NULL';

        return [
               'name' => "required|unique:categories,name,NULL,id,parent_id,$parentCategoryID",
        ];
    }
}
