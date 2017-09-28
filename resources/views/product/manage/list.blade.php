<div ng-controller="ProductListController as ProductListCtrl">

	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			
			<!-- main heading when parent are selected -->
	        <span ng-show="!ProductListCtrl.parentCategoryExist && !ProductListCtrl.categoryStatus">
	        	<?= __( 'Manage Categories & Products' ) ?>
	        </span>
	        <!-- /main heading when parent are selected -->
			
			<!-- main heading when child category or its product are selected -->
	        <span ng-show="ProductListCtrl.parentCategoryExist">
	        	<a ui-sref="categories({mCategoryID:''})"><?= __( 'Manage Categories & Products' ) ?></a> » <a ui-sref="categories({mCategoryID:ProductListCtrl.parentCategory.parent_id})" href>...</a> » <span ng-bind="ProductListCtrl.parentCategory.name"></span>
	        	<a href ui-sref="categories.edit({catID:ProductListCtrl.parentCategory.id})" title="<?= __('Edit') ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
	        </span>
	        <!-- /main heading when child category or its product are selected -->
			
			<!-- main heading when parent are selected in product tab -->
	        <span ng-show="ProductListCtrl.categoryStatus">
	        	<a ui-sref="products({mCategoryID:''})"><?= __( 'Manage Categories & Products' ) ?></a> » <a ui-sref="products({mCategoryID:ProductListCtrl.category.parent_id})" href>...</a> » <span ng-bind="ProductListCtrl.category.name"></span>
	        </span>
	        <!-- /main heading when parent are selected in product tab -->

        </h3>
		<!-- /main heading -->
		
		<!-- Back to parent category button -->
        <div class="lw-section-right-content">
        	<span title="<?=  __('Back To Parent Category')  ?>" ng-show="ProductListCtrl.categoryStatus">
	        <a class="lw-btn btn btn-default btn-sm" ui-sref="products({mCategoryID:ProductListCtrl.category.parent_id})" href><i class="fa fa-arrow-left"></i></a>
	        </span>
		</div>
		<!-- /Back to parent category button -->
    </div>
	
	<div class="pull-right">
		<!-- Add new category button -->
		<a title="<?=  __('Add New Category')  ?>" class="btn btn-default btn-sm" ui-sref="categories.add()" href><i class="fa fa-plus"></i> <?=  __('Add New Category')   ?></a>
		<!-- /Add new category button -->
		
		<!-- Add new product button -->
		<a href class="btn btn-sm btn-default" title="<?= __( 'Add New Product' ) ?>" ui-sref="category_product_add({categoryID : ProductListCtrl.categoryID})"><i class="fa fa-plus"></i> <?= __( 'Add New Product' ) ?></a>
		<!-- /Add new product button -->
	</div>

    <!-- tab heading -->
	<ul class="nav nav-tabs lw-tabs" role="tablist">
		<!-- category tab -->
		<li role="presentation" class="manageCategoriesTab" id="manageCategoriesTab">
			<a href="#manageCategories" ng-click="ProductListCtrl.goToCategories($event)" role="tab" title="<?= __( 'Categories' ) ?>" aria-controls="manageCategories" data-toggle="tab">
				<?=  __('Categories')  ?>
			</a>
		</li>
		<!-- /category tab -->

		<!-- product tab -->	
		<li role="presentation" class="tabpanel manageProductsTab active" id="manageProductsTab">
			<a href="#manageProducts" ng-click="ProductListCtrl.goToProducts($event)" role="tab" title="<?= __( 'Products' ) ?>" aria-controls="manageProducts" data-toggle="tab">
				<?=  __('Products')  ?>
			</a>
		</li>
		<!-- /product tab -->
	</ul>
	<br>
	<!-- /tab heading -->
	
	<div class="tab-content lw-tab-content">
			
		<div role="tabpanel" class="tab-pane fade in manageCategories" id="manageCategories">
		</div>
		
		<div role="tabpanel" class="tab-pane fade in manageProducts active" id="manageProducts">

			<!-- manage products  datatable container -->
			<div id="section2">
		  		
				<!-- datatable container -->
			    <div>
			        <!-- datatable -->
			        <table id="productsTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __('Thumbnail')  ?></th>
			                    <th><?=  __('Name')  ?></th>
			                    <th><?=  __('Featured')  ?></th>
			                    <th><?=  __('Stock')  ?></th>
			                    <th><?=  __('Active')  ?></th>
			                    <th><?=  __('Created at')  ?></th>
			                    <th><?=  __('Updated at')  ?></th>
			                    <th><?=  __('Categories')  ?></th>
			                    <th><?=  __('Brand')  ?></th>
			                    <th><?=  __('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
			    <!-- /datatable container -->
			</div>
			<!-- /manage products  datatable container -->

		</div>
        
	</div>

</div>

<!-- manage products section secripts -->

	<!-- productThumbnailColumnTemplate -->
	<script type="text/_template" id="productThumbnailColumnTemplate">

	<a href="<%- __tData.thumbnail_url %>" lw-ng-colorbox class="lw-image-thumbnail"><img src="<%- __tData.thumbnail_url %>"></a> 
	   
	</script>
	<!-- /productThumbnailColumnTemplate -->

	<!-- Manage Product List Name Column Template -->
	<script type="text/_template" id="nameColumnTemplate">
	  	
	  	<span class="custom-page-in-menu"><%-__tData.name %>  
	  	<a target="_blank" href="<%-__tData.detailPageURL %>" title="<?= __('Product Details') ?>"><i class="fa fa-external-link"></i></a>

		</span>
	</script>
	<!-- /Manage Product List Name Column Template -->

	<!-- productActionColumnTemplate -->
	<script type="text/_template" id="productActionColumnTemplate">
	    <a title="<?= __('Edit') ?>" class="lw-btn btn btn-default btn-xs" ui-sref="product_edit.details({productID : '<%- __tData.id %>'})"> 
	        <i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
	    <a title="<?= __('Delete') ?>" class="lw-btn btn btn-danger btn-xs delete-sw" ng-click="ProductListCtrl.productDelete('<%- __tData.id %>','<%- escape(__tData.name) %>' )" ng-href>
	        <i class="fa fa-trash-o fa-lg"></i> <?= __('Delete') ?>
	    </a>
	</script>
	<!-- /productActionColumnTemplatee -->

	<!-- /productBrandColumnTemplate -->
	<script type="text/_template" id="productBrandColumnTemplate">
		<% if (__tData.brand != null) { %>

			<% var bandStatus = (__tData.brand.status == 2) ? 'lw-danger' : '';  %>
	    	<% var brandStatusValue = (__tData.brand.status == 2) ? '<?= __( 'Inactive' ) ?>' : '<?= __( 'Active' ) ?>';  %>

			<span><a class="<%- bandStatus %>" title="<%- brandStatusValue %>" ui-sref="products({ brandID : '<%- __tData.brand._id %>'})">
				<%- __tData.brand.name %></a></span>

		<% } %>
	</script>
	<!-- /productBrandColumnTemplate -->
			
	<!-- Manage Product List creation date Column Template -->
	<script type="text/_template" id="creationDateColumnTemplate">

	   <span class="custom-page-in-menu"><%-__tData.creation_date %></span>

	</script>
	<!-- /Manage Product List creation date Column Template -->

	<!-- Manage Product List updation date Column Template -->
	<script type="text/_template" id="updationDateColumnTemplate">

	   <span class="custom-page-in-menu"><%-__tData.updation_date %></span>

	</script>
	<!-- /Manage Product List updation date Column Template -->
			
	<!-- Manage Product List out of stock product Column Template -->
	<script type="text/_template" id="outOfStockColumnTemplate">
	
	   <% if (__tData.out_of_stock == 0) { %> 
	        <span class="lw-success" title="<?= __( 'In Stock' ) ?>"><?=  __('In Stock')  ?></span>
	   <% } else { %>
	    <span class="lw-danger" title="<?= __( 'Out of Stock' ) ?>"><?=  __('Out of Stock')  ?></span>
	   <% } %>
	</script>
	<!-- /Manage Product List out of stock product Column Template -->

	<!-- Manage Product List out of stock product Column Template -->
	<script type="text/_template" id="featuredProductColumnTemplate">
	
	   <% if (__tData.featured == true) { %> 
	        <span class="lw-success"><?=  __('Yes')  ?></span>
	   <% } else { %>
	    <span class="lw-danger" ><?=  __('No')  ?></span>
	   <% } %>
	</script>
	<!-- /Manage Product List out of stock product Column Template -->


	<!-- Manage Product List Status Column Template -->
	<script type="text/_template" id="productStatusColumnTemplate">
	   <% if (__tData.status === 1) { %> 
	        <span title="<?= __( 'Active' ) ?>"><i class="fa fa-eye"></i></span>
	   <% } else { %>
	    <span title="<?= __( 'Inactive' ) ?>"><i class="fa fa-eye-slash"></i></span>
	   <% } %>
	</script>
	<!-- /Manage Product List Status Column Template -->

	<!-- product categories -->
	<script type="text/_template" id="productCategoriesColumnTemplate">
	    <% var categories = __tData.categories; %>
	    <% _.each(categories, function(category, index) { %>

	    	<% var status = (category.status == 0) ? 'lw-inactive' : '';  %>
	    	<% var statusValue = (category.status == 0) ? '<?= __( 'Inactive' ) ?>' : '<?= __( 'Active' ) ?>';  %>

	        <span class="<%- status %>"><%- category.name %> <%= (index == categories.length - 1) ? '' : '|' %></span>
	        
	    <% }); %> 

	</script>
	<!-- /product categories -->