<div ng-controller="CategoryController as categoryCtrl">

	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			
			<!-- main heading when parent are selected -->
	        <span ng-show="!categoryCtrl.parentCategoryExist && !categoryCtrl.categoryStatus">
	        	<?= __( 'Manage Categories & Products' ) ?>
	        </span>
	        <!-- /main heading when parent are selected -->
			
			<!-- main heading when child category or its product are selected -->
	        <span ng-show="categoryCtrl.parentCategoryExist">
	        	<a ui-sref="categories({mCategoryID:''})"><?= __( 'Manage Categories & Products' ) ?></a> » <a ui-sref="categories({mCategoryID:categoryCtrl.parentCategory.parent_id})" href>...</a> » <span ng-bind="categoryCtrl.parentCategory.name"></span>
	        	<a href ui-sref="categories.edit({catID:categoryCtrl.parentCategory.id})" title="<?= __('Edit') ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
	        </span>
	        <!-- /main heading when child category or its product are selected -->
			
			<!-- main heading when parent are selected in product tab -->
	        <span ng-show="categoryCtrl.categoryStatus">
	        	<a ui-sref="categories({mCategoryID:''})"><?= __( 'Manage Categories & Products' ) ?></a> » <a ui-sref="categories({mCategoryID:categoryCtrl.category.parent_id})" href>...</a> » <span ng-bind="categoryCtrl.category.name"></span>
	        </span>
	        <!-- /main heading when parent are selected in product tab -->

        </h3>
		<!-- /main heading -->
		
		<!-- Back to parent category button -->
        <div class="lw-section-right-content">
        	<span title="<?=  __('Back To Parent Category')  ?>" ng-show="categoryCtrl.parentCategoryExist">
	        <a class="lw-btn btn btn-default btn-sm" ui-sref="categories({mCategoryID:categoryCtrl.parentCategory.parent_id})" href><i class="fa fa-arrow-left"></i></a>
	        </span>
		</div>
		<!-- /Back to parent category button -->
    </div>
	
	<div class="pull-right">
		<!-- Add new category button -->
		<a title="<?=  __('Add New Category')  ?>" class="btn btn-default btn-sm" ui-sref="categories.add()" href><i class="fa fa-plus"></i> <?=  __('Add New Category')   ?></a>
		<!-- /Add new category button -->
		
		<!-- Add new product button -->
		<a href class="btn btn-sm btn-default" title="<?= __( 'Add New Product' ) ?>" ui-sref="category_product_add({categoryID : categoryCtrl.categoryID})"><i class="fa fa-plus"></i> <?= __( 'Add New Product' ) ?></a>
		<!-- /Add new product button -->
	</div>

    <!-- tab heading -->
	<ul class="nav nav-tabs lw-tabs" role="tablist">
		
        <!-- category tab -->
		<li role="presentation" class="manageCategoriesTab active" id="manageCategoriesTab">
			<a href="#manageCategories" ng-click="categoryCtrl.goToCategories($event)" role="tab" title="<?= __( 'Categories' ) ?>" aria-controls="manageCategories" data-toggle="tab">
				<?=  __('Categories')  ?>
			</a>
		</li>
		<!-- /category tab -->

		<!-- product tab -->	
		<li role="presentation" class="tabpanel manageProductsTab" id="manageProductsTab">
			<a href="#manageProducts" ng-click="categoryCtrl.goToProducts($event)" role="tab" title="<?= __( 'Products' ) ?>" aria-controls="manageProducts" data-toggle="tab">
				<?=  __('Products')  ?>
			</a>
		</li>
		<!-- /product tab -->

	</ul>
	<br>
	<!-- /tab heading -->
	
	<div class="tab-content lw-tab-content">
			
		<div role="tabpanel" class="tab-pane fade in manageCategories active" id="manageCategories">

			<!-- manage categories datatable container -->
			<div id="section1">

		    	<!-- manage categories datatable header column name -->

					<div class="alert alert-warning" ng-show="categoryCtrl.isParentInactive == 0"><?=  __("One of it's parent may be Inactive")  ?></div>

					<table id="categoriesTabList" class="category-table table table-striped table-bordered" cellspacing="0" width="100%">
						<thead class="page-header">
							<tr>
								<th><?=  __('Name')  ?></th>
								<th><?=  __('Active')  ?></th>
								<th><?=  __('Subcategories')  ?></th>
								<th><?=  __('Products')  ?></th>
								<th><?=  __('Action')  ?></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				<!-- /manage categories datatable header column name -->
		   	</div>
			<!--/ manage categories datatable container -->
			
			<div ui-view></div>
		</div>
		
		<div role="tabpanel" class="tab-pane fade in manageProducts" id="manageProducts">

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

<!--                            Manage Category section Script start here
------------------------------------------------------------------------------------------------- -->


<!-- categories list row Action column _template -->
<script type="text/template" id="categoriesColumnActionTemplate">
   <a href ui-sref="categories.edit({catID:'<%- __tData.id %>'})" title="<?= __('Edit') ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a> <a title="<?= __('Delete') ?>" ng-click="categoryCtrl.deleteDialog('<%- __tData.id %>', '<%- _.escape(__tData.name) %>');" class="btn btn-danger btn-xs delete-sw"><i class="fa fa-trash-o fa-lg"></i> <?= __('Delete')  ?></a>
</script>
<!-- /categories list row Action column _template -->

<!-- categories list row products column   _template -->
<script type="text/template" id="categoriesColumnActionAddProduct">
  	<a ui-sref="category_product_add({categoryID : <%- __tData.id %>})" title="<?= __('Add Product') ?>" class="btn btn-default btn-xs"><i class="fa fa-plus"></i> <?= __('Add Product') ?></a>

  	<a class="btn btn-default btn-xs" title="<?= __('This category contain __totalCategoryProductCount__ out of __currentCategoryProductsCount__', ['__totalCategoryProductCount__' => '<%= __tData.totalCategoryProductCount %>', '__currentCategoryProductsCount__' => '<%- __tData.currentCategoryProductsCount %>']) ?>"  ui-sref="products({mCategoryID:'<%- __tData.id %>'})">
  		<span class="badge custom-badge"> <%- __tData.currentCategoryProductsCount %> </span> / 
  		<span class="badge custom-badge"> <%- __tData.totalCategoryProductCount %> </span> <?= __('Products')  ?>
   </a>
</script>

<!-- categories list row Subcategories column  _template -->
<script type="text/template" id="categoriesColumnActionAddcategory">

  <a href ng-click="categoryCtrl.add(<%- __tData.id %>)" title="<?= __('Add Subcategory ') ?>" class="btn btn-default btn-xs"><i class="fa fa-plus"></i> <?= __('Add category') ?></a> 

  <a ui-sref="categories({mCategoryID:'<%- __tData.id %>'})"
  	title="<?= __('This category contain __activeChildCount__ active and __activeChildInactive__ inactive subcategories', [ '__activeChildCount__' => '<%= __tData.childCount.active %>','__activeChildInactive__' => '<%= __tData.childCount.inActive %>']) ?>" 
  	class="btn btn-default btn-xs">
  	<span class="badge custom-badge lw-active"><%- __tData.childCount.active %></span> 
  	| <span class="lw-inactive badge custom-badge" ><%- __tData.childCount.inActive %> </i></span> <?= __('Subcategories') ?></a>

</script>
 
<!-- categories list row name column  _template -->
<script type="text/template" id="categoriesColumnActionSubcategories">
  <a ui-sref="categories({mCategoryID:'<%- __tData.id %>'})" title="<?= __('View') ?>" class="tch-name word-wrap"><%- __tData.name %></a>
</script>
<!-- categories list row name column  _template -->

<!-- categories list row status column  _template -->
<script type="text/template" id="categoriesColumnStatus">
 	<% if (__tData.status === 1) { %> 
        <span title="<?= __( 'Active' ) ?>"><i class="fa fa-eye"></i></span>
   <% } else { %>
    <span title="<?= __( 'Inactive' ) ?>"><i class="fa fa-eye-slash"></i></span>
   <% } %>
</script>
<!-- categories list row status column  _template -->

<!-- product categories -->
<script type="text/_template" id="categoryDeleteAlertTemplate">
    <div class="text-center">

    	<%= __ngSupport.getText(
	        __globals.getJSString('category_delete_note_text'), {
	            ':name'    : unescape(__tData.name)
	        }
    	)%>
    <h3 class="disabledText"><%= __tData.captchaImg %></h3>
  	</div>
  	<div class="form-group custom-form-control">
  		<label for="exampleInputEmail1"></label>
  		<div class="input-group">
  		<span class="input-group-addon"><%= __globals.getJSString('category_delete_confirm_note') %></span>
  		<input type="text" placeholder="<%= __globals.getJSString('category_delete_input_placeholder_text') %>" class="form-control deleteCate"></div><div class="custom-error">
  		</div>
  	</div>
</script>
<!-- /product categories -->

<!--                             Manage Category section Script end here
------------------------------------------------------------------------------------------------- -->