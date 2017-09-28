<?php

namespace App\Yantrana\Core;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Http\Request;
use App\Yantrana\__Laraware\Core\CoreRequest;
use Auth;

abstract class BaseRequest extends CoreRequest
{
    /**
     * Modify form request response.
     *
     * @param  $errors 
     * 
     * @return array
     *------------------------------------------------------------------------ */
    public function response(array $errors)
    {

    //return \Response::json($errors);

    if (Request::ajax()) {
        return __apiResponse([
                    'validation' => $errors,
            'message' => __('Ooops..., looks like something went wrong!'),
        ], 3);
    }

        return $this->redirector->to($this->getRedirectUrl())
                            ->withInput($this->except($this->dontFlash))
                            ->withErrors($errors);
    }

    /**
     * Modify validator.
     *
     * @param  $factory 
     *
     * @return array
     *------------------------------------------------------------------------ */
    public function validator(ValidatorFactory $factory)
    {
        $result = parent::validator($factory);

        if ($this->isMethod('post')
                and isDemo()
                and isAdmin()
                and (Auth::id() !== 1) // admin user id
            ) {
            if ($result->passes()) {
                exit(__processResponse(['reaction_code' => 1, 'message' => null], [
                    1 => __('Saving functionality is disabled in this demo'),
                ], ['__useNativeJsonEncode' => true]));
            }
        }

        return $result;
    }
}
