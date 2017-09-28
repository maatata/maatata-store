<?php
/*
* ManageStoreRepository.php - Repository file
*
* This file is part of the Store component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Store\Repositories;

use Cache;
use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Store\Models\Setting as StoreSetting;
use App\Yantrana\Components\Store\Blueprints\ManageStoreRepositoryBlueprint;

class ManageStoreRepository extends BaseRepository
                          implements ManageStoreRepositoryBlueprint
{
    /**
     * @var StoreSetting - StoreSetting Model
     */
    protected $storeSetting;

    /**
     * Constructor.
     *
     * @param StoreSetting $storeSetting - StoreSetting Model
     *-----------------------------------------------------------------------*/
    public function __construct(StoreSetting $storeSetting)
    {
        $this->storeSetting = $storeSetting;
    }

    /**
     * Fetch store settings.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetch()
    {
        return $this->storeSetting->select('name', 'value')->get();
    }

    /**
     * Fetch store settings.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSettings()
    {
        return $this->viaCache('cache.storeSetting.namevalue', function () {
            return $this->storeSetting->select('name', 'value')->get();
        });
    }

    /**
     * Add new store settings.
     *
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function addSettings($input)
    {
        $settingNames = config('__tech.store_settings');
        $insertData = [];

        foreach ($input as $key => $value) {
            if (in_array($key, $settingNames)) {
                $insertData[] = [
                    'name' => $key,
                    'value' => $value,
                ];
            }
        }

        if ($this->storeSetting->prepareAndInsert($insertData)) {
            return true;
        }

        return false;
    }

    /**
     * Update store settings.
     *
     * @param object $settingsCollection
     * @param array  $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateSettings($settingsCollection, $input)
    {
        // Get config item
        $settingConfigNames = config('__tech.store_settings');
        $existingSettings = $settingsCollection->pluck('name')->all();
        $updateData = [];
        $inputSettingName = [];
        // Get config setting name and existing setting and check 
        // exist or not if not exist then add in database.
        foreach ($input as $key => $value) {
            if (!empty($existingSettings)) {
                if (in_array($key, $settingConfigNames)
                    and in_array($key, $existingSettings)) {
                    $updateData[] = [
                        'name' => $key,
                        'value' => $value,
                    ];
                }
            } else {
                $updateData[] = [
                    'name' => $key,
                    'value' => $value,
                ];
            }
            $inputSettingName[] = $key;
        }

        // Create new array for setting name
        $settingName = [];
        foreach ($settingsCollection as $setting) {
            $settingName[] = $setting['name'];
        }

        $insertData = [];

        $getNewSettingName = [];

        // Check input setting exist
        // compare database name and input name and return differences 
        if (!empty($inputSettingName)) {
            $newSettingsName = array_diff($inputSettingName, $settingName);

            if (!empty($newSettingsName)) {
                foreach ($newSettingsName as $newName) {
                    if (in_array($newName, $settingConfigNames)) {
                        $getNewSettingName[] = $newName;
                    }
                }
            }
        }

        // Check if input exist and check input data exist in new setting name
        if (!empty($input)) {
            foreach ($input as $inputName => $inputValue) {
                if (in_array($inputName, $getNewSettingName)) {
                    $insertData[] = [
                        'name' => $inputName,
                        'value' => $inputValue,
                    ];
                }
            }
        }

          // if insert data exist then insert new value in database
        if (!empty($insertData)) {
            if ($this->storeSetting->prepareAndInsert($insertData)) {
                return true;
            }
        }

        // if existing setting exist then update data
        if (!empty($existingSettings)) {
            if ($this->storeSetting->batchUpdate($updateData, 'name')) {
                return true;
            }
        } else {
            if ($this->storeSetting->prepareAndInsert($updateData)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Fetch store currency symbol.
     *
     * @return string
     *---------------------------------------------------------------- */
    public function fetchCurrencySymbol()
    {
        $storeCurrency = $this->storeSetting
                              ->where('name', 'currency_symbol')
                              ->select('value')
                              ->first();

        if (empty($storeCurrency)) {
            return '';
        }

        return $storeCurrency->value;
    }

    /**
     * Fetch store currency.
     *
     * @return string
     *---------------------------------------------------------------- */
    public function fetchCurrency()
    {
        $storeCurrency = $this->storeSetting
                              ->where('name', 'currency')
                              ->select('value')
                              ->first();

        if (empty($storeCurrency)) {
            return '';
        }

        return $storeCurrency->value;
    }
}
