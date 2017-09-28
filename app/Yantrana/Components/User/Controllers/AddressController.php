<?php
/*
* AddressController.php - Controller file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Controllers;

use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\User\AddressEngine;
use App\Yantrana\Components\User\Requests\AddressRequest;
use App\Yantrana\Components\User\Requests\AddressEditRequest;
use Illuminate\Http\Request;
use JavaScript;

class AddressController extends BaseController
{
    /**
     * @var AddressEngine - Address Engine
     */
    protected $addressEngine;

    /**
     * Constructor.
     *
     * @param AddressEngine $addressEngine - Address Engine
     *-----------------------------------------------------------------------*/
    public function __construct(AddressEngine $addressEngine)
    {
        $this->addressEngine = $addressEngine;
    }

    /**
     * get list of countries.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getCountries()
    {
        $countries = $this->addressEngine
                          ->fetchCountries();

        return $countries;
    }

    /**
     * Show address List view.
     *---------------------------------------------------------------- */
    public function addressList()
    {
        $addressBreadCrumb = $this->addressEngine
                                  ->generateBreadcrumb();

        // Get address type from config
        $configGetAddressType = config('__tech.address_type_list');

        // Get list of countries
        $countries = $this->getCountries();

        JavaScript::put([
            'configGetAddressType' => $configGetAddressType,
            'countries' => $countries,
            ]);

        return $this->loadPublicView('address.list', $addressBreadCrumb['data']);
    }

    /**
     * Show address view.
     *---------------------------------------------------------------- */
    public function getAddresses()
    {
        $addresses = $this->addressEngine->prepareList();

        return __processResponse($addresses, [], $addresses['data']);
    }

    /**
     * Handle delete address data request.
     *
     * @param int $addressID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($addressID, CommonPostRequest $request)
    {
        $processReaction = $this->addressEngine
                                ->processDelete($addressID);

        // get engine reaction                      
        return __processResponse($processReaction, [
                    1 => __('Address deleted successfully.'),
                    3 => __('Something went wrong.'),
                    18 => __('Address does not exist.'),
                    ], $processReaction['data']);
    }

    /**
     * Handle user address process request.
     *
     * @param object UserAddressRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(AddressRequest $request)
    {
        $processReaction = $this->addressEngine->addAddress($request->all());

        return __processResponse($processReaction, [
                1 => __('Address stored successfully.'),
                2 => __('failed to store address.'),
            ], $processReaction['data']);
    }

    /**
     * Handle edit address support data.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($addressID)
    {
        if (empty($addressID)) {
            return __apiResponse([], 7);
        }
        $address = $this->addressEngine->prepareEdit($addressID);

        return __processResponse($address, [
              18 => 'Address does not exist.',
            ], $address['data']);
    }

    /**
     * Handle user address update process request.
     *
     * @param object AddressEditRequest $request
     * @param $addressID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function update(AddressEditRequest $request, $addressID)
    {
        $processReaction = $this->addressEngine->updateAddress($addressID, $request->all());

        // get engine reaction
        return __processResponse($processReaction, [
                1 => __('Address updated successfully.'),
                14 => __('Nothing update.'),
                18 => __('Address does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Get address for order summary page.
     *---------------------------------------------------------------- */
    public function getAddressForOrder()
    {
        $addresses = $this->addressEngine->prepareOrderAddressList();

        return __processResponse($addresses, [], true);
    }

    /**
     * Handle user address make primary.
     *
     * @param object AddressEditRequest $request
     * @param $addressID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function makePrimaryAddress($addressID)
    {
        $processReaction = $this->addressEngine
                                ->updateAddressPrimary($addressID);

        return __processResponse($processReaction, [
                1 => __('Address updated successfully.'),
                14 => __('Nothing update.'),
                18 => __('Address does not exist.'),
            ], $processReaction['data']);
    }
}
