<?php
/*
* BrandRepository.php - Repository file
*
* This file is part of the Brand component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Brand\Models\Brand as BrandModel;
use App\Yantrana\Components\Brand\Blueprints\BrandRepositoryBlueprint;

class BrandRepository extends BaseRepository
                          implements BrandRepositoryBlueprint
{
    /**
     * @var BrandModel - Brand Model
     */
    protected $brandModel;

    /**
     * Constructor.
     *
     * @param BrandModel $brandModel - Brand Model
     *-----------------------------------------------------------------------*/
    public function __construct(BrandModel $brandModel)
    {
        $this->brandModel = $brandModel;
    }

    /**
     * Store new brand using provided data.
     *
     * @param array $inputData
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function store($inputData)
    {
        $brand = new $this->brandModel();
        $brand->name = $inputData['name'];
        $brand->logo = $inputData['logo'];
        $brand->description = isset($inputData['description'])
                                ? $inputData['description']
                                : null;
        $brand->status = ($inputData['status']) ? 1 // active 
                                 : 2; // deactive 

        //Check if brand added
        if ($brand->save()) {
            activityLog('ID of '.$brand->_id.' brand added.');

            return $brand;
        }

        return false;
    }

    /**
     * Fetch brands datatable source.
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchForList()
    {
        $dataTableConfig = [
            'fieldAlias' => [
                '_id' => 'name',
                'creation_date' => 'created_at',
                'name' => 'name',
            ],
            'searchable' => [
                '_id' => '_id',
                'creation_date' => 'created_at',
                'name' => 'name',
            ],
        ];

        return $this->brandModel->with('productCount')->dataTables($dataTableConfig)->toArray();
    }

    /**
     * Fetch brand by id.
     *
     * @param array $brandID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($brandID)
    {
        return $this->brandModel->find($brandID);
    }

    /**
     * Fetch brand by id.
     *
     * @param array $brandID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function delete($brandID)
    {
        if ($this->brandModel->where('_id', $brandID)->deleteIt()) {
            activityLog('ID of '.$brandID.' brand delete.');

            return  true;
        }

        return  false;
    }

    /**
     * update brand.
     *
     * @param $brand 
     * @param $updateData
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function update($brand, $updateData)
    {
        if ($brand->modelUpdate($updateData)) {
            activityLog('ID of '.$brand->_id.' brand update.');

            return $brand;
        }

        return false;
    }

    /**
     * fetch active brand.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchIsActive()
    {
        return $this->fetchAllActive();
    }

    /**
     * fetch active brand by ID.
     *
     * @param $brandId 
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchIsActiveByID($brandId)
    {
        return $this->brandModel
                    ->fetchByID($brandId)
                    ->active()
                    ->first();
    }

    /**
     * fetch active brand by IDs.
     *
     * @param $brandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandByIDs($brandIds)
    {
        return $this->brandModel
                    ->fetchByIDs($brandIds)
                    ->active()->get();
    }

    /**
     * fetch brand by ids.
     *
     * @param $brandIds
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrand($brandIds)
    {
        return $this->brandModel->whereIn('_id', $brandIds)->active()
                                ->select('_id as brandID', 'name as brandName')
                                ->get();
    }

    /**
     * Fetch active brands.
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchCount()
    {
        return $this->brandModel
                    ->count();
    }

    /**
     * fetch all active brands list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchActiveWithoutCache()
    {
        return $this->brandModel->whereStatus(1)->get();
    }

    /**
     * fetch all active brands list.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllActive()
    {
        return $this->viaCache('cache.brands.all.active', function () {
            return $this->brandModel->orderBy('name', 'asc')->whereStatus(1)->get();
        });
    }

    /**
     * fetch inactive brand lists.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchInactiveBrand()
    {
        return $this->viaCache('cache.brands.all.inactive', function () {
            return $this->brandModel->whereStatus(2)->pluck('_id')->toArray();
        });
    }
}
