<?php
/*
* ManageProductRepository.php - Repository file
*
* This file is part of the Product component.
*-----------------------------------------------------------------------------*/

namespace App\Yantrana\Components\Product\Repositories;

use App\Yantrana\Core\BaseRepository;
use App\Yantrana\Components\Product\Models\Product as ProductModel;
use App\Yantrana\Components\Category\Models\Category as CategoryModel;
use App\Yantrana\Components\Product\Models\ProductCategory;
use App\Yantrana\Components\Product\Models\RelatedProduct;
use App\Yantrana\Components\Product\Models\ProductImage;
use App\Yantrana\Components\Product\Models\ProductOptionLabel;
use App\Yantrana\Components\Product\Models\ProductOptionValue;
use App\Yantrana\Components\Product\Models\ProductSpecification;
use App\Yantrana\Components\Brand\Models\Brand;
use App\Yantrana\Components\Store\Models\Setting;
use App\Yantrana\Components\Product\Blueprints\ManageProductRepositoryBlueprint;
use DB;

class ManageProductRepository extends BaseRepository
                          implements ManageProductRepositoryBlueprint
{
    /**
     * @var ProductModel - Product Model
     */
    protected $product;

    /**
     * @var ProductCategory - ProductCategory Model
     */
    protected $productCategory;

    /**
     * @var RelatedProduct - RelatedProduct Model
     */
    protected $relatedProduct;

    /**
     * @var ProductImage - ProductImage Model
     */
    protected $productImage;

    /**
     * @var ProductOptionLabel - ProductOptionLabel Model
     */
    protected $productOptionLabel;

    /**
     * @var ProductOptionValue - ProductOptionValue Model
     */
    protected $productOptionValue;

    /**
     * @var CategoryModel - Category Model
     */
    protected $category;

    /**
     * @var Setting - Setting Model
     */
    protected $setting;

    /**
     * @var ProductSpecification - ProductSpecification Model
     */
    protected $productSpecification;

    /**
     * @var Brand - Brand Model
     */
    protected $brand;

    /**
     * Constructor.
     *
     * @param ProductModel         $product              - Product Model
     * @param ProductCategory      $productCategory      - ProductCategory Model
     * @param RelatedProduct       $relatedProduct       - RelatedProduct Model
     * @param ProductImage         $productImage         - ProductImage Model
     * @param ProductOptionLabel   $productOptionLabel   - ProductOptionLabel Model
     * @param ProductOptionValue   $productOptionValue   - ProductOptionValue Model
     * @param ProductSpecification $productSpecification - ProductSpecification Model
     * @param BrandModel           $brand                - Brand Model
     *-----------------------------------------------------------------------*/
    public function __construct(ProductModel $product,
        ProductCategory $productCategory,
        RelatedProduct $relatedProduct,
        ProductImage $productImage,
        ProductOptionLabel $productOptionLabel,
        ProductOptionValue $productOptionValue,
        CategoryModel $category,
        Setting $setting,
        ProductSpecification $productSpecification,
        Brand $brand)
    {
        $this->product = $product;
        $this->productCategory = $productCategory;
        $this->relatedProduct = $relatedProduct;
        $this->productImage = $productImage;
        $this->productOptionLabel = $productOptionLabel;
        $this->productOptionValue = $productOptionValue;
        $this->category = $category;
        $this->setting = $setting;
        $this->productSpecification = $productSpecification;
        $this->brand = $brand;
    }

    /**
     * Store product categories.
     *
     * @param number $productID
     * @param string $categoriesIDs
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeProductCategories($productID, $categoriesIDs)
    {
        // Check if categories empty
        if (empty($categoriesIDs)) {
            return false;
        }

        $productCategoriesData = [];

        foreach ($categoriesIDs as $categoryID) {
            $productCategoriesData[] = [
                'categories_id' => $categoryID,
                'products_id' => $productID,
            ];
        }

        return $this->productCategory->prepareAndInsert($productCategoriesData);
    }

    /**
     * Store related products of given product.
     *
     * @param number $productID
     * @param string $relatedProductsIDs
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeRelatedProducts($productID, $relatedProductsIDs)
    {
        // Check if related products empty
        if (empty($relatedProductsIDs)) {
            return false;
        }

        $relatedProductsData = [];

        foreach ($relatedProductsIDs as $relatedProductID) {
            $relatedProductsData[] = [
                'products_id' => $productID,
                'related_product_id' => $relatedProductID,
            ];
        }

        return $this->relatedProduct->prepareAndInsert($relatedProductsData);
    }

    /**
     * Store new product using provided data.
     *
     * @param array $input
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function store($input)
    {
        $newProduct = new $this->product();

        $newProduct->name = $input['name'];
        $newProduct->thumbnail = $input['image'];
        $newProduct->product_id = $input['product_id'];
        $newProduct->description = $input['description'];
        $newProduct->brands__id = (!empty($input['brands__id'])) ? $input['brands__id'] : null;
        $newProduct->price = $input['price'];
        $newProduct->youtube_video = (!empty($input['youtube_video'])) ? $input['youtube_video'] : null;
        $newProduct->status = (isset($input['publish']) and $input['publish'] == true) ? 1 : 2; // active product

        // Check if featured entered
        if (isset($input['featured'])) {
            $newProduct->featured = $input['featured'];
        } else {
            $newProduct->featured = 0;
        }

        // Check if out of stock
        if (isset($input['out_of_stock'])) {
            $newProduct->out_of_stock = $input['out_of_stock'];
        } else {
            $newProduct->out_of_stock = 0;
        }

        // Check if old price entered
        if (isset($input['old_price'])) {
            $newProduct->old_price = $input['old_price'];
        }

        // Check if product added & its categories also added
        if ($newProduct->save()
            and $this->storeProductCategories($newProduct->id, $input['categories'])) {
            activityLog('ID of '.$newProduct->id.' product added.');
            $productID = $newProduct->id;

            // Check if related Products exist & related products added
            if (!empty($related_products)
               and !$this->storeRelatedProducts($productID, $input['related_products'])) {
                return false;
            }

            return $productID;
        }

        return false;
    }

    /**
     * Fetch all products.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAll($productID = null)
    {
        $products = $this->product
                            ->select(
                                'id',
                                'name',
                                'product_id'
                            );

        // Check if product id exist
        if (!empty($productID)) {
            return $products->where('id', '!=', $productID)->get();
        }

        return $products->get();
    }

    /**
     * Fetch products datatable source.
     * 
     * @param (int) $categoryID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDataTableSource($categoryID = null, $brandId = null)
    {
        $dataTableConfig = [
            'fieldAlias' => [
            'creation_date' => 'created_at',
            'updation_date' => 'updated_at',
            ],
            'searchable' => [
                'name', 'product_id', 'description', 'price',
            ],
        ];

        $select = ['id', 'name', 'thumbnail', 'status', 'out_of_stock', 'featured', 'brands__id', 'created_at', 'updated_at'];

        if (!empty($categoryID)) {
            $products = $this->product
                              ->with(['categories' => function ($query) use ($categoryID) {
                                $query->where('categories_id', $categoryID)->select('products_id');
                              }, 'brand'])->get();

            $productID = [];
            if (!empty($products)) {
                foreach ($products as $key => $product) {
                    foreach ($product->categories as $key => $category) {
                        $productID[] = $category->products_id;
                    }
                }
            }

            return $this->product
                        ->with(['categories' => function ($query) use ($categoryID) {
                            $query->where('categories_id', $categoryID);
                        }, 'brand'])
                        ->whereIn('id', $productID)
                        ->select($select)
                        ->dataTables($dataTableConfig)
                        ->toArray();
        }

        if (!empty($brandId)) {
            return $this->product
                    ->with('categories', 'brand')
                    ->where('brands__id', $brandId)
                    ->select($select)
                    ->dataTables($dataTableConfig)
                    ->toArray();
        }

        return $this->product
                    ->with('categories', 'brand')
                    ->select($select)
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Fetch product by id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByID($productID, $selectOnly = false)
    {
        $product = $this->product->where('id', $productID);

        // Check if select only exist
        if ($selectOnly) {
            $product->select(
                    'name',
                    'thumbnail',
                    'product_id',
                    'description',
                    'price',
                    'old_price',
                    'status',
                    'featured',
                    'out_of_stock',
                    'brands__id',
                    'youtube_video'
                );
        }

        return $product->first();
    }

    /**
     * Fetch product for detail dialog by product ID.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductDetailForDetailDialog($productID, $selectOnly = false)
    {
        $query = $this->product
                    ->with('relatedProducts', 'image', 'categories', 'specification')
                    ->with(['option' => function ($query) {
                            $query->with('optionValues');
                    }]);

        if (isAdmin()) {
            $query->ofId($productID);
        } else {
            $query->where([
                            'id' => $productID,
                            'status' => 1, // active
                        ]);
        }

        return $query->select(
                        'id',
                        'name',
                        'thumbnail',
                        'product_id',
                        'description',
                        'status',
                        'out_of_stock',
                        'old_price',
                        'price',
                        'youtube_video',
                        'featured',
                        'status',
                        'brands__id',
                        'created_at',
                        'updated_at'
                    )->first();
    }

    /**
     * Fetch product by id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductByIDWithStock($productID)
    {
        return $this->product->where('id', $productID)
                        ->select(
                            'id',
                            'out_of_stock'
                        )->first();
    }

    /**
     * Fetch product count by id.
     *
     * @param number $productID
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchCountByID($productID)
    {
        return $this->product->where('id', $productID)->count();
    }

    /**
     * Delete product using its collection.
     *
     * @param object $product
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function delete($product)
    {
        // Check if product deleted			
        if ($product->delete()) {
            activityLog('ID of '.$product->id.' product deleted.');

            return true;
        }

        return false;
    }

    /**
     * Fetch product details using product id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchDetails($productID)
    {
        return $this->product
                    ->select('id', 'name', 'status')
                    ->where('id', $productID)
                    ->first();
    }

    /**
     * Fetch product details using product id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategory($productID)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->select('products_id', 'categories_id')
                    ->get();
    }

    /**
     * Fetch products categories.
     *
     * @param array $productIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductsCategories($productIDs)
    {
        return $this->productCategory
                    ->whereIn('products_id', $productIDs)
                    ->get(['products_id', 'categories_id']);
    }

    /**
     * Fetcfh all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function allCategories()
    {
        return $this->category->all();
    }

    /**
     * Fetcfh all categories.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchCategoryByID($categoryID)
    {
        return $this->category
                    ->where('id', $categoryID)
                    ->first(['id', 'name', 'parent_id']);
    }

    /**
     * Fetch all related products.
     *
     * @param number $relatedProductID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRelatedProductsByID($relatedProductID)
    {
        return $this->product
                    ->where('id', $relatedProductID)
                    ->first(['id', 'name']);
    }

    /**
     * Fetch all brand.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchBrandByID($brandId)
    {
        return $this->brand
                    ->where('_id', $brandId)
                    ->first(['_id', 'name']);
    }

    /**
     * Fetch product option label for detail dialog.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductOptionLabel($productID)
    {
        return $this->productOptionLabel
                    ->where('products_id', $productID)
                    ->get(['id', 'name']);
    }

    /**
     * Fetch product option values for detail dialog.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductOptionValues($optionID)
    {
        return $this->productOptionValue
                    ->where('product_option_labels_id', $optionID)
                    ->get(['name', 'addon_price']);
    }

    /**
     * Delete product`s related products.
     *
     * @param number $productID
     * @param array  $relatedProductsIDs
     *
     * @return bool
     *---------------------------------------------------------------- */
    private function deleteRelatedProducts($productID, $relatedProductsIDs)
    {
        return $this->relatedProduct
                    ->where('products_id', $productID)
                    ->whereIn('related_product_id', $relatedProductsIDs)
                    ->delete();
    }

    /**
     * Delete product`s categories.
     *
     * @param number $productID
     * @param array  $productCategoriesIDs
     *
     * @return bool
     *---------------------------------------------------------------- */
    private function deleteProductCategories($productID, $productCategoriesIDs)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->whereIn('categories_id', $productCategoriesIDs)
                    ->delete();
    }

    /**
     * Update product.
     *
     * @param object $product
     * @param array  $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function update($product, $input)
    {
        $isSomethingUpdated = false;

        $updateData = [
            'name' => $input['name'],
            'product_id' => $input['product_id'],
            'description' => $input['description'],
            'price' => $input['price'],
            'brands__id' => (!empty($input['brands__id'])) ? $input['brands__id'] : null,
            'youtube_video' => isset($input['youtube_video'])
                                   ? $input['youtube_video']
                                   : null,
        ];

        $productID = $product->id;

        // Check if image thumbnail selected
        if (isset($input['image'])) {
            $updateData['thumbnail'] = $input['image'];
        }

        // Check if old price entered
        $updateData['old_price'] = isset($input['old_price'])
                                   ? $input['old_price']
                                   : null;

        // Check if featured entered
        if (isset($input['featured'])) {
            $updateData['featured'] = $input['featured'];
        }

         // Check if out of stock select
        if (isset($input['outOfStock'])) {
            $updateData['out_of_stock'] = $input['outOfStock'];
        }

        // Check if product updated
        if ($product->modelUpdate($updateData)) {
            activityLog('ID of '.$productID.' product update.');
            $isSomethingUpdated = true;
        }

        // Check if related products exist
        if (!empty($input['related_products'])
            and $this->storeRelatedProducts($productID,
                                         $input['related_products']
                                        )) {
            $isSomethingUpdated = true;
        }

        // Check if delete related products exist
        if (!empty($input['delete_related_products'])
            and $this->deleteRelatedProducts($productID,
                 $input['delete_related_products']
                )) {
            $isSomethingUpdated = true;
        }

        // Check if categories exist
        if (!empty($input['categories'])
            and $this->storeProductCategories($productID,
                                         $input['categories']
                                        )) {
            $isSomethingUpdated = true;
        }

        // Check if categories exist
        if (!empty($input['delete_categories'])
            and $this->deleteProductCategories($productID,
                                         $input['delete_categories']
                                        )) {
            $isSomethingUpdated = true;
        }

        // Check if delete related products exist
        if (!empty($input['delete_related_products'])
            and $this->deleteRelatedProducts($productID,
                 $input['delete_related_products']
                )) {
            $isSomethingUpdated = true;
        }

        return $isSomethingUpdated;
    }

    /**
     * Fetch product`s related products using given product id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchRelatedProductsByProductID($productID)
    {
        return $this->relatedProduct
                    ->where('products_id', $productID)
                    ->get()
                    ->lists('related_product_id')
                    ->all();
    }

    /**
     * Fetch product categories using product id.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductCategoriesByProductID($productID)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->get()
                    ->lists('categories_id')
                    ->all();
    }

    /**
     * Store product image.
     *
     * @param number productID
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeImage($productID, $input)
    {
        $newImage = new $this->productImage();

        $newImage->title = $input['title'];
        $newImage->file_name = $input['file_name'];
        $newImage->products_id = $productID;

        // Check if product image save
        if ($newImage->save()) {
            activityLog('ID of '.$productID.' product image added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch product images datatable source.
     *
     * @param number productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchImagesDataTableSource($productID)
    {
        $dataTableConfig = [
            'searchable' => [
                'title',
            ],
        ];

        return $this->productImage
                    ->where('products_id', $productID)
                    ->select(
                        'id',
                        'title',
                        'file_name'
                    )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Fetch product image.
     *
     * @param number $productID
     * @param number $imageID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchImage($productID, $imageID, $selectOnly = false)
    {
        $image = $this->productImage->where([
                                    'products_id' => $productID,
                                    'id' => $imageID,
                                ]);

        // Check if select only exist
        if ($selectOnly) {
            $image->select('title');
        }

        return $image->first();
    }

    /**
     * Delete product image.
     *
     * @param object $productImage
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteImage($productImage)
    {
        // Check if product image delete
        if ($productImage->delete()) {
            activityLog('ID of '.$productImage->products_id.' product image deleted.');

            return true;
        }

        return false;
    }

    /**
     * Update product image.
     *
     * @param object $imageData
     * @param array  $input
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function updateImage($imageData, $input)
    {
        if ($imageData->modelUpdate($input)) {
            activityLog('ID of '.$imageData->products_id.' product image updated.');

            return true;
        }

        return false;
    }

    /**
     * Fetch product options datatable source.
     *
     * @param number productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptionsDataTableSource($productID)
    {
        $dataTableConfig = [
            'searchable' => [
                'name',
            ],
        ];

        return $this->productOptionLabel
                    ->where('products_id', $productID)
                    ->select(
                        'id',
                        'name'
                    )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Store option values.
     *
     * @param number $optionID
     * @param array  $values
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeOptionValues($optionID, $values)
    {
        $optionValues = [];

        foreach ($values as $value) {
            $optionValues[] = [
                'name' => $value['name'],
                'product_option_labels_id' => $optionID,
                'addon_price' => isset($value['addon_price'])
                                               ? $value['addon_price']
                                               : 0,
                'created_at' => getCurrentDateTime(),
                'updated_at' => getCurrentDateTime(),
            ];
        }

        return $this->productOptionValue->insert($optionValues);
    }

    /**
     * Store product option.
     *
     * @param number productID
     * @param array $input
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeOption($productID, $input)
    {
        $productOption = new $this->productOptionLabel();

        $productOption->name = $input['name'];
        $productOption->products_id = $productID;

        // Check if product option added & its values also added
        if ($productOption->save()
            and $this->storeOptionValues($productOption->id, $input['values'])) {
            activityLog('ID of '.$productID.' product option added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch prdouct option.
     *
     * @param number $productID
     * @param number $optionID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOption($productID, $optionID, $selectOnly = false)
    {
        $productOption = $this->productOptionLabel->where([
                                    'products_id' => $productID,
                                    'id' => $optionID,
                                ]);

        // Check if select only exist
        if ($selectOnly) {
            $productOption->select('name');
        }

        return $productOption->first();
    }

    /**
     * Delete prdouct option.
     *
     * @param object $productOption
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteOption($productOption)
    {
        // Check if product option deleted
        if ($productOption->delete()) {
            activityLog('ID of '.$productOption->products_id.' product option deleted.');

            return true;
        }

        return false;
    }

    /**
     * Update option.
     *
     * @param object $productOption
     * @param array  $input
     *
     * @return mixed
     *---------------------------------------------------------------- */
    public function updateOption($productOption, $input)
    {
        // Check if product option updated
        if ($productOption->modelUpdate([
                                    'name' => $input['name'],
                                ])) {
            activityLog('ID of '.$productOption->products_id.' product option updated.');

            return true;
        }

        return false;
    }

    /**
     * Fetch product option count.
     *
     * @param number $productID
     * @param number $optionName
     * @param number $optionID
     *
     * @return number
     *---------------------------------------------------------------- */
    public function fetchProductOptionCount($productID, $optionName,
     $optionID = null)
    {
        $productOption = $this->productOptionLabel
                              ->where([
                                    'products_id' => $productID,
                                    'name' => $optionName,
                                ]);

        if (!empty($optionID)) {
            $productOption->where('id', '!=', $optionID);
        }

        return $productOption->count();
    }

    /**
     * Fetch prdouct option values.
     *
     * @param number $optionID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptionValues($optionID)
    {
        return $this->productOptionValue
                    ->where('product_option_labels_id', $optionID)
                    ->select(
                        'id',
                        'name',
                        'addon_price'
                    )
                    ->get();
    }

    /**
     * Fetch prdouct option values by options ids.
     *
     * @param number $optionID
     * @param array  $optionValuesIDs
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptionValuesByIDs($optionID, $optionValuesIDs)
    {
        return $this->productOptionValue
                    ->where('product_option_labels_id', $optionID)
                    ->where('id', $optionValuesIDs)
                    ->get();
    }

    /**
     * Fetch product option value.
     *
     * @param number $optionID
     * @param number $optionValueID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptionValue($optionID, $optionValueID)
    {
        return $this->productOptionValue
                    ->where('product_option_labels_id', $optionID)
                    ->where('id', $optionValueID)
                    ->first();
    }

    /**
     * Delete product option value.
     *
     * @param object $optionValue
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteOptionValue($optionValue)
    {
        return $optionValue->delete();
    }

    /**
     * Update product option values.
     *
     * @param number $optionID
     * @param array  $optionValues
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateOptionValues($productID, $optionID, $optionValues)
    {
        if (__dbBatchUpdate('product_option_values', $optionValues, 'id')) {
            return true;
        }

        return false;
    }

    /**
     * Update new option values.
     *
     * @param number $optionID
     * @param array  $productID
     * @param number $newInputValues
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateNewOptionValues($productID, $optionID, $newInputValues)
    {
        $newOptionValues = [];

        foreach ($newInputValues as $newInputValue) {
            $newOptionValues[] = [
                'name' => $newInputValue['name'],
                'product_option_labels_id' => $optionID,
                'addon_price' => isset($newInputValue['addon_price'])
                                               ? $newInputValue['addon_price']
                                               : 0,
                'created_at' => getCurrentDateTime(),
                'updated_at' => getCurrentDateTime(),
            ];
        }

        // Check if products option values added.
        if ($this->productOptionValue->insert($newOptionValues)) {
            activityLog('ID of '.$productID.' product option values updated.');

            return true;
        }

        return false;
    }

    /**
     * Fetch any product options.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOptions($productID)
    {
        return $this->productOptionLabel
                    ->where('products_id', $productID)
                    ->select('name')
                    ->get();
    }

    /**
     * fetch all settings.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllSetting()
    {
        return $this->setting->get();
    }

    /**
     * fetch product related category.
     * 
     * @param int $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductWithCat($productID)
    {
        return  $this->product
                     ->where('id', $productID)
                     ->with(['categories' => function ($category) use ($productID) {

                        $category->where('products_id', $productID)->select('categories_id');

                    }])
                    ->get(['id', 'name', 'status', 'out_of_stock']);
    }

    /**
     * fetch product.
     * 
     * @param int $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductByID($productID)
    {
        $optionValueID = [8, 10];

        return $this->product
                    ->where('id', $productID)
                    ->with(['categories' => function ($category) use ($productID) {

                        $category->where('products_id', $productID)->select('categories_id');

                    }])
                    ->with(['option' => function ($option) use ($optionValueID) {
                        $option
                            ->with(['optionValues' => function ($optionValue) use ($optionValueID) {
                                $optionValue->whereIn('id', $optionValueID);
                            }]);
                    }])->get([
                         'id',
                         'name',
                         'thumbnail',
                         'product_id',
                         'price',
                         'status',
                         'out_of_stock',
                    ]);
    }

    /**
     * fetch product for cart items.
     * 
     * @param int $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchProductByCartProductID($productID, $optionValueIDs)
    {
        return $this->product
                    ->whereIn('id', $productID)
                    ->with(['categories' => function ($category) use ($productID) {

                        $category->whereIn('products_id', $productID)->select('categories_id');

                    }])
                    ->with([
                        'option' => function ($option) use ($optionValueIDs) {
                            $option->with(['optionValues' => function ($optionValue) use ($optionValueIDs) {
                                    $optionValue->whereIn('id', $optionValueIDs);
                            }]);
                        },
                    ])
                    ->with('brand')
                    ->select(
                        'id',
                         'name',
                         'thumbnail',
                         'product_id',
                         'price',
                         'status',
                         'out_of_stock',
                        'brands__id'
                    )->get();
    }

    /**
     * fetch all products status in group by.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchAllProducts()
    {
        return $this->product
                    ->select('status', DB::raw('COUNT(status) as productCount'))
                    ->groupBy('status')
                    ->get();
    }

    /**
     * fetch out of stock product count
     * availble in store.
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchOutOfStockCount()
    {
        return $this->product
                    ->where('out_of_stock', 1)
                    ->count();
    }

    /**
     * Fetch product specification datatable source.
     *
     * @param number productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSpecificationDataTableSource($productID)
    {
        $dataTableConfig = [
            'searchable' => [
                'name',
            ],
        ];

        return $this->productSpecification
                    ->where('products_id', $productID)
                    ->select(
                        '_id',
                        'name',
                        'value'
                    )
                    ->dataTables($dataTableConfig)
                    ->toArray();
    }

    /**
     * Store specification values.
     *
     * @param number $optionID
     * @param array  $values
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function storeSpecificationValues($productID, $inputs)
    {
        $specificationValues = [];

        foreach ($inputs as $input) {
            $specificationValues[] = [
                'name' => $input['name'],
                'value' => $input['value'],
                'products_id' => $productID,
                'created_at' => getCurrentDateTime(),
                'updated_at' => getCurrentDateTime(),
            ];
        }

        // Check if product specification added
        if ($this->productSpecification->insert($specificationValues)) {
            activityLog('ID of '.$productID.' product specification added.');

            return true;
        }

        return false;
    }

    /**
     * Fetch prdouct specification values.
     *
     * @param number $productID
     *
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchSpecificationByID($specificationID)
    {
        return $this->productSpecification
                    ->where('_id', $specificationID)
                    ->first(['_id', 'name', 'value', 'products_id']);
    }

    /**
     * Update specification values.
     *
     * @param number $optionID
     * @param array  $values
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateSpecificationValues($specificationData, $input)
    {
        if ($this->productSpecification->where('_id', $specificationData->_id)->update([
                                    'name' => $input['name'],
                                    'value' => $input['value'],
                                ])) {
            activityLog('ID of '.$specificationData->products_id.' product specification updated.');

            return true;
        }

        return false;
    }

    /**
     * Delete product Specification value.
     *
     * @param object $specificationValue
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteSpecificationValue($specificationValue)
    {
        // Check if product specification deleted
        if ($this->productSpecification->where('_id', $specificationValue->_id)->delete()) {
            activityLog('ID of '.$specificationValue->products_id.' product specification deleted.');

            return true;
        }

        return false;
    }

    /**
     * fetch specification data.
     *
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchAllSpecificationValues()
    {
        return $this->productSpecification->distinct()->get(['_id', 'name', 'value']);
    }

    /**
     * fetch product by id.
     * 
     * @param $productID
     * 
     * @return eloquent collection object
     *---------------------------------------------------------------- */
    public function fetchByProductID($productID)
    {
        return $this->productCategory
                    ->where('products_id', $productID)
                    ->get(['products_id', 'categories_id']);
    }

    /**
     * Fetch ids by brand Id.
     *
     * @param int $brandId
     *
     * @return array
     *---------------------------------------------------------------- */
    public function fetchIdsByBrandId($brandId)
    {
        return $this->product->where('brands__id', $brandId)->pluck('id')->all();
    }

    /**
     * Delete by ids.
     *
     * @param array $ids
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function deleteByIds($ids)
    {
        return $this->product->whereIn('id', $ids)->delete();
    }

    /**
     * Update product status.
     *
     * @param object $product
     * @param int    $status
     *
     * @return bool
     *---------------------------------------------------------------- */
    public function updateStatus($product, $status)
    {
        // Check if product status updated
        if ($product->modelUpdate(['status' => $status])) {
            activityLog('Id of '.$product->id.' product status updated.');

            return true;
        }

        return false;
    }
}
