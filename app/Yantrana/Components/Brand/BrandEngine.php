<?php
/*
* BrandEngine.php - Main component file
*
* This file is part of the Brand component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Brand;

use Hash;
use Auth;
use Breadcrumb;
use App\Yantrana\Components\Media\MediaEngine;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Brand\Blueprints\BrandEngineBlueprint;
use App\Yantrana\Components\Product\Repositories\ManageProductRepository;

class BrandEngine implements BrandEngineBlueprint
{
    /**
     * @var BrandRepository - Brand Repository
     */
    protected $brandRepository;

    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * Constructor.
     *
     * @param BrandRepository         $brandRepository         - Brand Repository
     * @param ManageProductRepository $manageProductRepository - ManageProduct Repository
     *-----------------------------------------------------------------------*/
    public function __construct(BrandRepository $brandRepository,
        MediaEngine $mediaEngine,
        ManageProductRepository $manageProductRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->mediaEngine = $mediaEngine;
        $this->manageProductRepository = $manageProductRepository;
    }

    /**
     * get prepare brands list.
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareList()
    {
        return $this->brandRepository
                        ->fetchForList();
    }

    /**
     * fetch brand data.
     *
     * @param int $brandID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareDetail($brandID)
    {
        // Get brand data by brand ID
        $brand = $this->brandRepository->fetchByID($brandID);

        // Check if brand is empty
        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        // prepare brandData array
        $brandData = [
            'name' => $brand->name,
            'description' => $brand->description,
            'active' => ($brand->status == 1) ? true : false,
        ];

        return __engineReaction(1, $brandData);
    }

    /**
     * add new brand.
     *
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAdd($inputData)
    {
        $logo = $inputData['logo'];

            // Check if selected product logo thumbnail exist
        if (!$this->mediaEngine->isUserTempMedia($logo)) {
            return __engineReaction(3);
        }

        $brand = $this->brandRepository->store($inputData);

        // Check if brand added then store brand image
        if (__ifIsset($brand)) {

            // Check if brand logo added
            if ($this->mediaEngine->storeBrandLogoMedia($logo, $brand->_id, null, true)) {
                return __engineReaction(1, ['brand' => $brand]);
            }

            return __engineReaction(2);
        }

        return __engineReaction(2);
    }

    /**
     * fetch brand data.
     *
     * @param int $brandID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchData($brandID)
    {
        // Get brand detail
        $brand = $this->brandRepository->fetchByID($brandID);

        // Check if brand is empty
        if (__isEmpty($brand)) {
            return __engineReaction(18);
        }

        // Prepare array brandData 
        $brandData = [
            '_id' => $brand->_id,
            'name' => $brand->name,
            'description' => $brand->description,
            'logo' => $brand->logo,
            'logo_url' => getBrandLogoURL($brand->_id, $brand->logo),
            'active' => ($brand->status == 1) ? true : false,
        ];

        return __engineReaction(1, $brandData);
    }

    /**
     * process of delete brand.
     *
     * @param int   $brandID
     * @param array $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDelete($brandID, $inputData)
    {
        $confirmProductDelete = 0;

        // if delete_related_products exist then varify entered password                            
        if (__ifIsset($inputData['delete_related_products'])) {
            $confirmProductDelete = 1;

            $user = Auth::user();

            // Check if logged in user password matched with entered password
            if (!Hash::check($inputData['current_password'], $user->password)) {
                return __engineReaction(2, null, __('Current password is incorrect.'));
            }

            $confirmProductDelete = 2;
        }

        $transactionResponse = $this->brandRepository
                                     ->processTransaction(function () use ($brandID, $inputData, $confirmProductDelete) {
            // only brand delete
            if ($confirmProductDelete === 0) {
                if (!$this->brandRepository->delete($brandID)) {
                    return $this->brandRepository
                            ->transactionResponse(2, null, __('Brand not deleted.'));
                }

                $this->mediaEngine->processDeleteBrandMedias($brandID);

                return $this->brandRepository
                        ->transactionResponse(1, null, __('Brand deleted successfully.'));
            }

            // delete brand with his products
            if ($confirmProductDelete === 2) {
                $brand = $this->brandRepository->fetchByID($brandID);

                $brandId = $brand->_id;

                $productIds = $this->manageProductRepository->fetchIdsByBrandId($brandId);

                //__dd($productIds);
                if (!__isEmpty($productIds)) {

                    // If products not deleted then return false
                    if (!$this->manageProductRepository->deleteByIds($productIds)) {
                        return $this->brandRepository
                                    ->transactionResponse(2, null, __('Brand not deleted.'));
                    }

                    foreach ($productIds as $productId) {
                        $this->mediaEngine->processDeleteProductMedias($productId);
                    }
                }

                if (!__isEmpty($productIds)) {
                    $deletedProductIds = implode($productIds, '|');

                    activityLog('ID of '.$brandId.' brand deleted of his products.'.$deletedProductIds);
                }

                if (!$this->brandRepository->delete($brandId)) {
                    return $this->brandRepository
                            ->transactionResponse(2, null, __('Brand not deleted.'));
                }

                $this->mediaEngine->processDeleteBrandMedias($brandId);

                return $this->brandRepository
                            ->transactionResponse(1, null, __('Brand deleted successfully.'));
            }

        });

        return __engineReaction($transactionResponse);
    }

    /**
     * process update brand.
     *
     * @param int $brandID
     * @param $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdate($brandID, $inputData)
    {
        // Get brand detail
        $brand = $this->brandRepository->fetchByID($brandID);

        //check if brand exist
        if (empty($brand)) {
            return __engineReaction(18);
        }

        // Check if product logo selected 
        if (!empty($inputData['logo']) and $inputData['logo'] != $brand->logo) {
            $logo = $inputData['logo'];

            // Check if selected product logo thumbnail exist
            if (!$this->mediaEngine->isUserTempMedia($logo)) {
                return __engineReaction(3);
            }

            $newImageThumbnail = $this->mediaEngine
                                      ->storeBrandLogoMedia(
                                            $logo,
                                            $brandID,
                                            $brand->logo,
                                            true
                                        );

            // Check if logo file moved to product media
            if (!$newImageThumbnail) {
                return 2; // error reaction
            }

            $inputData['logo'] = $newImageThumbnail;
        }

        // Check status
        $status = 1;
        if (empty($inputData['active']) or $inputData['active'] == false) {
            $status = 2;
        }

        // prepare array of updateData
        $updateData = [
            'name' => $inputData['name'],
            'logo' => $inputData['logo'],
            'description' => $inputData['description'],
            'status' => $status,
        ];

        // Update brand data
        $reponseData = $this->brandRepository->update($brand, $updateData);

        // If success then return engine reaction
        if ($reponseData) {
            return __engineReaction(1, $reponseData);
        }

        return __engineReaction(14);
    }

    /**
     * fetch active brand.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchIsActive()
    {
        // Get active brand collection
        $brandCollection = $this->brandRepository
                                ->fetchIsActive();

        // check if brand is empty
        if (empty($brandCollection)) {
            return __engineReaction(18);
        }

        $brands = [];

        // Make brand array 
        foreach ($brandCollection as $brand) {
            $brands[] = [
                'id' => $brand->_id,
                'name' => $brand->name,
                'logo' => $brand->logo,
                'logoURL' => getBrandLogoURL($brand->_id, $brand->logo),
            ];
        }

        return __engineReaction(1, [
                'breadCrumb' => BreadCrumb::generate('brand'),
                'brands' => $brands,
        ]);
    }
}
