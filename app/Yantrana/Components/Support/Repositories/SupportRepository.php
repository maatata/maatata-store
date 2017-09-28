<?php
/*
* SupportRepository.php - Repository file
*
* This file is part of the Support component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Support\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Support\Models\Support as SupportModel;
use App\Yantrana\Components\Support\Models\Country as CountryModel;
use App\Yantrana\Components\Support\Blueprints\SupportRepositoryBlueprint;

class SupportRepository extends BaseRepository
                          implements SupportRepositoryBlueprint
{
    /**
     * @var SupportModel - Support Model
     */
    protected $supportModel;

    /**
     * @var CountryModel - Country Model
     */
    protected $countrytModel;

    /**
     * Constructor.
     *
     * @param SupportModel $supportModel  - Support Model
     * @param CountryModel $countrytModel - Country Model
     *-----------------------------------------------------------------------*/
    public function __construct(SupportModel $supportModel, CountryModel $countrytModel)
    {
        $this->supportModel = $supportModel;
        $this->countrytModel = $countrytModel;
    }

    /**
     * Fetch all countries.
     *---------------------------------------------------------------- */
    public function fetchCountries()
    {
        return $this->countrytModel
                    ->get(['_id', 'iso_code', 'name'])
                    ->toArray();
    }

    /**
     * Fetch country detail.
     *
     * @param number $countryID
     *---------------------------------------------------------------- */
    public function fetchCountry($countryID)
    {
        return $this->countrytModel
                    ->where('_id', $countryID)
                    ->first(['iso_code', 'name']);
    }
}
