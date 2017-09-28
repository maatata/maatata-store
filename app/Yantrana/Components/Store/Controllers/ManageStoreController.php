<?php
/*
* ManageStoreController.php - Controller file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Controllers;

use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Store\ManageStoreEngine;
use App\Yantrana\Components\Store\Requests\EditStoreSettingsRequest;

class ManageStoreController extends BaseController
{
    /**
     * @var ManageStoreEngine - ManageStore Engine
     */
    protected $manageStoreEngine;

    /**
     * Constructor.
     *
     * @param ManageStoreEngine $manageStoreEngine - ManageStore Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreEngine $manageStoreEngine)
    {
        $this->manageStoreEngine = $manageStoreEngine;
    }

    /**
     * Get settings edit support data.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function settingsEditSupportData($formType)
    {
        $processReaction = $this->manageStoreEngine
                                ->prepareSettingsEditSupportData($formType);

        return __processResponse($processReaction, [
            ], $processReaction['data']);
    }

    /**
     * Handle edit store settings request.
     *
     * @param object EditStoreSettingsRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSettings(EditStoreSettingsRequest $request, $formType)
    {
        $processReaction = $this->manageStoreEngine
                                ->processEditStoreSettings($request->all(), $formType);

        return __processResponse($processReaction, [
                   1 => __('Settings updated successfully.'),
                3 => __('Please select the logo.'),
                4 => __('File has an invalid extension, it should be png.'),
                14 => __('Settings not updated.'),
             ], $processReaction['data']);
    }
}
