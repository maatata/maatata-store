<?php
/*
* ManageProductEngine.php - Main component file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product;

use App\Yantrana\Components\Product\Repositories\ManageProductRepository;
use App\Yantrana\Components\Product\Blueprints\ManageProductEngineBlueprint;
use App\Yantrana\Components\Media\MediaEngine;
use App\Yantrana\Components\Category\ManageCategoryEngine;
use App\Yantrana\Components\Store\Repositories\ManageStoreRepository;
use App\Yantrana\Components\Brand\Repositories\BrandRepository;
use App\Yantrana\Components\Product\Repositories\ProductRepository;

class ManageProductEngine implements ManageProductEngineBlueprint
{
    /**
     * @var ManageProductRepository - ManageProduct Repository
     */
    protected $manageProductRepository;

    /**
     * @var MediaEngine - Media Engine
     */
    protected $mediaEngine;

    /**
     * @var ManageStoreRepository - ManageStore Repository
     */
    protected $manageStoreRepository;

    /**
     * @var BrandRepository - ManageBrand Repository
     */
    protected $manageBrandRepository;

    /**
     * @var ProductRepository - Product Repository
     */
    protected $productRepository;

    /**
     * Constructor.
     *
     * @param ManageProductRepository $manageProductRepository - ManageProduct Repository
     * @param MediaEngine             $mediaEngine             - Media Engine
     * @param ManageStoreRepository   $manageStoreRepository   - ManageStore Repository
     * @param BrandRepository         $manageBrandRepository   - ManageBrand Repository
     *-----------------------------------------------------------------------*/
    public function __construct(ManageProductRepository $manageProductRepository,
                         MediaEngine $mediaEngine,
                         ManageStoreRepository $manageStoreRepository,
                         ManageCategoryEngine $manageCategoryEngine,
                         BrandRepository $manageBrandRepository,
                         ProductRepository $productRepository)
    {
        $this->manageProductRepository = $manageProductRepository;
        $this->mediaEngine = $mediaEngine;
        $this->manageStoreRepository = $manageStoreRepository;
        $this->manageCategoryEngine = $manageCategoryEngine;
        $this->manageBrandRepository = $manageBrandRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Prepare add product support data.
     *---------------------------------------------------------------- */
    public function prepareAddProoductSupportData()
    {
        $data = [
            'related_products' => $this->manageProductRepository
                                            ->fetchAll(),
            'store_currency_symbol' => $this->manageStoreRepository
                                            ->fetchCurrencySymbol(),
            'store_currency' => $this->manageStoreRepository
                                            ->fetchCurrency(),
            'activeBrands' => $this->getActiveBrands(),
            'categories' => fancytreeSource($this->manageCategoryEngine->getAll()),
        ];

        return __engineReaction(1, $data);
    }

    /**
     * Get Active brands.
     *---------------------------------------------------------------- */
    protected function getActiveBrands()
    {
        $activeBrands = [];
        $brands = $this->manageBrandRepository->fetchActiveWithoutCache();

        if (!empty($brands)) {
            foreach ($brands as $brand) {
                $activeBrands[] = [
                    'value' => $brand->_id,
                    'name' => $brand->name,
                ];
            }
        }

        return $activeBrands;
    }

    /**
     * Process add product based on post input data, checking if input -
     * data is valid then called the repository.
     *
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProduct($input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($input) {

            $image = $input['image'];

            // Check if selected product image thumbnail exist
            if (!$this->mediaEngine->isUserTempMedia($image)) {
                return 18;
            }

            // Check if categories exist
            if (empty($input['categories']) or !is_array($input['categories'])) {
                return 4;
            }

            // Check if product added then store image of product
            if ($productID = $this->manageProductRepository->store($input)) {
                if (!empty($productID)) {
                    $this->productId = $productID;
                }

                 // Check if product image added
                if ($this->mediaEngine->storeProductMedia($image, $productID, null, true)) {
                    return 1;
                }

                return 2;
            }

            return 2;

        });

        $newProductId = [];
        // Check reaction code and return productId
        if ($reactionCode == 1) {
            $newProductId = ['productId' => $this->productId];
        }

        return __engineReaction($reactionCode, $newProductId);
    }

    /**
     * Prepare product list.
     *---------------------------------------------------------------- */
    public function prepareProductList($categoryID = null, $brandId = null)
    {
        // Get product detail
        $productCollection = $this->manageProductRepository
                                  ->fetchDataTableSource($categoryID, $brandId);

        $requireColumns = [
            'creation_date' => function ($key) {
                return formatStoreDateTime($key['created_at']);
            },
            'updation_date' => function ($key) {
                return formatStoreDateTime($key['updated_at']);
            },
            'thumbnail_url' => function ($key) {

                return getProductImageURL($key['id'], $key['thumbnail']);

            },
            'detailPageURL' => function ($key) {

                return route('product.details', [$key['id'], str_slug($key['name'])]);
            },
            'categories' => function ($key) {

                $categories = [];

                if (!empty($key['categories'])) {
                    foreach ($key['categories'] as $category) {
                        $categories[] = [
                            'name' => $category['name'],
                            'id' => $category['id'],
                            'status' => $category['status'],
                        ];
                    }
                }

                return $categories;

            },
            'id', 'name', 'status', 'thumbnail', 'out_of_stock', 'featured', 'brand',
        ];

        // Get category with category ID
        $fetchCategory = [];
        if (!empty($categoryID)) {
            $fetchCategory = $this->manageProductRepository
                                    ->fetchCategoryByID($categoryID);
        }

        // Get Brand by brandID
        $fetchBrand = [];
        if (!empty($brandId)) {
            $fetchBrand = $this->manageProductRepository
                                    ->fetchBrandByID($brandId);
        }

        return __dataTable($productCollection, $requireColumns, [
                'category' => $fetchCategory,
                'brand' => $fetchBrand,
            ]);
    }

    /**
     * check if the this product is valid.
     *
     * @param object $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    protected function checkIsValidCategory($productID)
    {
        // Get categories products
        $productsCategories = $this->manageProductRepository
                                   ->fetchProductsCategories($productID);

        $findActiveParents = [];

        // get all categories
        $categories = $this->productRepository->fetchCategories();

        // Check if products category exist and find its parent 
        // category and check is active or not
        if (!empty($productsCategories)) {
            foreach ($productsCategories as $productCategory) {
                $categoriesIDs = $productCategory->categories_id;
                $findActiveParents[] = findActiveParents($categories, $categoriesIDs);
            }
        }

        // get active categories  & make in sigle level
        $makeArrayInSingleLevel = array_flatten($findActiveParents);

        // get active categories & get only unique
        return array_unique($makeArrayInSingleLevel);
    }

    /**
     * prepare detail dialog data.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */

   /* public function prepareDetailDialog($productID)
    {	
        // Get product detail
        $product = $this->manageProductRepository->fetchProductDetailForDetailDialog($productID);
        
        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }

        // Check valid product category and its parent category active or not
        $activeCatIDs = $this->checkIsValidCategory($productID);

        $relatedProductsCollection = [];

        // Check related product exist or not 
        // if they exist then fetch products using product ID
        if (!empty($product->relatedProducts)) {
            
            // get category related with product
            foreach ($product->relatedProducts as $relatedProductID) {
                
                $relatedProductsCollection [] = $this->manageProductRepository
                                                       ->fetchRelatedProductsByID(
                                                           $relatedProductID->related_product_id
                                                           ); 
            } 
        }
        
        $brandData = [];

        // Check product brand exist and fetch brand detail
        if (!empty($product->brands__id)) {
            
            $brandData = $this->manageProductRepository
                              ->fetchBrandByID($product->brands__id);
            // Create brand URL
               $brandData['brandURL'] = route('product.related.by.brand', [$brandData['_id'], str_slug($brandData['name'])]);
        }

        // Check product status
        if ($product->status === 1) {
            $product['active'] = true;
        } else {
            $product['active'] = false;
        }

        // Check product is out of stock
        if ($product->out_of_stock === 1) {
            $product['out_of_stock'] = true;
        } else {
            $product['out_of_stock'] = false;
        }

        // Check product description and remove html tag from description
        if (!__isEmpty($product->description)) {
            $product['description'] = strip_tags($product->description);
        }

        // Check product image and create image URL
        if (!__isEmpty($product->thumbnail)) {
             $product['thumbnail'] = getProductImageURL($product->id, $product->thumbnail);
        }

        // formatted created and updated at date and time
        $product['created_on'] = formatStoreDateTime($product->created_at);
        $product['updated_on'] = formatStoreDateTime($product->updated_at);
        
        // Check product categories and create url for category
        if (!__isEmpty($product['categories'])) {
        
            foreach ($product['categories'] as $key => $category) {

                $product['categories'][$key]['categoryURL'] = categoriesProductRoute($category['id'], $category['name']);
            }

            $product['categoriesCount'] = count($product['categories']);
        }

        // Check related products exist and create related product url
        if (!empty($relatedProductsCollection)) {
        
            foreach ($relatedProductsCollection as $key => $relatedProduct) {

                $relatedProductsCollection[$key]['relatedProductURL'] = route('product.details', [$relatedProduct['id'], str_slug($relatedProduct['name'])]); 
            }

        }
       
        $data = [
            'product'           => $product,
            'isActiveCategory'	=> $activeCatIDs,
            'relatedProducts'   => $relatedProductsCollection,
            'brandData'			=> $brandData,
            'currencySymbol' 	=> getCurrencySymbol()
        ];

        return __engineReaction(1, $data);
    }*/

    /**
     * Process product delete request.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProduct($productID)
    {
        // fetch product detail   
        $product = $this->manageProductRepository->fetchByID($productID);

        // Chech if product exist
        if (empty($product)) {
            return __engineReaction(18);    // not exist product record
        }

        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($product) {

            // Check if product deleted & its directory deleted successfully
            if ($this->manageProductRepository->delete($product)) {
                $this->mediaEngine->processDeleteProductMedias($product->id);

                return 1; // success reaction
            }

            return 2; // error reaction

        });

        // Check if return reaction code is success
        if ($reactionCode === 1) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Prepare product details.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductDetails($productID)
    {
        $product = $this->manageProductRepository->fetchDetails($productID);

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, ['product' => $product]);
    }

    /**
     * Prepare product edit details support data.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditDetailsSupportData($productID)
    {
        $product = $this->manageProductRepository->fetchByID($productID, true);

        // Check if product exist
        if (empty($product)) {
            return __engineReaction(18);
        }

        // Get product categories
        $product['categories'] = $this->manageProductRepository
                                         ->fetchProductCategoriesByProductID(
                                            $productID
                                         );

        // add dash(-) in the place of space
        $slugName = str_slug($product->name);

        $product['related_products'] = $this->manageProductRepository
                                            ->fetchRelatedProductsByProductID(
                                                $productID
                                            );
        // Create view page Url
        $product['viewPage'] = productsDetailsRoute($productID, $slugName);

        // Check product image and create media Url
        if (!empty($product['thumbnail'])) {
            $product['thumbnailURL'] = getProductMediaURL($productID);
        }

        // Check product status
        if ($product->status === 1) {
            $product['active'] = true;
        } else {
            $product['active'] = false;
        }

        // check product out of stock
        if ($product->out_of_stock == 1) {
            $product['outOfStock'] = true;
        } else {
            $product['outOfStock'] = false;
        }

           // Prepare data for view
        $data = [
            'product' => $product,
            'related_products' => $this->manageProductRepository
                                        ->fetchAll($productID),
            'store_currency_symbol' => $this->manageStoreRepository
                                            ->fetchCurrencySymbol(),
            'store_currency' => $this->manageStoreRepository
                                            ->fetchCurrency(),
            'activeBrands' => $this->getActiveBrands(),
            'categories' => fancytreeSource($this->manageCategoryEngine->getAll()),
        ];

        return __engineReaction(1, $data);
    }

    /**
     * Process edit product details information.
     *
     * @param number $productID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProduct($productID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // Get product detail
            $product = $this->manageProductRepository->fetchByID($productID);

            // Check if product exist
            if (empty($product)) {
                return 18;
            }

            // Check if categories exist
            if (empty($input['categories']) or !is_array($input['categories'])) {
                return 4;
            }

            // Check if product image selected 
            if (isset($input['image'])) {
                $image = $input['image'];

                // Check if selected product image thumbnail exist
                if (!$this->mediaEngine->isUserTempMedia($image)) {
                    return 3;
                }

                $newImageThumbnail = $this->mediaEngine
                                          ->storeProductMedia(
                                                $image,
                                                $productID,
                                                $product->thumbnail,
                                                true // generate thumb
                                            );

                // Check if image file moved to product media
                if (!$newImageThumbnail) {
                    return 2; // error reaction
                }

                $input['image'] = $newImageThumbnail;
            }

            $inputRelatedProducts = $input['related_products'];
            $deleteRelatedProducts = [];
            $newRelatedProducts = [];
            $existingRelatedProducts = $this->manageProductRepository
                                               ->fetchRelatedProductsByProductID(
                                                $productID
                                            );

            if (empty($inputRelatedProducts)
                and !empty($existingRelatedProducts)) {
                $deleteRelatedProducts = $existingRelatedProducts;
            } elseif (!empty($inputRelatedProducts)
                and empty($existingRelatedProducts)) {
                $newRelatedProducts = $inputRelatedProducts;
            } elseif (!empty($inputRelatedProducts)
                and !empty($existingRelatedProducts)) {
                foreach ($inputRelatedProducts as $inputRelatedProduct) {
                    if (!in_array($inputRelatedProduct, $existingRelatedProducts)) {
                        array_push($newRelatedProducts, $inputRelatedProduct);
                    }
                }

                foreach ($existingRelatedProducts as $existingRelatedProduct) {
                    if (!in_array($existingRelatedProduct, $inputRelatedProducts)) {
                        array_push($deleteRelatedProducts, $existingRelatedProduct);
                    }
                }
            }

            $input['related_products'] = $newRelatedProducts;
            $input['delete_related_products'] = $deleteRelatedProducts;

            $inputCategories = $input['categories'];
            $deleteCategories = [];
            $newCategories = [];
            $existingCategories = $this->manageProductRepository
                                          ->fetchProductCategoriesByProductID(
                                                $productID
                                            );

            // Check existance and input category
            if (empty($inputCategories)
                and !empty($existingCategories)) {
                $deleteCategories = $existingCategories;

            // Check if existingCategories are empty
            } elseif (!empty($inputCategories)
                and empty($existingCategories)) {
                $newCategories = $inputCategories;

            // Check if both are not empty
            } elseif (!empty($inputCategories)
                and !empty($existingCategories)) {

                // Check input category exist in existingCategories array or not
                foreach ($inputCategories as $inputCategory) {
                    if (!in_array($inputCategory, $existingCategories)) {
                        array_push($newCategories, $inputCategory);
                    }
                }

                // Check existing category is in inputCategories array or not
                foreach ($existingCategories as $existingCategory) {
                    if (!in_array($existingCategory, $inputCategories)) {
                        array_push($deleteCategories, $existingCategory);
                    }
                }
            }

            $input['categories'] = $newCategories;
            $input['delete_categories'] = $deleteCategories;

            // Check if product updated
            if ($this->manageProductRepository->update($product, $input)) {
                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Add Product image if provided data valid called repository method 
     * to store product image/.
     *
     * @param number productID
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductImage($productID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // fetch product                    
            $product = $this->manageProductRepository->fetchByID($productID);

            // Check if product exist
            if (empty($product)) {
                return 18;
            }

            $image = $input['image'];

            // Check if selected product image thumbnail exist
            if (!$this->mediaEngine->isUserTempMedia($image)) {
                return 3;
            }

            $newImageThumbnail = $this->mediaEngine
                                      ->storeProductMedia($image, $productID, null, 'productSliderImage');

            // Check if image file moved to product media
            if (!$newImageThumbnail) {
                return 2; // error reaction
            }

            $input['file_name'] = $newImageThumbnail;

            // Check if prdouct image added
            if ($this->manageProductRepository
                     ->storeImage($productID, $input)) {
                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct images for datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductImageList($productID)
    {
        $imagesCollection = $this->manageProductRepository
                                  ->fetchImagesDataTableSource($productID);

        $requireColumns = [
            'thumbnail_url' => function ($key) use ($productID) {

                return getProductImageURL($productID, $key['file_name']);

            },
            'id', 'title',
        ];

        return __dataTable($imagesCollection, $requireColumns);
    }

    /**
     * Delete product image.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductImage($productID, $imageID)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $imageID) {

            $productImage = $this->manageProductRepository
                                 ->fetchImage($productID, $imageID);

            // Check if product image exist
            if (empty($productImage)) {
                return 18;
            }

            // Check if prdouct image deleted
            if ($this->manageProductRepository->deleteImage($productImage)) {
                $this->mediaEngine->processDeleteProductMediaImage($productID,
                                        $productImage->file_name
                                    );

                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct image edit support data.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditImageSupportData($productID, $imageID)
    {
        $productImage = $this->manageProductRepository
                             ->fetchImage($productID, $imageID, true);

        // Check if product image exist
        if (empty($productImage)) {
            return 18;
        }

        return __engineReaction(1, ['prdouct_image' => $productImage]);
    }

    /**
     * Process edit product image.
     *
     * @param number $productID
     * @param number $imageID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductImage($productID, $imageID, $input)
    {
        $productImage = $this->manageProductRepository
                             ->fetchImage($productID, $imageID);

        // Check if product image exist
        if (empty($productImage)) {
            return __engineReaction(18);
        }

        // Check if product image updated
        if ($this->manageProductRepository
                 ->updateImage($productImage, $input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare prdouct option datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductOptionList($productID)
    {
        $optionCollection = $this->manageProductRepository
                                  ->fetchOptionsDataTableSource($productID);

        $requireColumns = [
            'id',
            'name',
        ];

        return __dataTable($optionCollection, $requireColumns);
    }

    /**
     * Add Product option.
     *
     * @param number productID
     * @param array $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductOption($productID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $input) {

            // Check if product exist
            if ($this->manageProductRepository
                     ->fetchCountByID($productID) == 0) {
                return 18;
            }

            // Check if values empty
            if (empty($input['values'])) {
                return 4;
            }

            // Check if product option name already taken by any option
            if ($this->manageProductRepository
                     ->fetchProductOptionCount($productID, $input['name']) > 0) {
                return 3;
            }

            // Check if prdouct option added
            if ($this->manageProductRepository
                     ->storeOption($productID, $input)) {
                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Delete product option.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductOption($productID, $optionID)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID) {

            // Get product option
            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);

            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            // Check if product option deleted
            if ($this->manageProductRepository->deleteOption($productOption)) {
                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare edit product option support data.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareEditOptionSupportData($productID, $optionID)
    {
        $productOption = $this->manageProductRepository
                              ->fetchOption($productID, $optionID, true);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, ['product_option' => $productOption]);
    }

    /**
     * Process edit prdouct option.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductOption($productID, $optionID, $input)
    {
        $productOption = $this->manageProductRepository
                             ->fetchOption($productID, $optionID);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        // Check if product option name already taken by any option
        if ($this->manageProductRepository
                 ->fetchProductOptionCount($productID, $input['name'], $optionID) > 0) {
            return __engineReaction(3);
        }

        // Check if prdouct option updated
        if ($this->manageProductRepository->updateOption($productOption, $input)) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Prepare product option values.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductOptionValues($productID, $optionID)
    {
        $productOption = $this->manageProductRepository
                              ->fetchOption($productID, $optionID);

        // Check if product option exist
        if (empty($productOption)) {
            return __engineReaction(18);
        }

        $optionValues = $this->manageProductRepository
                             ->fetchOptionValues($optionID);

        return __engineReaction(1, ['option_values' => $optionValues]);
    }

    /**
     * Process product option value delete request.
     *
     * @param number $productID
     * @param number $optionID
     * @param number $optionValueID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductOptionValue($productID,
        $optionID, $optionValueID)
    {
        $optionValue = $this->manageProductRepository
                            ->fetchOptionValue($optionID, $optionValueID);

        // Check if option value empty
        if (empty($optionValue)) {
            return __engineReaction(18);
        }

        // Check if product option value deleted
        if ($this->manageProductRepository->deleteOptionValue($optionValue)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * Process product option values add request.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductOptionValues($productID, $optionID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID, $input) {

            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);

            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            $optionValues = $input['values'];

            // Check if option values empty
            if (empty($optionValues)) {
                return 3;
            }

            // Check if product option values added
            if ($this->manageProductRepository
                     ->storeOptionValues($optionID, $optionValues)) {
                return 1;
            }

            return 2;

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Process product option values edit request.
     *
     * @param number $productID
     * @param number $optionID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processEditProductOptionValues($productID, $optionID, $input)
    {
        $reactionCode = $this->manageProductRepository
                             ->processTransaction(function () use ($productID, $optionID, $input) {

            $productOption = $this->manageProductRepository
                                  ->fetchOption($productID, $optionID);

            // Check if product option exist
            if (empty($productOption)) {
                return 18;
            }

            // Assign input data to optionValues variable
            $optionValues = $input['values'];

            $newInputValues = [];

            // Check if option values exist
            // then assign a detail and push in array
            foreach ($optionValues as $optionValue) {

                // Check option value is empty
                if (empty($optionValue['id'])) {
                    $addonPrice = 0;
                    if (!empty($optionValue['addon_price'])) {
                        $addonPrice = $optionValue['addon_price'];
                    }

                    $optionId = '';
                    if (!empty($optionValue['id'])) {
                        $optionId = $optionValue['id'];
                    }

                    $newInputValues[] = [
                        'id' => $optionId,
                        'name' => $optionValue['name'],
                        'addon_price' => $addonPrice,
                    ];
                }
            }

            $productOptionValue = $this->manageProductRepository
                                         ->fetchOptionValues($optionID)->toArray();

            // Check Product option and option values and update data
            if ((!empty($productOption)) and (empty($productOptionValue))) {
                $inputValues = $this->manageProductRepository
                                    ->updateNewOptionValues($productID, $optionID, $newInputValues);
                if ($inputValues) {
                    return 1; //success
                }
            }

            $inputValues = $this->manageProductRepository
                                ->updateNewOptionValues($productID, $optionID, $newInputValues);

            $valueInputs = [];

            // Check if option values exist
            // then assign a detail and push in array
            foreach ($optionValues as $optionValue) {
                if (!empty($optionValue['id'])) {
                    $addonPrice = 0;
                    if (!empty($optionValue['addon_price'])) {
                        $addonPrice = $optionValue['addon_price'];
                    }

                    $valueInputs[] = [
                        'id' => $optionValue['id'],
                        'name' => $optionValue['name'],
                        'addon_price' => $addonPrice,
                    ];
                }
            }

            // Check if product option values updated
            $updateValues = $this->manageProductRepository
                                 ->updateOptionValues($productID, $optionID, $valueInputs);

            if ($inputValues and $updateValues) {
                return 1; //success
            } elseif ($inputValues or $updateValues) {
                return 1; // success
            } else {
                return 2; // failed
            }

        });

        return __engineReaction($reactionCode);
    }

    /**
     * Prepare prdouct specification for datatable source.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareProductSpecificationList($productID)
    {
        $specificationCollection = $this->manageProductRepository
                                          ->fetchSpecificationDataTableSource($productID);

        $requireColumns = [
                '_id',
                'name',
                'value',
            ];

        return __dataTable($specificationCollection, $requireColumns);
    }

    /**
     * Process product specification  add request.
     *
     * @param number $productID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processAddProductSpecificationValues($productID, $input)
    {
        // Check if product option values added
            if ($this->manageProductRepository
                     ->storeSpecificationValues($productID, $input)) {
                return __engineReaction(1);
            }

        return __engineReaction(2);
    }

    /**
     * Prepare product specification support Data.
     *
     * @param number $productID
     * @param number $specificationID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function prepareSpecificationSupportData($productID, $specificationID)
    {
        $specificationData = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check specification data exist
        if (empty($specificationData)) {
            return __engineReaction(18);
        }

        $specificationCollection = $this->manageProductRepository
                                          ->fetchAllSpecificationValues();

        return __engineReaction(1, [
                    'secificationValues' => $specificationData,
                    'specificationCollection' => $specificationCollection,
        ]);
    }

    /**
     * Process product specification update.
     *
     * @param number $specificationID
     * @param array  $input
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateProductSpecificationValues($specificationID, $input)
    {
        $specificationData = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check if specification exist
        if (empty($specificationData)) {
            return __engineReaction(18);
        }

        $success = $this->manageProductRepository
                         ->updateSpecificationValues($specificationData, $input);

        // if update specification then return engine reaction
        if ($success == true) {
            return __engineReaction(1);
        }

        return __engineReaction(14);
    }

    /**
     * Process product specification value delete request.
     *
     * @param number $optionValueID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processDeleteProductSpecificationValue($productID, $specificationID)
    {
        $specificationValue = $this->manageProductRepository
                             ->fetchSpecificationByID($specificationID);

        // Check if option value empty
        if (empty($specificationValue)) {
            return __engineReaction(18);
        }

        // Check if product option value deleted
        if ($this->manageProductRepository->deleteSpecificationValue($specificationValue)) {
            return __engineReaction(1);
        }

        return __engineReaction(2);
    }

    /**
     * get all product specification data.
     *
     * @param number $optionValueID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getAllSpecificationData()
    {
        $specificationData = $this->manageProductRepository
                                  ->fetchAllSpecificationValues();

        return __engineReaction(1, ['specificationData' => $specificationData]);
    }

    /**
     * get product name for heading.
     *
     * @param number $productID
     *
     * @return array
     *---------------------------------------------------------------- */
    public function getName($productID)
    {
        $productData = $this->manageProductRepository
                            ->fetchDetails($productID);

        // If product record empty then return not exist reaction
        if (__isEmpty($productData)) {
            return __engineReaction(18);
        }

        return __engineReaction(1, [
                'productName' => $productData,
                'status' => $productData->status == 1 ? true : false,
            ]);
    }

    /**
     * Process update status delete request.
     *
     * @param number $productId
     * @param array  $inputData
     *
     * @return array
     *---------------------------------------------------------------- */
    public function processUpdateStatus($productId, $inputData)
    {
        // fetch product detail   
        $product = $this->manageProductRepository->fetchByID($productId);

        // Chech if product exist
        if (__isEmpty($product)) {
            return __engineReaction(18, null, 'Product does not exist.');  // not exist product record
        }

        $status = $inputData['active'] == true ? 1 : 2;

        // If status updated successfully then return success response
        if ($this->manageProductRepository->updateStatus($product, $status)) {
            return __engineReaction(1, null, 'Product status updated successfully.');
        }

        return __engineReaction(14, null, 'Status not updated.');
    }
}
