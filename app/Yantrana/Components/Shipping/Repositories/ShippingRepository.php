<?php
/*
* ShippingRepository.php - Repository file
*
* This file is part of the Shipping component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Shipping\Models\Shipping as ShippingModel;
use App\Yantrana\Components\Shipping\Blueprints\ShippingRepositoryBlueprint;

class ShippingRepository extends BaseRepository
                          implements ShippingRepositoryBlueprint
{
    /**
     * @var ShippingModel - Shipping Model
     */
    protected $shippingModel;

    /**
     * Constructor.
     *
     * @param ShippingModel $shippingModel - Shipping Model
     *-----------------------------------------------------------------------*/
    public function __construct(ShippingModel $shippingModel)
    {
        $this->shippingModel = $shippingModel;
    }

    /**
     * Fetch shipping datatable source.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchForList()
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => 'country',
                'creation_date' => 'created_at',
                'country' => 'country',
            ],
            'searchable' => [
                '_id' => 'shipping._id',
                'country' => 'countries.name',
                'charges' => 'shipping.charges',
                'free_after_amount' => 'shipping.free_after_amount',
                'amount_cap' => 'shipping.amount_cap',
                'notes' => 'shipping.notes',
            ],
        ];

        return $this->shippingModel
                    ->where('country', '!=', getAocCode())
                    ->join('countries', 'shipping.countries__id', '=', 'countries._id')
                    ->select(
                        'shipping._id',
                        'shipping.country',
                        'shipping.type',
                        'shipping.charges',
                        'shipping.free_after_amount',
                        'shipping.amount_cap',
                        'shipping.status',
                        'shipping.notes',
                        'shipping.created_at',
                        'shipping.updated_at',
                        'shipping.countries__id',
                        'countries.name'
                        )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Fetch shipping detail by id.
     *
     * @param array $shippingID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetail($shippingID)
    {
        return $this->shippingModel
                    ->where('_id', $shippingID)
                    ->first();
    }

    /**
     * Fetch shipping detail by id.
     *
     * @param array $countryCode
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByShippingId($countryCode)
    {
        return $this->shippingModel
                    ->where('country', $countryCode)
                    ->where('status', 1)
                    ->first();
    }

    /**
     * Delete shipping.
     *
     * @param array $shipping
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($shipping)
    {
        // Check if shipping deleted
        if ($shipping->delete()) {
            activityLog('ID of '.$shipping->_id.' shipping deleted.');

            return true;
        }

        return false;
    }

    /**
     * Store new shipping using provided data & return response.
     *
     * @param array $inputData
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function store($inputData)
    {
        $type = $inputData['type'];
        $shipping = new $this->shippingModel();
        $shipping->country = $inputData['code']; // country code
        $shipping->type = $inputData['type'];
        $shipping->notes = (isset($inputData['notes'])) ? $inputData['notes'] : '';
        $shipping->status = ($inputData['active']) ? 1 : 2; // Inactive 
        $shipping->countries__id = (isset($inputData['country'])) ? $inputData['country'] : config('__tech.aoc_id');

        if ($type === 1) {
            $shipping->charges = $inputData['charges'];
            $shipping->free_after_amount = (isset($inputData['free_after_amount']))
                                            ? $inputData['free_after_amount']
                                            : null;
        } elseif ($type === 2) {
            $shipping->charges = $inputData['charges'];
            $shipping->amount_cap = $inputData['amount_cap'];
        }

        // Check if shipping added
        if ($shipping->save()) {
            activityLog('ID of '.$shipping->_id.' shipping added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch shipping by id.
     *
     * @param array $shippingID
     * @param array $selectOnly
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($shippingID, $selectOnly = [])
    {
        $rule = $this->shippingModel->where('_id', $shippingID);

        // Check if selectOnly exist
        if (!__isEmpty($selectOnly)) {
            return $rule->first($selectOnly);
        }

        return $rule->first();
    }

    /**
     * Fetch countries code from shipping table.
     *
     * @param array $shippingID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCountries()
    {
        return $this->shippingModel->get(['country'])->toArray();
    }

    /**
     * Update shipping using provided data.
     *
     * @param obect $shipping
     * @param array $updateData
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function update($shipping, $updateData)
    {
        // Check if shipping updated
        if ($shipping->modelUpdate($updateData)) {
            activityLog('ID of '.$shipping->_id.' shipping updated.');

            return $shipping;
        }

        return false;
    }

    /**
     * fetch AOC shipping.
     *
     * @param string $country
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByConutry($country)
    {
        return  $this->shippingModel
                    ->scopeFetchByCountry($country)
                    ->where('status', 1)
                    ->first();
    }

    /**
     * fetch AOC shipping.
     *
     * @param string $country
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function checkIsValidByConutry($country)
    {
        return  $this->shippingModel
                     ->scopeFetchByCountry($country)
                     ->where('status', 1)
                     ->first();
    }

    /**
     * fetch International country(aoc).
     *
     * @param string $aoc
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByAoc($aoc)
    {
        return $this->shippingModel
                    ->where('country', $aoc)
                    ->first();
    }

    /**
     * fetch country count without aoc.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchWithoutAoc()
    {
        return $this->shippingModel
                    ->where('country', '!=', getAocCode())
                    ->count();
    }
}
