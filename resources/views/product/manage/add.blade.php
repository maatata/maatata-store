<div ng-controller="ManageProductAddController as addProductCtrl">
    
    <div ng-if="addProductCtrl.fancytree_categories">
    <div class="lw-section-heading-block">
    	<!-- main heading -->
    	<div ng-if="addProductCtrl.categoryStatus == false">
	        <h3 class="lw-section-heading">
	        	<?= __( 'Add Product' ) ?>
	        </h3>
	    </div>
        <div ng-if="addProductCtrl.categoryStatus == true">
	        <h3 class="lw-section-heading" ng-bind="addProductCtrl.categoryName">
	        </h3>
        </div>
        <!-- /main heading -->
    </div>

	<!-- form action -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6" 
        name="addProductCtrl.[[ addProductCtrl.ngFormName ]]" 
        novalidate>

        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control "
              name="name"
              ng-required="true" 
              autofocus
              ng-model="addProductCtrl.productData.name" />
        </lw-form-field>
        <!-- Name -->

        <!-- Product ID -->
        <lw-form-field field-for="product_id" label="<?= __( 'Product ID' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="product_id"
              ng-required="true" 
              ng-model="addProductCtrl.productData.product_id" />
        </lw-form-field>
        <!-- Product ID -->

        <div class="lw-form-inline-elements col-lg-12">
            <!-- Featured -->
            <lw-form-checkbox-field field-for="featured" label="<?= __( 'Mark as Featured' ) ?>" class="lw-form-item-box">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="featured"
                    ng-model="addProductCtrl.productData.featured" 
                    ui-switch=''/>
            </lw-form-checkbox-field>
            <!-- /Featured -->

            <!-- Out of Stock -->
            <lw-form-checkbox-field field-for="out_of_stock" label="<?= __( 'Mark Out of Stock' ) ?>" class="lw-form-item-box">
                <input type="checkbox" 
                    class="lw-form-field js-switch red"
                    name="out_of_stock"
                    ng-model="addProductCtrl.productData.out_of_stock" 
                    ui-switch="{color: '#E43B11'}" />
            </lw-form-checkbox-field>
            <!-- Out of Stock -->
        </div>
            
        <!-- Categories -->
        <lw-form-field field-for="categories" label="<?= __( 'Categories' ) ?>">
    
            <div 
                ng-model="addProductCtrl.productData.categories"
                class="select fancytree-list" 
                name="temp_row_id"
                lw-fancytree
                source='[[ addProductCtrl.fancytree_categories ]]'
                field-for="categories" 
                listing-for='products'
                form-type='productAdd'
                form-id='[[ addProductCtrl.productData.categoryID ]]'
            >
            </div>

            <input type="text" 
              class="lw-form-field form-control"
              name="categories"
              ng-required="true"
              readonly="readonly"
              style="display:none;" 
              ng-model="addProductCtrl.productData.categories" />

        </lw-form-field>
        <!-- Categories -->
        <!-- Brand ID -->   
        <div class="form-group">
            <lw-form-selectize-field field-for="brands__id" label="<?= __( 'Brand' ) ?>" class="lw-selectize">
                <div class="input-group">
                    <selectize config='addProductCtrl.brandsSelectConfig' class="lw-form-field lw-form-field-brand" name="brands__id" ng-model="addProductCtrl.productData.brands__id" options='addProductCtrl.activeBrands' placeholder="<?= __( 'Select Brand' ) ?>"></selectize>
                    <span class="input-group-btn">
                        <a href  ng-click="addProductCtrl.addBrand()" title="<?=  __('Add New brand')  ?>" class="btn btn-default"><?=  __('Add New brand')  ?></a>
                    </span>
                </div>
            </lw-form-selectize-field>  
        </div>
        <!-- Brand ID -->

       	<!-- Select Image -->
       	<div class="form-group">
       		<lw-form-selectize-field field-for="image" label="<?= __( 'Image' ) ?>" class="lw-selectize"><span class="badge lw-badge">[[addProductCtrl.images_count]]</span>
	            <selectize config='addProductCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="addProductCtrl.productData.image" options='addProductCtrl.image_files' placeholder="<?= __( 'Select Image' ) ?>" ng-required="true"></selectize> 
	        </lw-form-selectize-field>
            <div class="lw-form-append-btns">
                <span class="btn btn-primary btn-xs lw-btn-file">
                    <i class="fa fa-upload"></i> 
                            <?=   __('Upload New Images')   ?>
                    <input type="file" nv-file-select="" uploader="addProductCtrl.uploader" multiple/>
                </span>
                <button class="btn btn-default btn-xs" title="<?= __('Uploaded Images')  ?>" 
                ng-click="addProductCtrl.showUploadedMediaDialog()"  type="button">
                <?=  __("Uploaded Images")  ?></button>
            </div>
       	</div>
        <!-- Select Image -->
        
        <!-- Youtube Video Code -->
        <lw-form-field field-for="youtube_video" label="<?= __( 'Youtube Video' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control "
              name="youtube_video"
              autofocus
              ng-model="addProductCtrl.productData.youtube_video" />
        </lw-form-field>
        <!-- Youtube Video Code -->

        <div class="alert alert-info row">
          <small ><strong><?=  __('Please Note: ')  ?></strong> <?=  __('Use only youtube video code, as in following eg. __sampleYoutubeLink__',[
            '__sampleYoutubeLink__' => 'https://www.youtube.com/watch?v=10r9ozshGVE' ])  ?></small>
        </div>

        <!-- Old Price -->
        <lw-form-field field-for="old_price" label="<?= __( 'Old Price' ) ?>"> 
            <div class="input-group">
                <span class="input-group-addon" ng-bind-html="addProductCtrl.store_currency_symbol"></span>
                <input type="number" 
                  class="lw-form-field form-control"
                  name="old_price"
                  min="0"
                  ng-model="addProductCtrl.productData.old_price" />
                <span class="input-group-addon" ng-bind-html="addProductCtrl.store_currency"></span>
            </div>
        </lw-form-field>
        <!-- Old Price -->

        <!-- Price -->
        <lw-form-field field-for="price" label="<?= __( 'Price' ) ?>"> 
            <div class="input-group">
                <span class="input-group-addon" ng-bind-html="addProductCtrl.store_currency_symbol"></span>
                <input type="number" 
                  class="lw-form-field form-control"
                  name="price"
                  ng-required="true"
                  min="0.1"
                  ng-model="addProductCtrl.productData.price" />
                <span class="input-group-addon" ng-bind-html="addProductCtrl.store_currency"></span>
            </div>
        </lw-form-field>
        <!-- Price -->

        <!-- Description -->
        <lw-form-field field-for="description" label="<?= __( 'Description' ) ?>"> 
            <textarea name="description" class="lw-form-field form-control" ng-required="true"
             cols="30" rows="10" lw-ck-editor ng-minlength="10" ng-model="addProductCtrl.productData.description"></textarea>
         </lw-form-field>
        <!-- Description -->

        <!-- Related Products -->
        <div class="form-group">
	        <lw-form-selectize-field field-for="related_products" label="<?= __( 'Related Products' ) ?>" class="lw-selectize">
	            <selectize config='addProductCtrl.relatedProductsSelectConfig' class="lw-form-field" name="related_products" ng-model="addProductCtrl.productData.related_products" options='addProductCtrl.related_products' placeholder="<?= __( 'Select Related Products' ) ?>" ></selectize>
	        </lw-form-selectize-field>
        </div>
        <!-- Related Products -->

   
        <div class="form-group">

            <button type="submit" 
            	ng-click="addProductCtrl.saveAndAddOptions()" 
            	class="lw-btn btn btn-primary" 
            	title="<?= __('Save for now &amp; continue to add options (Publically not available until mark as active.)') ?>">
            <?= __('Save &amp; Continue') ?> <span></span></button>

            <button type="submit" 
            	ng-click="addProductCtrl.saveAndPublish()" 
            	class="lw-btn btn btn-default" 
            	title="<?= __('Save this item &amp; mark as active.') ?>">
            <?= __('Save &amp; Publish') ?> <span></span></button>

            <button type="button" 
            		ui-sref="products" 
            		class="lw-btn btn btn-default" 
            		title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
</div>

    </form>
	<!-- form action -->
	</div>

</div>
<!-- image path and name -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->

<!-- image path and name -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->
