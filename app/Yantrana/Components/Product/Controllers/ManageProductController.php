<?php
/*
* ManageProductController.php - Controller file
*
* This file is part of the Product component.  
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Controllers;

use App\Yantrana\Support\CommonPostRequest as Request;
use App\Yantrana\Core\BaseController;
use App\Yantrana\Components\Product\ManageProductEngine;
use App\Yantrana\Components\Product\Requests\ManageProductAddRequest;
use App\Yantrana\Components\Product\Requests\ManageProductEditRequest;
use App\Yantrana\Components\Product\Requests\ProductImageAddRequest;
use App\Yantrana\Components\Product\Requests\ProductImageEditRequest;
use App\Yantrana\Components\Product\Requests\ProductOptionAddRequest;
use App\Yantrana\Components\Product\Requests\ProductOptionEditRequest;
use App\Yantrana\Components\Product\Requests\ProductOptionValuesEditRequest;
use App\Yantrana\Components\Product\Requests\ProductOptionValuesAddRequest;
use App\Yantrana\Components\Product\Requests\ProductSpecificationAddRequest;
use App\Yantrana\Components\Product\Requests\ProductSpecificationUpdateRequest;
use App\Yantrana\Components\Product\Requests\ManageProductUpdateStatusRequest;

class ManageProductController extends BaseController
{
    /**
     * @var ManageProductEngine - ManageProduct Engine
     */
    protected $manageProductEngine;

    /**
     * Constructor.
     *
     * @param ManageProductEngine $manageProductEngine - ManageProduct Engine
     *-----------------------------------------------------------------------*/
    public function __construct(ManageProductEngine $manageProductEngine)
    {
        $this->manageProductEngine = $manageProductEngine;
    }

    /**
     * Handle product list request.
     *
     * @param number $categoryID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function index($categoryID = null, $brandId = null)
    {
        return $this->manageProductEngine->prepareProductList($categoryID, $brandId);
    }

    /**
     * Handle product edit details support data request.
     *
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getDetail($productID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareDetailDialog($productID);

        return __processResponse($processReaction, [
                18 => __('Product does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * get brand request.
     *
     * @param number $brandId
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function getBrands($brandId)
    {
        return $this->manageProductEngine->prepareProductList(null, $brandId);
    }

    /**
     * Handle add product support data request.
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addSupportData()
    {
        $processReaction = $this->manageProductEngine
                                ->prepareAddProoductSupportData();

        return __processResponse($processReaction, [], $processReaction['data']);
    }

    /**
     * Handle add new product request.
     *
     * @param object ManageProductAddRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function add(ManageProductAddRequest $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processAddProduct($request->all());

        return __processResponse($processReaction, [
                1 => __('Product added successfully.'),
                2 => __('Product not added.'),
                18 => __('Selected image does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product delete request.
     *
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function delete($productID, Request $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processDeleteProduct($productID);

        return __processResponse($processReaction, [
                1 => __('Product deleted successfully.'),
                2 => __('Product not deleted.'),
                18 => __('Product does not exist.'),
            ]);
    }

    /**
     * Handle product details request.
     *
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function details($productID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareProductDetails($productID);

        return __processResponse($processReaction, [
                18 => __('Product does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product edit details support data request.
     *
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editDetailsSupportData($productID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareEditDetailsSupportData($productID);

        return __processResponse($processReaction, [
                18 => __('Product does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle edit existing product details request.
     *
     * @param object ManageProductEditRequest $request
     * @param number                          $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function edit(ManageProductEditRequest $request, $productID)
    {
        $processReaction = $this->manageProductEngine
                                ->processEditProduct(
                                    $productID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product updated successfully.'),
                2 => __('Product not updated.'),
                3 => __('Selected image does not exist.'),
                4 => __('Select valid product categories.'),
                18 => __('Product does not exist.'),
            ]);
    }

    /**
     * Handle product add image request.
     *
     * @param object ProductImageAddRequest $request
     * @param number                        $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addImage(ProductImageAddRequest $request, $productID)
    {
        $processReaction = $this->manageProductEngine
                                ->processAddProductImage(
                                    $productID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product image added successfully.'),
                2 => __('Product image not added.'),
                3 => __('Selected image does not exist.'),
                18 => __('Product does not exist.'),
            ]);
    }

    /**
     * Handle product images list request.
     *
     * @param number $productID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function imageList($productID)
    {
        return $this->manageProductEngine->prepareProductImageList($productID);
    }

    /**
     * Handle product image delete request.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteImage($productID, $imageID, Request $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processDeleteProductImage($productID, $imageID);

        return __processResponse($processReaction, [
                1 => __('Product image deleted successfully.'),
                2 => __('Product image not deleted.'),
                18 => __('Product image does not exist.'),
            ]);
    }

    /**
     * Handle product image edit support data request.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editImageSupportData($productID, $imageID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareEditImageSupportData(
                                    $productID,
                                    $imageID
                                );

        return __processResponse($processReaction, [
                18 => __('Product image does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product image edit support data request.
     *
     * @param object ProductImageEditRequest $request
     * @param number                         $productID
     * @param number                         $imageID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editImage(ProductImageEditRequest $request, $productID,
     $imageID)
    {
        $processReaction = $this->manageProductEngine
                                ->processEditProductImage(
                                    $productID,
                                    $imageID,
                                    $request->only(
                                        'title'
                                    )
                                );

        return __processResponse($processReaction, [
                1 => __('Product image updated successfully.'),
                14 => __('Product image not updated.'),
                18 => __('Product image does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product option list request.
     *
     * @param number $productID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function optionList($productID)
    {
        return $this->manageProductEngine->prepareProductOptionList($productID);
    }

    /**
     * Handle product option add request.
     *
     * @param object ProductOptionAddRequest $request
     * @param number                         $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addOption(ProductOptionAddRequest $request, $productID)
    {
        $processReaction = $this->manageProductEngine
                                ->processAddProductOption(
                                    $productID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product option added successfully.'),
                2 => __('Product option not added.'),
                3 => __('Option name already taken for this product.'),
                18 => __('Product does not exist.'),
            ]);
    }

    /**
     * Handle product option delete request.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteOption($productID, $optionID, Request $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processDeleteProductOption($productID, $optionID);

        return __processResponse($processReaction, [
                1 => __('Product option deleted successfully.'),
                2 => __('Product option not deleted.'),
                18 => __('Product option does not exist.'),
            ]);
    }

    /**
     * Handle product option edit support data request.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editOptionSupportData($productID, $optionID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareEditOptionSupportData(
                                    $productID,
                                    $optionID
                                );

        return __processResponse($processReaction, [
                18 => __('Product option does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product option edit request.
     *
     * @param object ProductOptionEditRequest $request
     * @param number                          $productID
     * @param number                          $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editOption(ProductOptionEditRequest $request, $productID,
     $optionID)
    {
        $processReaction = $this->manageProductEngine
                                ->processEditProductOption(
                                    $productID,
                                    $optionID,
                                    $request->only(
                                        'name',
                                        'required'
                                    )
                                );

        return __processResponse($processReaction, [
                1 => __('Product option updated successfully.'),
                3 => __('Option name already taken for this product.'),
                14 => __('Product option not updated.'),
                18 => __('Product option does not exist.'),
            ]);
    }

    /**
     * Handle product option values add request.
     *
     * @param object ProductOptionValuesAddRequest $request
     * @param number                               $productID
     * @param number                               $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addOptionValues(ProductOptionValuesAddRequest $request,
     $productID, $optionID)
    {
        $processReaction = $this->manageProductEngine
                                ->processAddProductOptionValues(
                                    $productID,
                                    $optionID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product option values added successfully.'),
                3 => __('Value name already taken for this product option.'),
                18 => __('Product option does not exist.'),
            ]);
    }

    /**
     * Handle product option values request.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function optionValues($productID, $optionID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareProductOptionValues(
                                    $productID,
                                    $optionID
                                );

        return __processResponse($processReaction, [
                18 => __('Product option does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product option value delete request.
     *
     * @param number $productID
     * @param number $optionID
     * @param number $optionValueID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteOptionValue($productID, $optionID, $optionValueID, Request $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processDeleteProductOptionValue(
                                    $productID,
                                    $optionID,
                                    $optionValueID
                                );

        return __processResponse($processReaction, [
                1 => __('Product option value deleted.'),
                2 => __('Product option value not deleted.'),
                18 => __('Product option value does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product option values edit request.
     *
     * @param object ProductOptionValuesEditRequest $request
     * @param number                                $productID
     * @param number                                $optionID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editOptionValues(ProductOptionValuesEditRequest $request,
     $productID, $optionID)
    {
        $processReaction = $this->manageProductEngine
                                ->processEditProductOptionValues(
                                    $productID,
                                    $optionID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product option updated successfully.'),
                3 => __('Option values not enter.'),
                4 => __('Option name already taken for this product.'),
                14 => __('Product option not updated.'),
                18 => __('Product option does not exist.'),
            ]);
    }

    /**
     * Handle product specification list request.
     *
     * @param number $productID
     * 
     * @return json object
     *---------------------------------------------------------------- */
    public function specificationList($productID)
    {
        return $this->manageProductEngine->prepareProductSpecificationList($productID);
    }

    /**
     * Handle product specification add request.
     *
     * @param object ProductSpecificationAddRequest $request
     * @param number                                $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function addSpecification(ProductSpecificationAddRequest $request, $productID)
    {
        $processReaction = $this->manageProductEngine
                                ->processAddProductSpecificationValues(
                                    $productID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product Specification added successfully.'),
                2 => __('Product Specification not added.'),
                18 => __('Product does not exist.'),
            ]);
    }

    /**
     * Handle product specification edit request.
     *
     * @param number $productID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function editSpecification($productID, $specificationID)
    {
        $processReaction = $this->manageProductEngine
                                ->prepareSpecificationSupportData(
                                    $productID,
                                    $specificationID
                                );

        return __processResponse($processReaction, [
                18 => __('Product specification does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * Handle product specification edit request.
     *
     * @param object use App\Yantrana\Components\Product\Requests\ $request
     * @param number                                               $specificationID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function updateSpecification($productID, $specificationID, ProductSpecificationUpdateRequest $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processUpdateProductSpecificationValues(
                                    $specificationID,
                                    $request->all()
                                );

        return __processResponse($processReaction, [
                1 => __('Product specification updated successfully.'),
                3 => __('Product Specification values not enter.'),
                14 => __('Product Specification values not updated.'),
                18 => __('Product Specification values does not exist.'),
            ]);
    }

    /**
     * Handle product specification value delete request.
     *
     * @param number $specificationID
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function deleteSpecification($productID, $specificationID, Request $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processDeleteProductSpecificationValue($productID, $specificationID);

        return __processResponse($processReaction, [
                1 => __('Product specification deleted.'),
                2 => __('Product specification not deleted.'),
                18 => __('Product specification does not exist.'),
            ], $processReaction['data']);
    }

    /**
     * get all specification Data.
     *
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function specificationGetAllData()
    {
        $specificationData = $this->manageProductEngine
                                ->getAllSpecificationData();

        return __processResponse($specificationData, [], $specificationData['data']);
    }

    /**
     * get all specification Data.
     *
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function getProductName($productID)
    {
        $productName = $this->manageProductEngine
                            ->getName($productID);

        return __processResponse($productName, [], $productName['data']);
    }

    /**
     * Handle update status request.
     *
     * @param number                                  $productId
     * @param object ManageProductUpdateStatusRequest $request
     *
     * @return json object
     *---------------------------------------------------------------- */
    public function updateStatus($productId, ManageProductUpdateStatusRequest $request)
    {
        $processReaction = $this->manageProductEngine
                                ->processUpdateStatus($productId, $request->all());

        return __processResponse($processReaction);
    }
}
