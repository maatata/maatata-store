<?php
/*
* AddressEngine.php - Main component file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User;

use App\Yantrana\Components\User\Repositories\AddressRepository;
use App\Yantrana\Components\User\Blueprints\AddressEngineBlueprint;
use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use Config;
use Request;
use Breadcrumb;

class AddressEngine implements AddressEngineBlueprint
{
    /**
     * @var AddressRepository - Address Repository
     */
    protected $addressRepository;

    /**
     * @var ShippingRepository
     */
    protected $shippingRepository;

    /**
     * @var TaxRepository
     */
    protected $taxRepository;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * @var addressConfig - addressConfig config items
     */
    protected $addressConfig;

    /**
     * Constructor.
     *
     * @param AddressRepository $addressRepository - Address Repository
     *-----------------------------------------------------------------------*/
    public function __construct(AddressRepository $addressRepository,
        ShippingRepository $shippingRepository,
        ManageProductRepository $manageProductRepository,
        TaxRepository $taxRepository,
        SupportRepository $supportRepository)
    {
        $this->addressRepository = $addressRepository;
        $this->shippingRepository = $shippingRepository;
        $this->manageProductRepository = $manageProductRepository;
        $this->taxRepository = $taxRepository;
        $this->supportRepository = $supportRepository;
        $this->addressConfig = config('__tech.address_type');
    }

    /**
     * Fetch countries.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchCountries()
    {
        // get countries array
        $countries = $this->supportRepository->fetchCountries();

        $countriesCollection = [];

        // set key value pair of countries and remove exist country
        foreach ($countries as $key => $country) {
            $countriesCollection[] = [
                'value' => $country['_id'],
                'text' => $country['name'],
            ];
        }

        return __engineReaction(1, [
            'countries' => $countriesCollection,
        ]);
    }

    /**
     * Generate Breadcrumb.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function generateBreadcrumb()
    {
        // generate breadcrumb for address list
        $breadCrumb = Breadcrumb::generate('address');
        if (!empty($breadCrumb)) {
            return __engineReaction(1, [
                'breadCrumb' => $breadCrumb,
            ]);
        }

        return __engineReaction(2, [
                'breadCrumb' => null,
            ]);
    }

    /**
     * Prepare address List request.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        $getAddress = $this->addressRepository
                            ->fetchAddresses();

        // Get address type from config
        $addressType = config('__tech.address_type');

        $addresses = [];

        // Check if addess not exist
        if (empty($getAddress)) {
            return __engineReaction(2, [
                'message' => __('There are no address.'),
            ]);
        }

        // all address push in addresses array
        foreach ($getAddress as $address) {

            // get country name 
            $country = $this->supportRepository
                              ->fetchCountry($address->countries__id);

            $addresses[] = [
                'id' => $address->id,
                'type' => $addressType[$address->type],
                'addressID' => $address->id,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'primary' => $address->primary,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $country->name,
                'pin_code' => $address->pin_code,
                'countryCode' => $address->country,
            ];
        }

        // Get address type from config
        $addressType = config('__tech.address_type_list');

        // get countries collection
         $countries = $this->fetchCountries();

        return __engineReaction(1, [
            'addresses' => $addresses,
            'addressType' => $addressType,
            'countries' => $countries,
        ]);
    }

    /**
     * process delete address.
     *
     * @param int $addressID
     *
     * @return reaction number
     *---------------------------------------------------------------- */
    public function processDelete($addressID)
    {
        // fetch address detail
        $addressDetail = $this->addressRepository->fetchAddressByID($addressID);

        // Check if address exist
        if (__isEmpty($addressDetail)) {
            return __engineReaction(18);
        }

        // process delete address
        $address = $this->addressRepository->delete($addressID);

        // Check if address deleted
        if ($address) {
            return __engineReaction(1);
        }

        return __engineReaction(18);
    }

    /**
     * Process user address request.
     *
     * @param array $request
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addAddress($request)
    {
        // get country name 
        $country = $this->supportRepository
                          ->fetchCountry($request['country']);

        // Check if country code exist
        if (!__isEmpty($country['iso_code'])) {
            $request['code'] = $country['iso_code'];
        }

        // Process store new address
        $address = $this->addressRepository->store($request);

        // if address store successfully
           if ($address) {
               return  __engineReaction(1);
           }

        return  __engineReaction(2);
    }

    /**
     * Process Edit address request.
     *
     * @param array $addressID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEdit($addressID)
    {
        $address = $this->addressRepository->fetchAddressByID($addressID);

        $editData = [];

        // Check if address exist 
        if (empty($address)) {
            return __engineReaction(18);
        }

        // Prepare array of editData
        $editData = [
              'id' => $address->id,
              'type' => $address->type,
              'address_line_1' => $address->address_line_1,
              'address_line_2' => $address->address_line_2,
              'primary' => ($address->primary) ? true : false,
              'city' => $address->city,
              'state' => $address->state,
              'country' => $address->countries__id,
              'pin_code' => $address->pin_code,
        ];

        // Get address type from config
        $addressType = config('__tech.address_type_list');

        // get countries collection
         $countries = $this->fetchCountries();

        return __engineReaction(1, [
                'address' => $editData,
                'countries' => $countries,
                'addressType' => $addressType,
            ]);
    }

    /**
     * Process Update address request.
     *
     * @param array $addressID, $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function updateAddress($addressID, $input)
    {
        $addressData = $this->addressRepository->fetchAddressByID($addressID);

        // Check if address exist 
        if (empty($addressData)) {
            return __engineReaction(18);
        }

        // get country name 
        $country = $this->supportRepository
                        ->fetchCountry($input['country']);

        // Check if country code exist
        if (!__isEmpty($country['iso_code'])) {
            $input['code'] = $country['iso_code'];
        }

        // prepare array of updateData
        $updateData = [
            'type' => $input['type'],
            'address_line_1' => $input['address_line_1'],
            'address_line_2' => $input['address_line_2'],
            'city' => $input['city'],
            'state' => $input['state'],
            'country' => $input['code'],
            'pin_code' => $input['pin_code'],
            'countries__id' => $input['country'],
        ];

        // Check if primary address exist
        $updateData['primary'] = 1;
        if (!$input['primary']) {
            $updateData['primary'] = 0;
        }

        $address = $this->addressRepository->update($addressData, $updateData);

        // if address store successfully then return reaction
        if ($address) {
            return  __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare address List request.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareOrderAddressList()
    {
        $getAddresses = $this->addressRepository
                              ->fetchAddressesForOrder();

        // Get address type from config
        $addressType = config('__tech.address_type');

        $addresses = [];

        // Check if addess not exist
        if (__isEmpty($getAddresses)) {
            return __engineReaction(2, [
                'message' => __('There are no address.'),
            ]);
        }

        // all address push in addresses array
        foreach ($getAddresses as $address) {

            // get country name 
            $country = $this->supportRepository
                              ->fetchCountry($address->countries__id);

            $addresses[] = [
                'id' => $address->id,
                'type' => $addressType[$address->type],
                'addressID' => $address->id,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $country->name,
                'pin_code' => $address->pin_code,
            ];
        }

        return __engineReaction(1, [
            'addresses' => $addresses,
        ]);
    }

    /**
     * update address primary.
     *
     * @param int $addressID
     * @param $primary
     *
     * @return reaction number
     *---------------------------------------------------------------- */
    public function updateAddressPrimary($addressID)
    {
        // Get address detail
        $address = $this->addressRepository->fetchAddressByID($addressID);

        // Check if address is empty
        if (empty($address)) {
            return __engineReaction(18);
        }

        // if address already primary then return reaction
        if ($address['primary'] == 1) {
            return __engineReaction(14);
        }

        // Update primary data
        $updateData = [
            'primary' => 1,
        ];

        // update address primary
        $addressResponse = $this->addressRepository->updatePrimary($address, $updateData);

        // Check if address update successfully
        if ($addressResponse) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Get login user of primary address.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getUserPrimaryAddress()
    {
        // fetch primary address
        $primaryAddress = $this->addressRepository->fetchUserPrimaryAddress();

        $getPrimaryAddress = [];

        if (!empty($primaryAddress)) {

            // fetch primary address of user
            $countryName = $this->supportRepository
                                ->fetchCountry($primaryAddress->countries__id);

            $getPrimaryAddress = [
                'id' => $primaryAddress->id,
                'address_line_1' => $primaryAddress->address_line_1,
                'address_line_2' => $primaryAddress->address_line_2,
                'type' => $this->addressConfig[$primaryAddress->type],
                'city' => $primaryAddress->city,
                'state' => $primaryAddress->state,
                'country' => $countryName->name,
                'pin_code' => $primaryAddress->pin_code,
                'countryCode' => $primaryAddress->country,
            ];
        }

        return [
            'address' => $getPrimaryAddress,
        ];
    }

    /**
     * get selected country of address.
     *
     * @param string $countryCode
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getUserAddress($addressID)
    {
        // fetch primary address
        $address = $this->addressRepository->fetchAddressForOrder($addressID);

        $getAddress = [];

        if (!empty($address)) {

            // fetch primary address of user
            $countryName = $this->supportRepository
                                ->fetchCountry($address->countries__id);

            $getAddress = [
                'id' => $address->id,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'type' => $this->addressConfig[$address->type],
                'city' => $address->city,
                'state' => $address->state,
                'country' => $countryName->name,
                'pin_code' => $address->pin_code,
                'countryCode' => $address->country,
            ];
        }

        return [
            'address' => $getAddress,
        ];
    }

    /**
     * get selected ID.
     *
     * @param string $addressID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAddressByID($addressID)
    {
        // fetch primary address
        $address = $this->addressRepository->fetchAddressByID($addressID);

        $getAddress = [];

        if (!empty($address)) {

            // fetch primary address of user
            $countryName = $this->supportRepository
                                ->fetchCountry($address->countries__id);

            $getAddress = [
                'id' => $address->id,
                'address_line_1' => $address->address_line_1,
                'address_line_2' => $address->address_line_2,
                'type' => $this->addressConfig[$address->type],
                'city' => $address->city,
                'state' => $address->state,
                'country' => $countryName->name,
                'pin_code' => $address->pin_code,
                'countryCode' => $address->country,
            ];
        }

        return [
            'address' => $getAddress,
        ];
    }

    /**
     * check if address is valid.
     *
     * @param array $data
     * @param array $inputAddress
     *---------------------------------------------------------------- */
    public function checkIsValidAddress($data, $inputAddress)
    {
        if (!array_key_exists('addressID', $inputAddress)) {
            return 3; // address not selected
        }

        if ($inputAddress['sameAddress'] == false and !__ifIsset($inputAddress['addressID1'])) {
            return 4; // address1 not selected
        }

        $addressID = $inputAddress['addressID'];
        $addressID1 = __ifIsset($inputAddress['addressID1'], $inputAddress['addressID1'], $addressID);

        $shippingAddress = $this->addressRepository->fetchAddressByID($addressID);

        // check if address is available
        if (__isEmpty($shippingAddress) or ($shippingAddress->users_id != getUserID())) {
            return 3;
        }

        return [
                'addresses_id' => $shippingAddress->id,
                'addresses_id1' => $addressID1,
                'country' => $shippingAddress->country,
            ];
    }

    /**
     * get adddress by shipping address id & billing address id.
     *
     * @param int $addressId
     * @param int $addressId1
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAddress($addressId, $addressId1)
    {
        $address = $this->addressRepository->fetchAddressByID($addressId);

        $addressData = [];
        $sameAddress = true;

        // check if address is available
        if (!__isEmpty($address)) {

           // fetch primary address of user
            $countryName = $this->supportRepository
                                ->fetchCountry($address->countries__id);

            if ($address->id !== (int) $addressId1) {
                $address1 = $this->addressRepository->fetchAddressByID($addressId1);

                $addressData['billingAddress'] = [
                    'id' => $address1->id,
                    'addressLine1' => $address1->address_line_1,
                    'addressLine2' => $address1->address_line_2,
                    'type' => $this->addressConfig[$address1->type],
                    'city' => $address1->city,
                    'state' => $address1->state,
                    'country' => $countryName->name,
                    'pinCode' => $address1->pin_code,
                    'countryCode' => $address1->country,
                ];

                $sameAddress = false;
            }

            $addressData['shippingAddress'] = [
                'id' => $address->id,
                'addressLine1' => $address->address_line_1,
                'addressLine2' => $address->address_line_2,
                'type' => $this->addressConfig[$address->type],
                'city' => $address->city,
                'state' => $address->state,
                'country' => $countryName->name,
                'pinCode' => $address->pin_code,
                'countryCode' => $address->country,
            ];
        }

        $addressData['sameAddress'] = $sameAddress;

        return $addressData;
    }
}
