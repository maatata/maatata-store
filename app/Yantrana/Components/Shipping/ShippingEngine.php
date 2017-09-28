<?php
/*
* ShippingEngine.php - Main component file
*
* This file is part of the Shipping component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping;

use App\Yantrana\Components\Shipping\Repositories\ShippingRepository;
use App\Yantrana\Components\Support\Repositories\SupportRepository;
use App\Yantrana\Components\Shipping\Blueprints\ShippingEngineBlueprint;
use Config;

class ShippingEngine implements ShippingEngineBlueprint
{
    /**
     * @var ShippingRepository - Shipping Repository
     */
    protected $shippingRepository;

    /**
     * @var SupportRepository - Support Repository
     */
    protected $supportRepository;

    /**
     * Constructor.
     *
     * @param ShippingRepository $shippingRepository - Shipping Repository
     * @param SupportRepository  $supportRepository  - Support Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ShippingRepository $shippingRepository,
                         SupportRepository $supportRepository)
    {
        $this->shippingRepository = $shippingRepository;
        $this->supportRepository = $supportRepository;
    }

    /**
     * get prepare shippings list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        return $this->shippingRepository
                    ->fetchForList();
    }

    /**
     * Prepare shipping rule detail.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getDetail($shippingID)
    {
        $shipping = $this->shippingRepository
                         ->fetchDetail($shippingID);

        // check if shipping rule is empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        // get all countries name and id from database 
        $countries = $this->supportRepository
                          ->fetchCountry($shipping->countries__id);

        // Get shipping type from config
        $shippingType = config('__tech.shipping.typeShow');

        // Create array for all shipping data 
        $shippingData = [
            'country' => $countries->name,
            'shippingType' => $shipping->type,
            'type' => $shippingType[$shipping->type],
            'charges' => $shipping->charges,
            'free_after_amount' => $shipping->free_after_amount,
            'amount_cap' => $shipping->amount_cap,
            'notes' => $shipping->notes,
        ];

        return __engineReaction(1, [
                'shippingData' => $shippingData,
                'currencySymbol' => getCurrencySymbol(), // Get currency symbol of store
            ]);
    }

    /**
     * get countries for add.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchAllCountries()
    {
        // get countries array
        $countries = $this->supportRepository->fetchCountries();

        // get save country code from shipping table
        $shippingCountries = $this->shippingRepository->fetchCountries();

        $allCountriesCode = [];
        $countriesCollection = [];

        // Create array of countries code
        foreach ($shippingCountries as $countryCode) {
            $allCountriesCode[] = $countryCode['country'];
        }

        // Create key value pair of countries and remove exist country
        foreach ($countries as $country) {

            // Check in array country code exist or not
            // if exist then not include that country in countries list
            if (!in_array($country['iso_code'], $allCountriesCode) == true) {
                $countriesCollection[] = [
                            'value' => $country['_id'],
                            'text' => $country['name'],
                        ];
            }
        }

        return __engineReaction(1, [
                        'countries' => $countriesCollection,
                        'currencySymbol' => getCurrencySymbol(),
                        'currency' => getCurrency(),
                        'shippingType' => config('__tech.shipping.type'),
                    ]);
    }

    /**
     * Process add new shipping rule.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addProcess($inputData)
    {
        // get country code 
        $country = $this->supportRepository->fetchCountry($inputData['country']);

        // If country empty then show message
        if (__isEmpty($country)) {
            return __engineReaction(2, null, __('Invalid country code.'));
        }

        $inputData['code'] = $country['iso_code'];

        // Check if shipping rule added then return on reaction code
        if ($this->shippingRepository->store($inputData)) {
            return __engineReaction(1, null, __('Shipping rule added successfully.'));
        }

        return __engineReaction(2, null, __('Shipping rule not added'));
    }

    /**
     * Prepare edit support data.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($shippingID)
    {
        // Get shipping data
        $shipping = $this->shippingRepository
                         ->fetchByID(
                            $shippingID,
                            [
                                'country',
                                'type',
                                'charges',
                                'free_after_amount',
                                'amount_cap',
                                'status',
                                'notes',
                                'countries__id',
                            ]
                        );

        // If Shipping Rule Empty then return 404 reaction code
        if (__isEmpty($shipping)) {
            return __engineReaction(18, null, __('Shipping rule does not exist.'));
        }

        // get countries name 
        $country = $this->supportRepository->fetchCountry($shipping->countries__id);

        // Create array for all shipping data 
        $shippingData = [
            '_id' => $shippingID,
            'country' => !__isEmpty($country) ? $country->name : '',
            'type' => $shipping->type,
            'charges' => $shipping->charges,
            'free_after_amount' => $shipping->free_after_amount,
            'amount_cap' => $shipping->amount_cap,
            'notes' => $shipping->notes,
            'active' => ($shipping->status == 1) ? true : false,
        ];

        return __engineReaction(1, [
                'shippingData' => $shippingData,
                'currencySymbol' => getCurrencySymbol(), // Store currency symbol
                'currency' => getCurrency(),
                'shippingType' => config('__tech.shipping.type'),
            ]);
    }

    /**
     * Process update shipping rule.
     *
     * @param int   $shippingID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($shippingID, $inputData)
    {
        // Get shipping data from database
        $shipping = $this->shippingRepository->fetchByID($shippingID);

        // Check if shipping empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        $status = 1;

        // Check if status is not active 
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        $updateData = [
            'type' => $inputData['type'],
            'notes' => $inputData['notes'],
            'status' => $status,
        ];

        $type = $inputData['type'];

        // Check if shipping type is 1 (Flat)
        if ($type == 1) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = (isset($inputData['free_after_amount']))
                                                ? $inputData['free_after_amount']
                                                : null;
        } elseif ($type == 2) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = $inputData['amount_cap'];
        } elseif ($type == 3 or $type == 4) {
            $updateData['charges'] = null;
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = null;
        }

        // Check if shipping updated or not
        if ($this->shippingRepository->update($shipping, $updateData)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Process of delete shipping rule.
     *
     * @param int $shippingID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($shippingID)
    {
        // Get shipping by shipping ID
        $shipping = $this->shippingRepository->fetchByID($shippingID);

        // Check if shipping is empty
        if (empty($shipping)) {
            return __engineReaction(18);
        }

        // Check if shipping deleted
        if ($this->shippingRepository->delete($shipping)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare all other country data (Aoc).
     *---------------------------------------------------------------- */
    public function getAocData()
    {
        // Get All Other countries data from database
        $aocShipping = $this->shippingRepository->fetchByAoc(getAocCode());

        // Create all other countries shipping array
        $shipping = [
            'country' => getAocCode(),
            'type' => isset($aocShipping->type)
                                    ? $aocShipping->type
                                    : 1,
            'charges' => isset($aocShipping->charges)
                                    ? $aocShipping->charges
                                    : null,
            'free_after_amount' => isset($aocShipping->free_after_amount)
                                    ? $aocShipping->free_after_amount
                                    : null,
            'amount_cap' => isset($aocShipping->amount_cap)
                                    ? $aocShipping->amount_cap
                                    : null,
            'notes' => isset($aocShipping->notes)
                                    ? $aocShipping->notes
                                    : '',
        ];

        // get config Items for shipping
        $configItems = [
            'shippingType' => config('__tech.shipping.type'),
            'storeCurrencySymbol' => getCurrencySymbol(),
            'currency' => getCurrency(),
            ];

        return __engineReaction(1, [
                        'shipping' => $shipping,
                        'configItems' => $configItems,
                    ]);
    }

    /**
     *  process of update record of All other Country shipping (Aoc).
     *
     * @param array $inputData
     *---------------------------------------------------------------- */
    public function processUpdateAoc($inputData)
    {
        $inputData['status'] = 1; // active
        $inputData['active'] = 1; // active
        $inputData['code'] = getAocCode();
        $inputData['country'] = null;
        $updateData['country'] = getAocCode();

        $updateData = [
            'type' => $inputData['type'],
            'notes' => $inputData['notes'],
        ];

        // get input data shipping type
        $shippingType = $inputData['type'];

        // Check if type is 3 (Free) or 4 (Not Shippable)
        if ($shippingType == 3 or $shippingType == 4) {
            $updateData['charges'] = null;
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = null;
        }

        // Check if type is 1 (Flat)
        if ($shippingType == 1) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = $inputData['free_after_amount'];
            $updateData['amount_cap'] = null;
        }

        // Check if type is 2 (Percentage)
        if ($shippingType == 2) {
            $updateData['charges'] = $inputData['charges'];
            $updateData['free_after_amount'] = null;
            $updateData['amount_cap'] = $inputData['amount_cap'];
        }

        // Get aoc Data from database
        $aocCollection = $this->shippingRepository->fetchByAoc(getAocCode());

        // Check if aoc is empty 
        // if aoc not exist in database then store new aoc data                     
        if (__isEmpty($aocCollection)) {

            // Check id aoc saved
            if ($this->shippingRepository->store($inputData)) {
                return __engineReaction(1);
            }

            return __engineReaction(14);
        }

        // if aoc already exist then update aoc data and
        // Check if aoc updated
        if ($this->shippingRepository->update($aocCollection, $updateData)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Get shipping data base on country code.
     *
     * @param string $country
     *
     * @return collection object
     *---------------------------------------------------------------- */
    public function getShipping($country)
    {
        $shipping = $this->shippingRepository
                         ->checkIsValidByConutry($country);

        // If shipping not available for given country then get from all country shipping rule    
        if (__isEmpty($shipping)) {
            $shipping = $this->shippingRepository
                             ->fetchByConutry(getAocCode());
        }

        return $shipping;
    }

    /**
     * This function perform add ion of shipping with cart total price.
     *
     * @param string $country
     * @param float  $cartTotalPrice
     * @param float  $discountAddedPrice
     *
     * @return array
     *---------------------------------------------------------------- */
    public function addShipping($country, $cartTotalPrice, $discount)
    {
        // fetch shipping data base on country code
        $shipping = $this->getShipping($country);

        if (__isEmpty($shipping)) {
            $subtotal = $cartTotalPrice - $discount;

            return [
                'info' => '',
                'totalPrice' => 0,
                'formettedDiscountPrice' => $subtotal,
            ];
        }

        $shippingAmount = 0;
        $shippingType = 0;

        $shippingType = $shipping->type;

        // shipping type 1 means flat amount
        if ($shipping->type == 1) {
            if ($shipping->free_after_amount > $cartTotalPrice or
                $shipping->free_after_amount == 0) {
                $shippingAmount = $shipping->charges;
            }
        }

        // shipping type 2 means percentage
        if ($shipping->type == 2) {
            $shippingAmount = ($shipping->charges / 100) * $cartTotalPrice;

            if (!empty($shipping->amount_cap)) {
                if ($shipping->amount_cap < $shippingAmount) {
                    $shippingAmount = $shipping->amount_cap;
                } else {
                    $shippingAmount = $shippingAmount;
                }
            }
        }

        // Check if shipping calculated amount is not empty
        $shipping['shippingAmt'] = $shippingAmount;
        $shipping['formattedShippingAmt'] = priceFormat($shippingAmount);

        // calculated sub total when subtract discoount from cart total
        if (!__isEmpty($discount)) {
            $discountAddedPrice = $cartTotalPrice - $discount;
        } else {
            $discountAddedPrice = null;
        }

        // if descount amount is preset then the price calculate with descount amount but 
        // condition check with base cart order price $cartTotalPrice  
        $price = __isEmpty($discountAddedPrice) ? $cartTotalPrice : $discountAddedPrice;

        // return after addition of shipping price in cart
        $afterAddShipping = $price + $shippingAmount; // addition of shipping price

        // how many shipping are applied on order amount

        $shipping['shippingAmount'] = ($afterAddShipping === $cartTotalPrice) ? null : $afterAddShipping;

        return [
            'info' => $shipping,
            'totalPrice' => $afterAddShipping,
            'discountAddedPrice' => $discountAddedPrice,
            'formettedDiscountPrice' => priceFormat($discountAddedPrice),
        ];
    }

    /**
     * This function perform addion of shipping with cart total price.
     *
     * @param string $country
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getShippingInformation($country)
    {
        // fetch shipping data base on country code
        $shipping = $this->shippingRepository->fetchByAoc($country);

        if (__isEmpty($shipping)) {
            return [
                'info' => '',
            ];
        }

        return [
                'info' => [
                    '_id' => $shipping->_id,
                    'charges' => $shipping->charges,
                    'type' => $shipping->type,
                    'notes' => $shipping->notes,
                ],
            ];
    }
}
