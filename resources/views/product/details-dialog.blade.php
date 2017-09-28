<div ng-controller="ProductDetailsDialogController as productDetailsDialogCtrl"  class="lw-dialog">
	<!--   main heading  -->
	<div class="lw-section-heading-block" ng-if="productDetailsDialogCtrl.pageStatus">
        <h3 class="lw-header" ng-bind="::productDetailsDialogCtrl.productDetails.name"></h3>
    </div>
	<!--  /main heading  -->
	
	<!--  loader  -->
	<div ng-hide="productDetailsDialogCtrl.pageStatus" class="text-center">
		<div class="loader"><?=  __('Loading...')  ?></div>
	</div>
	<!--  / loader  -->

	<div ng-if="productDetailsDialogCtrl.pageStatus">
		<!--  dialog body section  -->
		<div class="row">

			<!--  to show the product image  -->
		  	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 lw-quick-view-thumbnail-holder">
				<a href ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" title="<?= __('Product Details') ?>" class=""> <img ng-src="[[ productDetailsDialogCtrl.productDetails.productImage ]]" class="img-responsive lw-quick-view-thumbnail" alt=""></a>
		  	</div>
		  	<!--  /to show the product image  -->

			<!--  product details section like price, qty ectc  -->
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
				<!--  To show product ID  -->
				<div><?= __('ID') ?> : [[ productDetailsDialogCtrl.productDetails.product_id ]] </div>		
				<!--  / To show product ID  -->

				<!--  product categories section  -->
				<div>
	        		<?=  __('Available in these categories')  ?> :
					<!--   categories section   -->
					<span ng-repeat="category in productDetailsDialogCtrl.productDetails.productCategories track by $index">
	                <a href="[[ category.categoryUrl ]]">
					  [[ category.name ]] </a><span ng-show=" ! $last ">,</span>
	                
					</span>
					<!--  / categories section  -->
				</div>
				<hr>
				<!--  /product categories section  -->

				<!--  Form for add to cart section -->
				<div>
					<!--  Form for add to cart section -->
					<form 
						class="lw-form lw-ng-form" 
						name="productDetailsDialogCtrl.[[ productDetailsDialogCtrl.ngFormName ]]" 
						novalidate>
						
						<!--   Show the product options -->
					    <div ng-repeat="option in productDetailsDialogCtrl.productDetails.option track by $index" ng-switch="option.optionValueExist">
					        <span  ng-switch-when="true"><lw-form-field field-for="options[option.name]" label="[[option.optionName]] : ">
								<select 
					                ng-init="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name] = option.option_values[0]"  

					                ng-model="productDetailsDialogCtrl.productData.options[productDetailsDialogCtrl.productDetails.id][option.name]" 

					                class="form-control" 

					                name="options[option.name]" 
					                ng-options='(value.addon_price != 0 ? (value.name +" (+"+value.addon_price_format+")") : value.name) for value in option.option_values'
					                ng-change="productDetailsDialogCtrl.updateCartItem(productDetailsDialogCtrl.productDetails.id, true)"

					            ></select>
							</lw-form-field>
							</span>
					    </div>
					    <!--  / show the product options -->

					    <!--  price details table  -->
					    <div ng-switch="productDetailsDialogCtrl.optionLength" >
					    	<table  ng-switch-when="true" class='table table-bordered table-striped table-hover table-rounded'>
						        <tbody>
						            <tr>
						                <td><strong><?=  __('Base Price')  ?> </strong></td>
						                <td class="text-right">
						                   <span ng-bind="productDetailsDialogCtrl.productDetails.priceDetails.base_price"></span>
						                    <!--  To show old price  -->
						                	<span ng-if="productDetailsDialogCtrl.productDetails.old_price">
						                		<small><strike class="lw-price">[[productDetailsDialogCtrl.productDetails.oldPrice]]   <?= getStoreSettings('currency') ?> </strike> </small>
						                	</span>
						                	<!--  To show old price   -->
						                </td>
						            </tr>
						            <tr ng-repeat="productOption in productDetailsDialogCtrl.productDetails.priceDetails.option">
						                <td>[[ productOption.optionName ]] <em ng-bind="productOption.name"></em> </td>
						                <td class="text-right">
				                			<sapn ng-show="productOption.addon_price != 0">+ [[productOption.addon_price_format]]</sapn>
					                    	<sapn ng-show="productOption.addon_price == 0"> - </sapn>
							            </td>
						            </tr>
						        </tbody>
						    </table>
					    </div>
					    
					    <!--  / price details table  -->

					    <!--  Price  -->
					    <div><h3 class="lw-effective-price">
					        <small title="<?= __( 'Price based on options selections' ) ?>"><?= __( 'Price :' ) ?></small>
					        <span ng-bind="productDetailsDialogCtrl.productDetails.basePriceWithAddonPrice"></span> 
					        
					        <?= getStoreSettings('currency') ?>

					        <!--  To show old price  -->
					    	<span ng-if="productDetailsDialogCtrl.productDetails.old_price">
					    		<small ng-if="productDetailsDialogCtrl.optionLength == false"><strike class="lw-price">[[productDetailsDialogCtrl.productDetails.oldPrice]]   <?= getStoreSettings('currency') ?> </strike> </small>
					    	</span>
					    	<!--  To show old price   -->
					    </h3></div>
					    <!--  /Price  -->


					    <!--  show quantity field if product available else show out of stock   -->
					    <div ng-switch="productDetailsDialogCtrl.productDetails.out_of_stock">
							
							<!--  Quantity show when the product is in stock -->
							<div ng-switch-when="0">

					            <lw-form-field field-for="quantity" label="<?= __( 'Quantity' ) ?>">
					                
					                <div class="input-group">
					                   <!--  decrement of qunatity btn  -->
					                    <span class="input-group-btn ">
					                        <button title="<?=  __('Decrement')  ?>" type="button" class="btn btn-default btn-number lw-vxs-hidden" ng-click="productDetailsDialogCtrl.getQtyAction(false, productDetailsDialogCtrl.productData.quantity)">
					                            <i class="glyphicon glyphicon-minus"></i>
					                        </button>
					                    </span>
					                    <!-- / decrement of qunatity btn  -->

					                    <input style="text-align:center" type="number" 
					                      class="lw-form-field form-control"
					                      name="quantity"
					                      ng-required="true"
					                      min="1" 
					                      max="99999"
					                      ng-model="productDetailsDialogCtrl.productData.quantity" />
					                    
					                    <!--  show add & Update cart btn  -->
					                    <span class="input-group-btn" ng-switch="productDetailsDialogCtrl.productData.isCartExist">

					                        <!--  increment of quantity btn  -->
					                        <button title="<?=  __('Increment')  ?>" type="button" ng-click="productDetailsDialogCtrl.getQtyAction(true, productDetailsDialogCtrl.productData.quantity)" class="btn btn-default btn-number lw-vxs-hidden">
					                          <i class="glyphicon glyphicon-plus"></i>
					                        </button>
					                        <!-- / increment of quantity btn  -->
					                        <!--  Add cart btn  -->
					                        <button 
					                            ng-switch-when="false" 
					                            class="btn btn-primary lw-btn-process" 
					                            title="<?=  __('Add to Cart')  ?>" 
					                            type="submit" ng-click="productDetailsDialogCtrl.addToCart()">
					                            <i class="fa fa-cart-plus"></i> <?=  __("Add to Cart")  ?>
					                        </button>
					                        <!--  / Add cart btn  -->

					                        <!--  Update cart btn  -->
					                        <button ng-switch-when="true"  
					                            title="<?= __('Update Cart') ?>" 
					                            type="submit" 
					                            class="btn btn-primary lw-btn-process" 
					                            ng-click="productDetailsDialogCtrl.addToCart()"> 
					                            <i class="fa fa-cart-plus"></i> <?=  __("Update Cart")  ?>
					                            <span></span>
					                        </button>
					                        <!--  Update cart btn   -->
					                    </span>                                    
					                    <!--  /show add & Update cart btn  -->
					                </div>
					                
					            </lw-form-field>
							</div>
					        <!--  /Quantity show when the product is in stock  -->

					      	<!--  show out of stock alert msg  -->
					        <div ng-switch-when="1" class="alert alert-warning">
					            <?=  __('Out of Stock')  ?>
					        </div>
							<!--  / show out of stock alert box  -->
					        
					    </div>
					    <!-- / show ... else show out of stock   -->
					</form>
					<!--  Form for add to cart section -->
				</div>
				<!-- / Form for add to cart section -->
			</div>
			<!-- / product details section like price, qty etc  -->

		</div>
		<!-- / dialog body section  -->


		<!-- dialog action btns  -->
		<div>

			<div class="lw-section-heading-block"></div>
			
			<!--  show product details btn  -->
			<a href 
				ng-href="[[productDetailsDialogCtrl.productDetails.detailURL+productDetailsDialogCtrl.pageType]]" 
				title="<?= __('Product Details') ?>" 
				class="btn btn-default btn-sm lw-show-process-action lw-xs-dblock">
				<i class="glyphicon glyphicon-search icon-white"></i> <?=  __('Product Details')  ?>
			</a>
			<!-- /show product details btn  -->

			{{-- Disabled btn when any cart item is invalid then display btn conditionally --}}
	    	<span ng-switch="productDetailsDialogCtrl.productData.isCartExist">

	        	<!--  Go to Shopping Cart btn  -->		
				<a ng-switch-when="true" title="<?=  __('Go to Shopping Cart')  ?>" class="btn btn-warning btn-sm pull-right lw-show-process-action lw-xs-dblock" href="<?=  route('cart.view')  ?>"><i class="glyphicon glyphicon-shopping-cart icon-white"></i> <?=  __('Go to Shopping Cart')  ?></a>
				<!-- / Go to Shopping Cart btn  -->	


	        	<!--  Go to Shopping Cart btn  -->
				<a ng-switch-when="false" title="<?=  __('Go to Shopping Cart')  ?>" class="btn btn-default btn-sm pull-right lw-show-process-action lw-xs-dblock" href="<?=  route('cart.view')  ?>"><i class="glyphicon glyphicon-shopping-cart icon-white"></i> <?=  __('Go to Shopping Cart')  ?></a>
				<!-- / Go to Shopping Cart btn  -->	
			</span>
			{{--/ Disabled btn ....... conditionally  --}}

		</div>
		<!-- / dialog action btns -->
		
	</div>

</div>