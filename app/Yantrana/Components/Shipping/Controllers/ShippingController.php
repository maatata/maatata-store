<?php
/*
* ShippingController.php - Controller file
*
* This file is part of the Shipping component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Shipping\Controllers;

use App\Yantrana\Support\CommonPostRequest;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Shipping\ShippingEngine;
use App\Yantrana\Components\Shipping\Requests\ShippingAddRequest;
use App\Yantrana\Components\Shipping\Requests\ShippingEditRequest;
use App\Yantrana\Components\Shipping\Requests\InternationalShippingUpdateRequest;

class ShippingController extends BaseController
{
    /**
     * @var ShippingEngine - Shipping Engine
     */
    protected $shippingEngine;

    /**
     * Constructor.
     *
     * @param ShippingEngine $shippingEngine - Shipping Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ShippingEngine $shippingEngine)
    {
        $this->shippingEngine = $shippingEngine;
    }

    /**
     * Handle shipping list datatable source.
     *
     * @return json
     *---------------------------------------------------------------- */
    public function index()
    {
        $engineReaction = $this->shippingEngine->prepareList();

        $requireColumns = [
            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            // page type 
            'type' => function ($key) {

                return getTitle($key['type'], '__tech.shipping.typeShow');

            },
            'charges' => function ($key) {

                if ($key['type'] == 1) { // Flat

                    $charges = priceFormat($key['charges']);
                } elseif ($key['type'] == 2) { // Percentage

                    $charges = $key['charges'].'%';
                } else {
                    $charges = '';
                }

                return $charges;
            },
            '_id',
            'status',
            'name',
        ];

        return __dataTable($engineReaction, $requireColumns);
    }

    /**
     * Handle get shipping detail.
     *
     * @param int $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($shippingID)
    {
        $processReaction = $this->shippingEngine->getDetail($shippingID);

        return __processResponse($processReaction, [
                    18 => __('Shipping rule does not exist.'),
                ], null, true);
    }

    /**
     * Handle add shipping request.
     *
     * @param object ShiipingAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addProcess(ShippingAddRequest $request)
    {
        $processReaction = $this->shippingEngine->addProcess($request->all());

        return __processResponse($processReaction);
    }

    /**
     * Handle edit support data request.
     *
     * @param int $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSupportData($shippingID)
    {
        $processReaction = $this->shippingEngine->fetchData($shippingID);

        return __processResponse($processReaction, null, null, true);
    }

    /**
     * Handle countries data request.
     *
     * @param int $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getCountries()
    {
        $processReaction = $this->shippingEngine->fetchAllCountries();

        return __processResponse($processReaction, [], null, true);
    }

    /**
     * Handle edit shipping request.
     *
     * @param object ShippingEditRequest $request
     * @param $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editProcess(ShippingEditRequest $request, $shippingID)
    {
        $processReaction = $this->shippingEngine
                                ->processUpdate($shippingID, $request->all());

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Shipping rule updated successfully.'),
                    14 => __('Nothing update.'),
                    18 => __('Shipping rule does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle delete shipping request.
     *
     * @param $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($shippingID, CommonPostRequest $request)
    {
        if (empty($shippingID)) {
            return __apiResponse([], 7);
        }

        $processReaction = $this->shippingEngine
                                ->processDelete($shippingID);

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('Shipping rule deleted successfully.'),
                    2 => __('Shipping rule not deleted.'),
                    18 => __('Shipping rule does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * Handle get details of international shipping request.
     *
     * @param int $shippingID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getAocSupportData()
    {
        $processReaction = $this->shippingEngine
                                ->getAocData();
        // get engine reaction						
        return __processResponse($processReaction, [
                    18 => __('Shipping rule does not exist.'),
                ], $processReaction['data']);
    }

    /**
     * update record of international shipping.
     *
     * @param param1 type 
     *---------------------------------------------------------------- */
    public function aocProcess(InternationalShippingUpdateRequest $request)
    {
        $processReaction = $this->shippingEngine
                                ->processUpdateAoc($request->all());

        // get engine reaction						
        return __processResponse($processReaction, [
                    1 => __('All Other Country Shipping rule updated successfully.'),
                    14 => __('Nothing update.'),
                ], $processReaction['data']);
    }
}
