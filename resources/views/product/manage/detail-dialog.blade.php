<div class="lw-dialog" ng-controller="ProductDetailDialogController as productDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Product Information' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	<!--  if product is invalid then show this message  -->
	<div ng-if="productDetailCtrl.productData.isActiveCategory == '' || productDetailCtrl.productData.product.active == false">
		<div class="alert alert-info" role="alert">
	    	<?=  __('This product or its category is deactive & will not display in public until you change status Deactive to Active')  ?>
	    </div>
    </div>
	<!--  if product is invalid then show this message  -->

	<div class="panel panel-default">
	  <!--  Table  -->
	  <table class="table table-bordered" border="1">
	    	<tr>
	    		<!--  thumbnail  --> 
	    		<th><?=  __('Thumbnail')  ?></th>
	    		<td><img ng-src="[[productDetailCtrl.productData.product.thumbnail]]" class="lw-image-thumbnail"></a> </td>
	    		<!-- / thumbnail  --> 
	    	</tr>
	    	<tr>
	    		<!--  product id  --> 
	    		<th><?=  __('Product ID')  ?></th>
	    		<td ng-bind="productDetailCtrl.productData.product.product_id"></td>
	    		<!-- / product id  --> 
	    	</tr>
	    	<tr>
	    		<!--  product name  --> 
	    		<th><?=  __('Name')  ?></th>
	    		<td ng-bind="productDetailCtrl.productData.product.name"></td>
	    		<!-- / product name  --> 
	    	</tr>
	    	<tr ng-if="productDetailCtrl.productData.product.old_price != null">
	    		<!--  old price  -->
	    		<th><?=  __('Old Price')  ?></th>
	    		<td>
	    			<span ng-bind="productDetailCtrl.productData.currencySymbol"></span>
	    			<span ng-bind="productDetailCtrl.productData.product.old_price"></span>
	    		</td>
	    		<!-- / old price  -->
	    	</tr> 
	    	<tr ng-if="productDetailCtrl.productData.product.old_price != null">
	    		<!--  new price  -->
	    		<th><?=  __('Price')  ?></th>
	    		<td>
	    			<span ng-bind="productDetailCtrl.productData.currencySymbol"></span>
	    			<span ng-bind="productDetailCtrl.productData.product.price"></span>
	    		</td>
	    		<!-- / new price  -->
	    	</tr>
	    	<tr>
	    		<!--  created at  --> 
	    		<th><?=  __('Created On')  ?></th>
	    		<td ng-bind="productDetailCtrl.productData.product.created_on"></td>
	    		<!-- / created at  --> 
	    	</tr>
	    	<tr>
	    		<!--  updated on  --> 
	    		<th><?=  __('Updated On')  ?></th>
	    		<td ng-bind="productDetailCtrl.productData.product.updated_on"></td>
	    		<!-- / updated on  --> 
	    	</tr>
	  </table>
	  <!-- / Table  -->  
	</div>

	<!--  product discription  -->
  	<hr>
	<div>
		<strong class="pull-left"><?=  __('Description')  ?></strong><br>
   		<span ng-bind="productDetailCtrl.productData.product.description"></span>
	</div>
	<hr>
	<!-- / product discription  -->

	  
	
	<div class="panel panel-default">
	  <!--  Table  -->
	  <table class="table table-bordered" border="1">
	    	<tr><!-- / featured  --> 
	    		<th><?=  __('Featured')  ?></th>
	    		<td>
	    			<span ng-if="productDetailCtrl.productData.product.featured == true">
	    				<i class="fa fa-check fa-1x lw-success"></i>
	    			</span>
	    			<span ng-if="productDetailCtrl.productData.product.featured == false">
	    				<i class="fa fa-times fa-1x lw-danger"></i>
	    			</span>
	    		</td>
	    	</tr><!-- / featured  --> 
	    	<tr><!-- / out of stock  --> 
	    		<th><?=  __('Out of Stock')  ?></th>
	    		<td>
	    			<span ng-if="productDetailCtrl.productData.product.out_of_stock == true">
	    				<i class="fa fa-check fa-1x lw-success"></i>
	    			</span>
	    			<span ng-if="productDetailCtrl.productData.product.out_of_stock == false">
	    				<i class="fa fa-times fa-1x lw-danger"></i>
	    			</span>
	    		</td>
	    	</tr><!-- / out of stock  --> 
	    	<tr><!--  status  --> 
	    		<th><?=  __('Status')  ?></th>
	    		<td>
	    			<span ng-if="productDetailCtrl.productData.product.active == true">
						<?=  __('Active')   ?>
					</span>
					<span ng-if="productDetailCtrl.productData.product.active == false">
						<?=  __('Deactive')   ?>
					</span>
	    		</td>
	    	</tr><!--  /status  --> 
	  </table>
	  <!-- / Table  -->  
	</div>
	
	<div class="panel panel-default">
	  <!--  categories  -->
	  <table class="table table-bordered" border="1">
	    	<tr>
	    		<th><?=  __('Categories')  ?></th>
	    	</tr>
	    	<tr>
	    		<td>
	    			<span ng-repeat="category in productDetailCtrl.productData.product.categories">
	    				<a ng-href="[[category.categoryURL]]" title="<?=  __('Go to Category Products')  ?>" target="_new" ng-bind="category.name"></a><span ng-if="$last != true">,</span>
	    			</span>
	    		   
				</td>
	    	</tr>
	  </table>
	  <!--  /categories  -->
	</div>
	
	<div class="panel panel-default" ng-if="productDetailCtrl.productData.brandData != ''">
	  <!--  brand  -->
	  <table class="table table-bordered" border="1">
	    	<tr>
	    		<th><?=  __('Brand')  ?></th>
	    		<td><a href="[[ productDetailCtrl.productData.brandData.brandURL ]]" title="<?=  __('Go to Brand Products')  ?>" target="_new" ng-bind="productDetailCtrl.productData.brandData.name"></td>
	    	</tr>
	  </table>
	  <!--  /brand  -->
	</div>
	
	
	<div class="panel panel-default" ng-if="productDetailCtrl.productData.product.option != ''">
		<div class="panel-heading"><strong><?=  __('Product Options')  ?></strong></div>
		  <!--  options  -->
		  <div class="table-responsive">
			  <table class="table table-bordered" border="1" ng-repeat="option in productDetailCtrl.productData.product.option">
				  <thead>
				    	<tr>
				    		<th ng-bind="option.name"></th>
				    	</tr>
				    	<tr>
				    		<th><?=  __('Addon Value')  ?></th>
				    		<th><?=  __('Addon Price ')  ?><em><?=  __('(if any)')  ?></em></th>
				    	</tr>
				  </thead>
				  <tbody>
				    	<tr ng-repeat="value in option.option_values">
				    		<td ng-bind="value.name"></td>
				  			<td>
				  				<span ng-bind="productDetailCtrl.productData.currencySymbol"></span>
				  				<span ng-bind="value.addon_price"></span>
				  			</td>
				    	</tr>
			   	  </tbody>
			  </table>
		  </div>
		  <!--  /options  -->
	</div>

	<div class="panel panel-default" ng-if="productDetailCtrl.productData.product.specification != ''">
	  <!--  specification  -->
	  <table class="table table-bordered" border="1">
	    	<tr>
	    		<th><?=  __('Product Specifications')  ?></th>
	    	</tr>
	    	<tr ng-repeat="specification in productDetailCtrl.productData.product.specification">
	    		<td ng-bind="specification.name"></td>
	    		<td ng-bind="specification.value"></td>
	    	</tr>
	  </table>
	  <!--  /specification  -->
	</div>
	
	<div class="panel panel-default" ng-if="productDetailCtrl.productData.relatedProducts != ''">
	  <!--  related products  -->
	  <table class="table table-bordered" border="1">
	    	<tr>
	    		<th><?=  __('Related Products')  ?></th>
	    	</tr>
	    	<tr>
	    		<td>
	    		   <ul ng-repeat="product in productDetailCtrl.productData.relatedProducts">
				     <li><a href="[[ product.relatedProductURL ]]" title="<?=  __('Go to View Details Page')  ?>" target="_new" ng-bind="product.name"></a></li>
				   </ul>
				</td>
	    	</tr>
	  </table>
	  <!--  /related products  -->
	</div>

	<!--  action button  -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="productDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!--  /action button  -->
</div>