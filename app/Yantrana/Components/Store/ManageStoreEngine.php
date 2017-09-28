<?php
/*
* ManageStoreEngine.php - Main component file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store;

use App\Yantrana\Components\Store\Repositories\ManageStoreRepository;
use App\Yantrana\Components\Store\Blueprints\ManageStoreEngineBlueprint;
use App\Yantrana\Components\Media\MediaEngine;

class ManageStoreEngine implements ManageStoreEngineBlueprint
{
    /**
     * @var ManageStoreRepository - ManageStore Repository
     */
    protected $manageStoreRepository;

    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * Constructor.
     *
     * @param ManageStoreRepository $manageStoreRepository - ManageStore Repository
     * @param MediaEngine           $mediaEngine           - Media Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageStoreRepository $manageStoreRepository,
     MediaEngine $mediaEngine)
    {
        $this->manageStoreRepository = $manageStoreRepository;
        $this->mediaEngine = $mediaEngine;
    }

    /**
     * get require data for form.
     *
     * @param string $data
     * @param string $string
     *---------------------------------------------------------------- */
    protected function checkIsEmpty($data, $string)
    {
        return isset($data[$string]) ? $data[$string] : '';
    }

    /**
     * check the data is true or false.
     *
     *  @param array $data
     *  @param string $string
     *---------------------------------------------------------------- */
    protected function checkIsValid($data, $string)
    {
        return isset($data[$string]) ? $data[$string] : true;
    }

    /**
     * Prepare settings edit support data.
     * 
     * @param string $formType
     * 
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSettingsEditSupportData($formType)
    {
        $settings = [];
        $requestSettingData = [];
        $settingsCollection = $this->manageStoreRepository->fetch();

        // Check if setting collection exist
        if (!empty($settingsCollection)) {
            foreach ($settingsCollection as $setting) {
                $name = $setting->name;
                $value = $setting->value;

                $settings[$name] = $value;
            }

             // general tab 
            if ($formType == 'general') {
                $homePage = $this->checkIsEmpty($settings, 'home_page');
                $requestSettingData = [
                    'store_name' => $this->checkIsEmpty($settings, 'store_name'),
                ];
            }
        }

        return __engineReaction(1, [
            'store_settings' => $requestSettingData,
        ]);
    }

    /**
     * Process edit store settings.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditStoreSettings($input, $formType)
    {
        $reactionCode = $this->manageStoreRepository
                             ->processTransaction(function () use ($input, $formType) {

            $settingsCollection = $this->manageStoreRepository->fetch();

            // Check if store settings empty
            if (empty($settingsCollection)) {
                if ($this->manageStoreRepository->addSettings($input)) {
                    return 1;
                }

                return 14;
            }

            $settingUpdate = $this->manageStoreRepository
                                   ->updateSettings($settingsCollection, $input);

            return 14;

        });

        if ($reactionCode == 1) {
            return __engineReaction(1, [
                    'message' => __('Settings updated successfully.'),
                    'textMessage' => __('To take a effect updated settings, please reload page.'),
                    ]);
        }

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare store settings.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function prepareStoreSettings()
    {
        return $this->manageStoreRepository->fetchSettings();
    }
}
