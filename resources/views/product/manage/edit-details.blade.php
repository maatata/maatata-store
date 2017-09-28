<div ng-controller="ManageProductEditDetailsController as editDetailsCtrl">
    <div>
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __( 'Details' ) ?></h3>
        <!-- /main heading -->
    </div>

    <div ng-hide="editDetailsCtrl.pageStatus" class="text-center">
		<div class="loader"><?=  __('Loading...')  ?></div>
	</div>
	
    <div ng-show="editDetailsCtrl.pageStatus" ng-if="editDetailsCtrl.fancytree_categories">
    	<!-- form action -->
    	<form class="lw-form lw-ng-form form-horizontal col-lg-7 col-md-8 col-sm-12 col-xs-12" 
        name="editDetailsCtrl.[[ editDetailsCtrl.ngFormName ]]" 
        novalidate>
        
	        <!-- Name -->
	        <lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="name"
	              ng-required="true" 
	              autofocus
	              ng-model="editDetailsCtrl.productData.name" />
	        </lw-form-field>
	        <!-- /Name -->

	        <!-- Product ID -->
	        <lw-form-field field-for="product_id" label="<?= __( 'Product ID' ) ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="product_id"
	              ng-required="true" 
	              ng-model="editDetailsCtrl.productData.product_id" />
	        </lw-form-field>
	        <!-- /Product ID -->

	        <div class="lw-form-inline-elements">

	            <!-- Featured -->
	            <lw-form-checkbox-field field-for="featured" label="<?= __( 'Mark as Featured' ) ?>" class="lw-form-item-box">
	                <input type="checkbox" 
		                class="lw-form-field js-switch"
		                name="featured"
		                ng-model="editDetailsCtrl.productData.featured" 
		                ui-switch=""/>
	            </lw-form-checkbox-field>
	            <!-- /Featured -->

	            <!-- Out of Stock -->
	            <lw-form-checkbox-field field-for="outOfStock" label="<?= __( 'Mark Out of Stock' ) ?>" class="lw-form-item-box">
	                <input type="checkbox" 
		                class="lw-form-field js-switch"
		                name="outOfStock"
		                ng-model="editDetailsCtrl.productData.outOfStock" 
		                ui-switch="{color: '#E43B11'}"/>
	            </lw-form-checkbox-field>
	            <!-- /Out of Stock -->
	        </div>

	        <!-- Categories -->
	        <lw-form-field field-for="categories" label="<?= __( 'Categories' ) ?>">

	            <div ng-if="editDetailsCtrl.categories"
	                ng-model="editDetailsCtrl.productData.categories"
		        	class="select fancytree-list" 
		        	name="temp_row_id"
		         	lw-fancytree
		         	source='[[ editDetailsCtrl.fancytree_categories ]]' 
		         	listing-for="products"
		         	form-type='productEdit'
					form-id='[[ editDetailsCtrl.categories ]]'
	            >
	            </div>

	            <input type="text" 
	              class="lw-form-field form-control"
	              name="categories"
	              ng-required="true"
	              readonly="readonly"
	              style="display:none;" 
	              ng-model="editDetailsCtrl.productData.categories" />

	        </lw-form-field>
	        <!-- /Categories -->

	        <!-- Brand ID -->
	        <div class="form-group">
		        <lw-form-selectize-field field-for="brands__id" label="<?= __( 'Brand' ) ?>" class="lw-selectize">
		        	<div class="input-group">
		            	<selectize config='editDetailsCtrl.brandsSelectConfig' class="lw-form-field lw-form-field-brand" name="brands__id" ng-model="editDetailsCtrl.productData.brands__id" options='editDetailsCtrl.activeBrands' placeholder="<?= __( 'Select Brand' ) ?>"></selectize>
		            	<span class="input-group-btn">
							<a href  ng-click="editDetailsCtrl.addBrand()" title="<?=  __('Add New brand')  ?>" class="btn btn-default"><?=  __('Add New brand')  ?></a>
						</span>
		            </div>
		        </lw-form-selectize-field>	
			</div>
			<!-- /Brand ID -->
			
			<!-- image thumbnil -->
	        <div class="form-group">
				<div class="lw-thumb-logo" ng-if="editDetailsCtrl.productData.thumbnail">
		        	<a href="[[editDetailsCtrl.productData.thumbnailURL]]/[[editDetailsCtrl.productData.thumbnail]]" lw-ng-colorbox class="lw-thumb-logo"><img  ng-src="[[editDetailsCtrl.productData.thumbnailURL]]/[[editDetailsCtrl.productData.thumbnail]]" alt=""></a>
		        </div>
	        </div>
			<!-- /image thumbnil -->

	       	<!-- Select Image -->
	       	<div class="form-group">
		        <lw-form-selectize-field field-for="image" label="<?= __( 'Image' ) ?>" class="lw-selectize"><span class="badge lw-badge">[[editDetailsCtrl.images_count]]</span>
		            <selectize config='editDetailsCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="editDetailsCtrl.productData.image" options='editDetailsCtrl.image_files' placeholder="<?= __( 'Select Image' ) ?>"></selectize>
		        </lw-form-selectize-field> 
                <div class="lw-form-append-btns">
                    <span class="btn btn-primary btn-xs lw-btn-file">
                        <i class="fa fa-upload"></i> 
                                <?=   __('Upload New Images')   ?>
                        <input type="file" nv-file-select="" uploader="editDetailsCtrl.uploader" multiple/>
                </span>
                <button class="btn btn-default btn-xs" title="<?= __('Uploaded Images')  ?>" 
                    ng-click="editDetailsCtrl.showUploadedMediaDialog()"  type="button"><?=  __("Uploaded Images")  ?>
                </button>
                </div>
	        </div>
	        <!-- /Select Image -->
	        
	        <!-- Youtube Video Code -->
	        <lw-form-field field-for="youtube_video" label="<?= __( 'Youtube Video Code' ) ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control "
	              name="youtube_video"
	              autofocus
	              ng-model="editDetailsCtrl.productData.youtube_video" />
	        </lw-form-field>
	        <!-- /Youtube Video Code -->
            
            <div class="alert alert-info row">
                <small ><strong><?=  __('Please Note: ')  ?></strong> <?=  __('Use only youtube video code, as in following eg. __sampleYoutubeLink__',[
                '__sampleYoutubeLink__' => 'https://www.youtube.com/watch?v=10r9ozshGVE' ])  ?></small>
            </div>

			<!-- Old Price -->
	        <lw-form-field field-for="old_price" label="<?= __( 'Old Price' ) ?>"> 
	            <div class="input-group">
	                <span class="input-group-addon" ng-bind-html="editDetailsCtrl.store_currency_symbol"></span>
	                <input type="number" 
	                  class="lw-form-field form-control"
	                  name="old_price"
	                  min="0"
	                  ng-model="editDetailsCtrl.productData.old_price" />
	                <span class="input-group-addon" ng-bind-html="editDetailsCtrl.store_currency"></span>
	            </div>
	        </lw-form-field>
	        <!-- /Old Price -->

	        <!-- Price -->
	        <lw-form-field field-for="price" label="<?= __( 'Price' ) ?>"> 
	            <div class="input-group">
	                <span class="input-group-addon" ng-bind-html="editDetailsCtrl.store_currency_symbol"></span>
	                <input type="number" 
	                  class="lw-form-field form-control"
	                  name="price"
	                  min="0.1"
	                  ng-required="true" 
	                  ng-model="editDetailsCtrl.productData.price" />
	                <span class="input-group-addon" ng-bind-html="editDetailsCtrl.store_currency"></span>
	            </div>
	        </lw-form-field>
	        <!-- /Price -->

	        <!-- Description -->
	        <lw-form-field field-for="description" label="<?= __( 'Description' ) ?>"> 
	            <textarea name="description" class="lw-form-field form-control" ng-required="true"
	             cols="30" rows="10" lw-ck-editor ng-model="editDetailsCtrl.productData.description"></textarea>
	         </lw-form-field>
	        <!-- /Description -->

	        <!-- Related Products -->
	        <div class="form-group">
		        <lw-form-selectize-field field-for="related_products" label="<?= __( 'Related Products' ) ?>" class="lw-selectize">
		            <selectize config='editDetailsCtrl.relatedProductsSelectConfig' class="lw-form-field" name="related_products" ng-model="editDetailsCtrl.productData.related_products" options='editDetailsCtrl.related_products' placeholder="<?= __( 'Select Related Products' ) ?>" ></selectize>
		        </lw-form-selectize-field>
	        </div>
	        <!-- /Related Products -->

			<!-- button action -->
	        <div class="form-group">
	            <button type="submit" ng-click="editDetailsCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>

	            <button type="button" ui-sref="products" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>

	            <button type="button" class="lw-btn btn btn-default" title="<?= __('To show the details of this product') ?>"> 
	            	<a href="[[editDetailsCtrl.productData.viewPage]]" 
	            		target="_new" ><?= __('View Page') ?> <i class="fa fa-external-link"></i></a>
	            </button>
	        </div>
			<!-- /button action -->
	    </form>
	    <!-- /form action -->
    </div>
</div>
</div>

<!-- imageListItemTemplate -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /imageListItemTemplate -->

<!-- imageListOptionTemplate -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /imageListOptionTemplate -->

