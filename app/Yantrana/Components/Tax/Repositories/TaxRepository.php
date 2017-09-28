<?php
/*
* TaxRepository.php - Repository file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Tax\Models\Tax as TaxModel;
use App\Yantrana\Components\Tax\Blueprints\TaxRepositoryBlueprint;

class TaxRepository extends BaseRepository
                          implements TaxRepositoryBlueprint
{
    /**
     * @var TaxModel - Tax Model
     */
    protected $taxModel;

    /**
     * Constructor.
     *
     * @param TaxModel $taxModel - Tax Model
     *-----------------------------------------------------------------------*/
    public function __construct(TaxModel $taxModel)
    {
        $this->taxModel = $taxModel;
    }

    /**
     * Fetch taxes datatable source.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchForList()
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => 'label',
                'creation_date' => 'created_at',
                'country' => 'country',
            ],
            'searchable' => [
                'label' => 'tax.label',
                'country' => 'countries.name',
                'applicable_tax' => 'tax.applicable_tax',
                'notes' => 'tax.notes',
            ],
        ];

        return $this->taxModel
                    ->join('countries', 'tax.countries__id', '=', 'countries._id')
                    ->select(
                        'tax._id',
                        'tax.label',
                        'tax.country',
                        'tax.applicable_tax',
                        'tax.type',
                        'tax.status',
                        'tax.created_at',
                        'countries.name'
                    )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Fetch tax detail by id.
     *
     * @param int $taxID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetailByID($taxID)
    {
        return $this->taxModel
                    ->where('_id', $taxID)
                    ->first();
    }

    /**
     * Fetch shipping by countries.
     *
     * @param array $shippingID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCountries()
    {
        return $this->taxModel->pluck('country')->toArray();
    }

    /**
     * Store new tax using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function store($inputData)
    {
        $tax = new $this->taxModel();

        $tax->country = $inputData['code'];
        $tax->label = $inputData['label'];
        $tax->type = $inputData['type'];
        $tax->applicable_tax = (!empty($inputData['applicable_tax']))
                                        ? $inputData['applicable_tax']
                                        : null;

        $tax->notes = !empty($inputData['notes'])
                                        ? $inputData['notes']
                                        : null;
        $tax->status = ($inputData['active'])
                                        ? 1 // active 
                                        : 2; // deactive 
        $tax->countries__id = $inputData['country'];

        // Check if tax added
        if ($tax->save()) {
            activityLog('ID of '.$tax->_id.' tax added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch tax by id.
     *
     * @param int   $taxID
     * @param array $selectOnly
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($taxID, $selectOnly = [])
    {
        $tax = $this->taxModel->where('_id', $taxID);

        // Check if select only exist
        if (!__isEmpty($selectOnly)) {
            return $tax->first($selectOnly);
        }

        return $tax->first();
    }

    /**
     * Update tax using provided data.
     *
     * @param obect $tax
     * @param array $updateData
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function update($tax, $updateData)
    {
        // Check if tax updated
        if ($tax->modelUpdate($updateData)) {
            activityLog('ID of '.$tax->_id.' tax update.');

            return true;
        }

        return false;
    }

    /**
     * Delete tax.
     *
     * @param object $tax
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($tax)
    {
        // Check if tax deleted
        if ($tax->delete()) {
            activityLog('ID of '.$tax->_id.' tax deleted.');

            return  1;
        }

        return  2;
    }

    /**
     * fetch tax by country.
     *
     * @param obect $country
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByConutry($country)
    {
        return $this->taxModel
                    ->where([
                        'status' => 1,
                        'country' => $country,
                    ])
                    ->get();
    }

    /**
     * fetch tax by tax__id.
     *
     * @param number $taxId
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByTaxId($taxId)
    {
        return $this->taxModel
                    ->where([
                        'status' => 1,
                        '_id' => $taxId,
                    ])
                    ->get();
    }

    /**
     * Fetch tax detail by ids.
     *
     * @param int $taxIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByIDs($taxIDs)
    {
        return $this->taxModel
                    ->whereIn('_id', $taxIDs)
                    ->select('notes', 'label')
                    ->get();
    }
}
