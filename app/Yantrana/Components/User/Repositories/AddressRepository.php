<?php
/*
* AddressRepository.php - Repository file
*
* This file is part of the User component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\User\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\User\Models\Address as AddressModel;
use App\Yantrana\Components\User\Blueprints\AddressRepositoryBlueprint;
use Auth;

class AddressRepository extends BaseRepository
                          implements AddressRepositoryBlueprint
{
    /**
     * @var AddressModel - Address Model
     */
    protected $addressModel;

    /**
     * Constructor.
     *
     * @param AddressModel $addressModel - Address Model
     *-----------------------------------------------------------------------*/
    public function __construct(AddressModel $addressModel)
    {
        $this->addressModel = $addressModel;
    }

    /**
     * fetch all addresses of user.
     * 
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAddresses()
    {
        $addressWhere = [
                'status' => 1,
                'users_id' => Auth::id(),
            ];

        return $this->addressModel
                    ->where($addressWhere)
                    ->select(
                             'id',
                             'type',
                             'users_id',
                             'address_line_1',
                             'address_line_2',
                             'primary',
                             'city',
                             'state',
                             'country',
                             'pin_code',
                             'created_at',
                             'countries__id'
                            )->get();
    }

    /**
     * delete address.
     *
     * @param $addressID 
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function delete($addressID)
    {
        if ($this->addressModel->where(['id' => $addressID])
                                  ->update(['status' => 3])) {
            activityLog('ID of '.$addressID.' address deleted.');

            return true;
        }

        return false;
    }

    /**
     * Fetch address by ID.
     *
     * @param array $addressID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAddressByID($addressID)
    {
        return $this->addressModel->where('id', $addressID)->first();
    }

    /**
     * Store new address.
     *
     * @param array $request
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function store($request)
    {
        $address = new AddressModel();
        //check if checkbox true or false
        if (!empty($request['make_primary']) == true) {
            //fetch address data
            $addressWhere = [
                'primary' => 1,
                'users_id' => Auth::id(),
            ];
            //update data
            $addressData = $this->addressModel->where($addressWhere)
                                               ->update(['primary' => 0]);
            $address->primary = 1;
        } else {
            $address->primary = 0;
        }

        $address->users_id = Auth::id();
        $address->address_line_1 = $request['address_line_1'];
        $address->address_line_2 = $request['address_line_2'];
        $address->city = $request['city'];
        $address->state = $request['state'];
        $address->country = $request['code'];
        $address->pin_code = $request['pin_code'];
        $address->type = $request['type'];
        $address->status = 1;
        $address->countries__id = $request['country'];

        if ($address->save()) {
            activityLog('ID of '.$address->id.' address added.');

            return true;
        }

        return false;
    }

    /**
     * update address.
     *
     * @param $address, $updateData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function update($address, $updateData)
    {
        //check if checkbox true or false
        if ($address['primary'] == 0) {

            //fetch address data
            $addressWhere = [
                'primary' => 1,
                'users_id' => Auth::id(),
            ];

            //update data
            $addressData = $this->addressModel
                                    ->where($addressWhere)
                                    ->update(['primary' => 0]);
        }

        if ($address->modelUpdate($updateData)) {
            activityLog('ID of '.$address->id.' address updated.');

            return  true;
        }

        return false;
    }

    /**
     * fetch all addresses of user.
     * 
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAddressesForOrder()
    {
        $addressWhere = [
                'status' => 1,
                'users_id' => Auth::id(),
            ];

        return $this->addressModel
                    ->where($addressWhere)
                    ->select(
                             'id',
                             'type',
                             'address_line_1',
                             'address_line_2',
                             'city',
                             'state',
                             'country',
                             'pin_code',
                             'countries__id'
                            )->get();
    }

    /**
     * make primary address.
     *
     * @param $address
     * @param $updateData
     *---------------------------------------------------------------- */
    public function updatePrimary($address, $updateData)
    {
        //check if checkbox true or false
        if ($updateData['primary']) {

            //fetch address data
            $addressWhere = [
                'primary' => 1,
                'users_id' => Auth::id(),
            ];

            //update data
            $addressData = $this->addressModel
                                    ->where($addressWhere)
                                    ->update(['primary' => 0]);
        }

        return $address->modelUpdate($updateData);
    }

    /**
     * Fetch primary address.
     *
     * @param array $addressID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchUserPrimaryAddress()
    {
        $addressWhere = [
                'status' => 1,
                'users_id' => Auth::id(),
                'primary' => 1,
            ];

        return $this->addressModel->where($addressWhere)
                        ->select(
                            'id',
                            'address_line_1',
                            'address_line_2',
                            'type',
                            'city',
                            'state',
                            'country',
                            'pin_code',
                            'countries__id'
                        )
                        ->first();
    }

    /**
     * Fetch address by using country code.
     *
     * @param array $addressID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAddressForOrder($addressID)
    {
        $addressWhere = [
                'id' => $addressID,
                'status' => 1,
                'users_id' => Auth::id(),
            ];

        return $this->addressModel->where($addressWhere)
                        ->select(
                            'id',
                            'address_line_1',
                            'address_line_2',
                            'type',
                            'city',
                            'state',
                            'country',
                            'pin_code',
                            'countries__id'
                        )
                        ->first();
    }
}
