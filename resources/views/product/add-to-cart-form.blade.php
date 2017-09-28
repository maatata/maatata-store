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
                ng-options='(value.name + " ("+value.addon_price_format+")") for value in option.option_values'
				
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
	                <td class="text-right" ng-bind="productOption.addon_price_format"></td>
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
    	<span ng-if="productDetailsDialogCtrl.oldPrice != ''">
    		<small ng-if="productDetailsDialogCtrl.optionLength == false"><strike class="lw-price">[[productDetailsDialogCtrl.oldPrice]]   <?= getStoreSettings('currency') ?> </strike> </small>
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
                        <button title="<?=  __('Decrement')  ?>" type="button" class="btn btn-default btn-number" ng-click="productDetailsDialogCtrl.getQtyAction(false, productDetailsDialogCtrl.productData.quantity)">
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

                        <!--  increment of qunatity btn  -->
                        <button title="<?=  __('Increment')  ?>" type="button" ng-click="productDetailsDialogCtrl.getQtyAction(true, productDetailsDialogCtrl.productData.quantity)" class="btn btn-default btn-number">
                          <i class="glyphicon glyphicon-plus"></i>
                        </button>
                        <!-- / increment of qunatity btn  -->
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