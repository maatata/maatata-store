<?php
/*
* TaxEngine.php - Main component file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax;

use App\Yantrana\Components\Tax\Repositories\TaxRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Tax\Blueprints\TaxEngineBlueprint;

class TaxEngine implements TaxEngineBlueprint
{
    /**
     * @var TaxRepository - Tax Repository
     */
    protected $taxRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * Constructor.
     *
     * @param TaxRepository $taxRepository - Tax Repository
     *-----------------------------------------------------------------------*/
    public function __construct(TaxRepository $taxRepository,
                         SupportRepository $supportRepository)
    {
        $this->taxRepository = $taxRepository;
        $this->supportRepository = $supportRepository;
    }

    /**
     * Prepare taxes list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        return $this->taxRepository->fetchForList();
    }

    /**
     * Prepare detail tax data.
     *
     * @param array $taxID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchDetail($taxID)
    {
        // Get tax detail 
        $tax = $this->taxRepository
                    ->fetchDetailByID($taxID);

        // Check if tax is empty
        if (empty($tax)) {
            return __engineReaction(18);
        }

        // get countries data
        $countries = $this->supportRepository
                          ->fetchCountry($tax->countries__id);

        // Get tax type from config 
        $taxType = config('__tech.tax.type');

        // Prepare array of taxData
        $taxData = [
            'country' => $countries['name'],
            'label' => $tax->label,
            'taxType' => $tax->type,
            'type' => $taxType[$tax->type],
            'applicable_tax' => $tax->applicable_tax,
            'notes' => $tax->notes,
            'active' => ($tax->status == 1) ? true : false,
        ];

        return __engineReaction(1, [
                'taxData' => $taxData,
                'currencySymbol' => getCurrencySymbol(),
            ]);
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

        // set key value pair of country name and ID
        foreach ($countries as $key => $country) {
            $countriesCollection[] = [
                'value' => $country['_id'],
                'text' => $country['name'],
            ];
        }

        return __engineReaction(1, [
                    'countries' => $countriesCollection,
                    'currencySymbol' => getCurrencySymbol(),
                    'currency' => getCurrency(),
                    'taxType' => config('__tech.tax.type'),
                ]);
    }

    /**
     * add new tax.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addProcess($inputData)
    {
        $country = $this->supportRepository
                          ->fetchCountry($inputData['country']);

        // Check if country code exist  
        if (__isEmpty($country)) {
            return __engineReaction(2, null, __('Invalid Country Selected.'));
        }

        $inputData['code'] = $country['iso_code'];

        // If tax addded then return reaction code for success
        if ($this->taxRepository->store($inputData)) {
            return __engineReaction(1, null, __('Tax added successfully.'));
        }

        return __engineReaction(2, null, __('oh..no. error.'));
    }

    /**
     * Prepare edit tax data.
     *
     * @param array $taxID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($taxID)
    {
        $tax = $this->taxRepository
                    ->fetchByID(
                        $taxID,
                        [
                            'label',
                            'country',
                            'applicable_tax',
                            'type',
                            'status',
                            'notes',
                            'countries__id',
                        ]
                    );

        // If tax is empty then retuen not exist reaction code
        if (__isEmpty($tax)) {
            return __engineReaction(18);
        }

        // Prepare array taxdata
        $taxData = [
            '_id' => $taxID,
            'country' => $tax->countries__id,
            'label' => $tax->label,
            'type' => $tax->type,
            'applicable_tax' => $tax->applicable_tax,
            'notes' => $tax->notes,
            'active' => ($tax->status == 1) ? true : false,
        ];

        // get countries list
        $countriesCollection = $this->fetchCountries();

        return __engineReaction(1, [
                'countries' => $countriesCollection,
                'taxData' => $taxData,
                'currencySymbol' => getCurrencySymbol(), // Get currency symbol of store,
                'currency' => getCurrency(),
                'taxType' => config('__tech.tax.type'),
            ]);
    }

    /**
     * Process update tax if tax exist.
     *
     * @param int   $taxID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($taxID, $inputData)
    {
        // Get tax detail from database using tax ID
        $tax = $this->taxRepository->fetchByID($taxID);

        // Check if tax is empty then return not exist reaction code
        if (empty($tax)) {
            return __engineReaction(18, null, __('Tax does not exist.'));
        }

        $country = $this->supportRepository
                            ->fetchCountry($inputData['country']);

        // Check if country code exist
        if (__isEmpty($country['iso_code'])) {
            return __engineReaction(14, null, __('Invalid Country Code.'));
        }

        $inputData['code'] = $country['iso_code'];

        $status = 1;

        // Check if tax is not active
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        // Prepare array of tax data for update process
        $updateData = [
            'country' => $inputData['code'],
            'type' => $inputData['type'],
            'label' => $inputData['label'],
            'notes' => $inputData['notes'],
            'status' => $status,
            'countries__id' => $inputData['country'],
            'applicable_tax' => $inputData['applicable_tax'],
        ];

        // Check if tax type is equal to 3 (No tax)
        /*if ($inputData['type'] == 3) {

            $updateData['applicable_tax'] 	= null;
            
        } else { // Check if tax type not 3 (percentage %)

            $updateData['applicable_tax']   = $inputData['applicable_tax'];

        }*/

        // If tax updated then return success reaction code
        if ($this->taxRepository->update($tax, $updateData)) {
            return __engineReaction(1, null, __('Tax updated successfully.'));
        }

        return __engineReaction(14, null, __('Nothing update.'));
    }

    /**
     * process of delete tax.
     *
     * @param int $taxID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($taxID)
    {
        $tax = $this->taxRepository->fetchByID($taxID);

        // If tax does not exist then return not exist reaction code
        if (empty($tax)) {
            return __engineReaction(18);
        }

        // If Tax Deleted successfully then return engine reaction
        if ($this->taxRepository->delete($tax)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * This function perform addion of taxes with cart total price.
     *
     * @param string $country
     * @param float  $cartTotalPrice
     * @param float  $discountAddedPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function additionOfTaxses($country, $cartTotalPrice, $discountAddedPrice)
    {
        $taxCollection = $this->taxRepository->fetchByConutry($country);

        // If No tax Available For Given Country Code
        if (__isEmpty($taxCollection)) {
            return [
                'info' => '',
                'totalPrice' => __isEmpty($discountAddedPrice)
                                    ? $cartTotalPrice
                                    : $discountAddedPrice,
            ];
        }

        $applicableTaxAmount[] = 0;
        $taxData = [];
        $totalTaxAmount = 0;

        foreach ($taxCollection as $key => $tax) {
            $calculatedAmount = 0;

            //tax type 1 means flat amount
            if ($tax->type == 1) {
                $calculatedAmount = $tax->applicable_tax;
                $applicableTaxAmount[] = $calculatedAmount;
            }

            //tax type 2 means percentage
            if ($tax->type == 2) {
                $calculatedAmount = ($tax->applicable_tax / 100) * $cartTotalPrice;
                $applicableTaxAmount[] = $calculatedAmount;
            }

            $taxData[$key] = [
                'id' => $tax->_id,
                'label' => $tax->label,
                'notes' => $tax->notes,
                'type' => $tax->type,
                'applicableTax' => $tax->applicable_tax,
                'formatedTax' => priceFormat($calculatedAmount),
                'amount' => $calculatedAmount,
            ];
        }

        $totalTaxAmount = array_sum($applicableTaxAmount);

        // if discount amount is present then the price calculate with discount amount but 
        // condition check with base cart order price $cartTotalPrice  
        $price = __isEmpty($discountAddedPrice) ? $cartTotalPrice : $discountAddedPrice;

        return [
            'info' => $taxData,
            'totalPrice' => $totalTaxAmount + $price,
        ];
    }

    /**
     * get tax infromation.
     *
     * @param array $taxsesData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getTaxInformation($taxsesData, $currency)
    {
        if (__isEmpty($taxsesData)) {
            return [
                    'info' => '',
                ];
        }

        $taxIds = $taxAmount = [];

        foreach ($taxsesData as $tax) {
            $taxIds[] = $tax->tax__id;
            $taxAmount[] = $tax->amount;
        }

        $taxCollection = $this->taxRepository->fetchByIDs($taxIds);

        if (__isEmpty($taxCollection)) {
            return [
                'info' => '',
            ];
        }

        $taxArray = [];

        foreach ($taxCollection as $key => $tax) {
            $amount = $taxAmount[$key];
            $taxArray[] = [
                'label' => $tax->label,
                'notes' => $tax->notes,
                'taxAmount' => $amount,
                'formatedTaxAmount' => orderPriceFormat($amount, $currency),
            ];
        }

        return [
            'info' => $taxArray,
        ];
    }
}
