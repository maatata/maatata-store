<?php
/*
* TaxController.php - Controller file
*
* This file is part of the Tax component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Tax\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Tax\TaxEngine;
use App\Yantrana\Components\Tax\Requests\TaxAddRequest;
use App\Yantrana\Components\Tax\Requests\TaxEditRequest;

class TaxController extends BaseController
{
    /**
     * @var TaxEngine - Tax Engine
     */
    protected $taxEngine;

    /**
     * Constructor.
     *
     * @param TaxEngine $taxEngine - Tax Engine
     *-----------------------------------------------------------------------*/
    public function __construct(TaxEngine $taxEngine)
    {
        $this->taxEngine = $taxEngine;
    }

    /**
     * Handle tax tabular data request.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index()
    {
        $engineReaction = $this->taxEngine->prepareList();

        $requireColumns = [
            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            // page type 
            'type' => function ($key) {

                return getTitle($key['type'], '__tech.tax.type');

            },
            'applicable_tax' => function ($key) {

                if ($key['type'] == 1) { // Flat 

                    $applicableTax = priceFormat($key['applicable_tax']);
                } elseif ($key['type'] == 2) { // Percentage 

                    $applicableTax = $key['applicable_tax'].'%';
                } else {
                    $applicableTax = '';
                }

                return $applicableTax;
            },
            '_id',
            'label',
            'status',
            'name',
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * get tax detail.
     *
     * @param int $taxID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($taxID)
    {
        $processReaction = $this->taxEngine->fetchDetail($taxID);

        return __processResponse($processReaction, [
                    18 => __('Tax does not exist.'),
                ], null, true);
    }

    /**
     * Handle countries list request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getCountries()
    {
        $processReaction = $this->taxEngine->fetchCountries();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle add tax request.
     *
     * @param object TaxAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(TaxAddRequest $request)
    {
        $processReaction = $this->taxEngine->addProcess($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Handle edit support data request.
     *
     * @param int $taxID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($taxID)
    {
        $processReaction = $this->taxEngine->fetchData($taxID);

        return __processResponse($processReaction, [
                    18 => __('Tax does not exist.'),
                ], null, true);
    }

    /**
     * Handle edit tax request.
     *
     * @param object TaxEditRequest $request
     * @param int                   $taxID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editProcess(TaxEditRequest $request, $taxID)
    {
        $processReaction = $this->taxEngine->processUpdate($taxID, $request->all());

        return __processResponse($processReaction);
    }

    /**
     * Handle delete tax request.
     *
     * @param $taxID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($taxID, Request $request)
    {
        $processReaction = $this->taxEngine->processDelete($taxID);

        return __processResponse($processReaction, [
                    1 => __('Tax deleted successfully.'),
                    2 => __('Something went wrong.'),
                    18 => __('Tax does not exist.'),
                ]);
    }
}
