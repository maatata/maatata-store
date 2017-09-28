@if ((isCurrentRoute('order.summary.view') == false 
	or getStoreSettings('hide_sidebar_on_order_page') == 0))
<div ng-controller="OrderSummaryController as CartOrderCtrl">
@else 
<div ng-controller="OrderSummaryController as CartOrderCtrl" class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 ">
@endif

	{{--  heading section --}}
	<div class="lw-section-heading-block">
        {{-- main heading --}}
        <h3 class="lw-section-heading">
        	@section('page-title',  __('Order Summary - Complete your order') ) <?=  __('Order Summary -')  ?> <small><?=  __(' Complete your order')  ?></small>
        </h3>
        {{-- /main heading --}}
    </div>
    {{-- / heading section --}}

    {{-- main container --}}
    <div ng-if="CartOrderCtrl.pageStatus == true">

		<form class="lw-form lw-ng-form" 
		name="CartOrderCtrl.[[ CartOrderCtrl.ngFormName ]]" 
		novalidate>

			<div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><strong><?=  __('Products in Cart')  ?></strong></h4>
                    </div>

				{{-- product detail, price and quantity --}}
					<div class="table-responsive lw-shopping-cart">
						<table class="table table-bordered lw-custom-table" cellspacing="0" width="100%">
							<thead class="page-header">
								<tr>
	                            <th class="text-center"><?= __('Thumbnail') ?></th>
	                            <th><?= __('Item Description') ?></th>
	                            <th class="text-right"><?= __('Price') ?></th>
	                            <th class="text-center"><?= __('Qty') ?></th>
	                            <th class="text-right"><?= __('Subtotal') ?></th>
	                        </tr>
							</thead>
							<tbody>
								{{-- products image, option and price come in this section --}}
								<tr ng-show="CartOrderCtrl.orderSupportData.cartItems" ng-repeat="item in CartOrderCtrl.orderSupportData.cartItems track by $index" id="rowid_[[item.rowid]]">
	                                <td class="lw-product-thumbnail-column">
	                                    <a class="lw-product-thumbnail" href ng-href="[[item.productDetailURL]]"><img ng-src="[[ item.thumbnail_url ]]"></a>
	                                </td>
						          	<td>
						          		{{-- product name and price --}}
										<strong> <span ng-bind="item.name"> </span> : </strong> (<span> + [[item.formated_price]]</span>) <br>
										{{-- product name and price --}}

										{{-- product option name and value --}}
						          		<div ng-repeat="(key, option) in item.options">
			            					<span ng-show="option.addonPrice != 0">
		                                        <strong>[[option.optionName]] : </strong> [[option.valueName]] ( +[[option.formated_addon_price]])<br>
		                                    </span>
		                                    <span ng-show="option.addonPrice == 0">
		                                        <strong>[[option.optionName]] : </strong> [[option.valueName]]<br>
		                                    </span>
				          				</div>
				          				{{-- product option name and value --}}

				          				{{-- if product is active or invalid then show this message --}}
				          				<div class="lw-error-msg-order">
		                                    <span class="lw-order-product-stock">
		                                        <span ng-if="item.ERROR_MSG" ng-bind="item.ERROR_MSG"></span>
		                                    </span>
		                                </div>
										{{-- /if product is invalid then show this message --}}
						          	</td>
	                                <td align="right" ng-bind="item.new_price"></td>
									<td class="text-center" ng-bind="item.qty"></td>
									<td align="right" ng-bind="item.new_subTotal"></td>
								</tr>
								{{-- /products image, option and price come in this section --}}

								<tr ng-show="CartOrderCtrl.orderSupportData.cartItems.length == 0">
									<td colspan="4">
						          		<div class="alert alert-info"><?=  __("Your cart is empty.")  ?> <a href="<?=  route('home.page')  ?>" class="btn-sm btn-default lw-btn lw-show-process-action" title="<?= __('Do some more Shopping!!') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __('Continue Shopping') ?></a></div>
						          	</td>
								</tr>
								
								{{-- cart total --}}					
								<tr>
									<td colspan="4">
						          		<strong><?=  __('Cart Total')  ?></strong>
						          	</td>	
						          	<td align="right">
						          	<strong ng-bind="CartOrderCtrl.orderSupportData.formatedCartTotalPrice">
						          	</strong>
						          	<strong ng-bind="CartOrderCtrl.orderSupportData.currency"></strong>
						          	</td>
						        </tr>
						        {{-- /cart total --}}

						        {{-- error message --}}
								<tr ng-if="CartOrderCtrl.orderSupportData.cartItemStatus">
									<td colspan="4">
										<div class="alert alert-danger"><?=  __('There are no items in the cart.')  ?></div>
									</td>
								</tr>

		                        <tr ng-show="CartOrderCtrl.orderSupportData.itemIsInvalid">
		                            <td colspan="6">
		                                <div class="alert alert-danger">
		                                    <?=  __("We're sorry. The highlighted item(s) in your Shopping Cart are currently unavailable. Please remove the item(s) to proceed.")  ?>
		                                    <strong class="pull-right"><a href="" title="<?= __('Remove all this invalid products.') ?>" ng-click="CartOrderCtrl.removeAllInvlidCartItem(true)"><?= __('Remove') ?></a></strong>
		                                </div>
		                            </td>
		                        </tr>
								{{-- /error message --}}
							</tbody>
						</table>
				{{-- /product detail, price and quantity --}}
				</div>
			</div>

			<div>
				{{-- include coupon section --}}
				<fieldset class="lw-fieldset-2">

					<legend>
						<?=  __('Coupon')  ?> 
					</legend>

				    @include('coupon.apply')

				</fieldset>
				{{-- /coupon section --}}

			</div>
			
			{{-- after adding coupon price to display subtotal table --}}
			<div>
				<fieldset class="lw-fieldset-2"  ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true">
					<legend>
						<?=  __('Sub Total')  ?> 
					</legend>

					<div class="table-responsive">

						<table class="table table-bordered lw-custom-table" cellspacing="0" width="100%">

							<tbody class="ng-scope">

								<tr>
									<td colspan="3">
						          		<h4><strong><?=  __('Sub Total')  ?></strong></h4>
						          	</td>	
						          	<td align="right">
						          	+ <strong ng-bind="CartOrderCtrl.orderSupportData.subtotalPrice"></strong>
						          	<strong ng-bind="CartOrderCtrl.orderSupportData.total.currency"></strong>
						          	</td>
						        </tr>

							</tbody>

						</table>

					</div>

				</fieldset>

			</div>
			{{--/ after adding coupon price to display subtotal table --}}

			{{-- To show the shipping & billing address of login user --}}
			<div>
				<fieldset class="lw-fieldset-2">

					<legend>
						<?=  __('Shipping / Billing Address')  ?> 
					</legend>
				
					<div>
						{{-- Name --}}
				        <lw-form-field field-for="name" label="<?=  __('Full Name')  ?>"> 
			                <input type="name" 
			                    class="lw-form-field form-control"
			                    name="name"
			                    ng-required="true"
			                    ng-model="CartOrderCtrl.orderData.fullName"
			                />
			            </lw-form-field>
			            {{-- Name --}}
					</div>

					
					{{-- Start Shipping address panel --}}
					<div class="col-lg-12">
						
						<div class="panel panel-default">

								<div class="panel-heading">

									<strong><?= __('Shipping Address') ?> :</strong>
									
									<div class="lw-form-inline-elements">
										{{--  same address  --}}
										<lw-form-checkbox-field field-for="sameAddress" label="<?= __('Use this address as billing address') ?>">
								            <input type="checkbox" 
								                class="lw-form-field"
								                name="sameAddress" 
								                ng-change="CartOrderCtrl.sameAsAddress(CartOrderCtrl.orderSupportData.sameAddress, CartOrderCtrl.orderSupportData.shippingAddress)"
								                ng-model="CartOrderCtrl.orderSupportData.sameAddress"/>
							        	</lw-form-checkbox-field>
										{{--  /same address  --}}
									</div>
								</div>
						
								<div class="panel-body">

									<!-- shipping id -->
									<lw-form-field field-for="addressID" label=""> 
										<input type="hidden" 
											class="lw-form-field form-control"
											name="addressID"
											ng-model="CartOrderCtrl.orderData.addressID" 
										/>
									</lw-form-field>
									<!-- /shipping id -->

									{{-- shipping address --}}
									<div ng-show="CartOrderCtrl.orderSupportData.shippingAddress">

										<address ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1" class="lw-address" id="lw-shipping">
											<strong ng-if="CartOrderCtrl.orderSupportData.shippingAddress.type" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.type"></strong><br>
											<span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.address_line_1"></span><br>
											<span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.address_line_2" ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.address_line_2"></span><br>
											<span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.city"></span>
											<span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.state"></span>
											<span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.country"></span><br>
											<span ng-if="CartOrderCtrl.orderSupportData.shippingAddress.pin_code"><?= __('Pincode') ?> : <span ng-bind="CartOrderCtrl.orderSupportData.shippingAddress.pin_code"></span></span>
										</address>
									</div>
									{{-- shipping address --}}

									{{-- message for shipping address--}}
									<div class="alert alert-danger" 
										ng-show="!CartOrderCtrl.orderSupportData.shippingAddress">
										<?= __('Address is required') ?>
									</div>
									{{-- /message for shipping address--}}
									
									{{-- change address button--}}
									<a  href="" 
										ng-show="CartOrderCtrl.orderSupportData.shippingAddress"
										class="btn btn-default btn-sm pull-right" 
										ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'shipping')" title="<?= __('Change Address') ?>">
										<i class="fa fa-pencil-square-o"></i> <?= __('Change Address') ?>
									</a>
									{{-- /change address button--}}

									{{-- select address button--}}
									<a href="" 
										ng-hide="CartOrderCtrl.orderSupportData.shippingAddress.id" 
										class="btn btn-default btn-sm pull-right" 
										ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'shipping')" 
										title="<?= __('Select Address') ?>">
											<?= __('Select Address') ?>
									</a>
									{{-- select address button--}}
								</div>
							{{-- End Shipping address panel --}}
						</div>
						
					</div>

					{{-- Billing address--}}
					<div class="col-lg-12" ng-show="CartOrderCtrl.orderSupportData.sameAddress == false">

						<div class="panel panel-default">

							<div class="panel-heading">

								<strong><?= __('Billing Address') ?> :</strong>
								
							</div>

							<div class="panel-body">

								<!-- Billing id -->
								<lw-form-field field-for="addressID1" label=""> 
									<input type="hidden" 
										class="lw-form-field form-control"
										name="addressID1"
										ng-model="CartOrderCtrl.orderData.addressID1" 
									/>
								</lw-form-field>
								<!-- /Billing id -->

								<address class="lw-address"  ng-if="CartOrderCtrl.orderSupportData.billingAddress"  id="lw-billing">
									<strong ng-if="CartOrderCtrl.orderSupportData.billingAddress.type" 
										ng-bind="CartOrderCtrl.orderSupportData.billingAddress.type">
									</strong>
									<br>
									<span 
										ng-if="CartOrderCtrl.orderSupportData.billingAddress.address_line_1" ng-bind="CartOrderCtrl.orderSupportData.billingAddress.address_line_1">
									</span><br>

									<span ng-if="CartOrderCtrl.orderSupportData.billingAddress.address_line_2" ng-bind="CartOrderCtrl.orderSupportData.billingAddress.address_line_2"></span>
										<br>
									<span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.city"></span>
									<span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.state"></span>
									<span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.country"></span><br>
									<span ng-if="CartOrderCtrl.orderSupportData.billingAddress.pin_code"><?= __('Pincode') ?> : <span ng-bind="CartOrderCtrl.orderSupportData.billingAddress.pin_code"></span></span>
								</address>
								
								{{-- message for billing address required--}}
								<div class="alert alert-danger" 
									ng-show="CartOrderCtrl.orderSupportData.billingAddress == ''">
									<?= __('Address is required') ?>
								</div>
								{{-- /message for billing address required--}}

								{{-- change address button--}}
								<a href="" 
									ng-show="CartOrderCtrl.orderSupportData.billingAddress && !CartOrderCtrl.orderSupportData.sameAddress"
									class="btn btn-default btn-sm pull-right" 
									ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'billing')" title="<?= __('Change Address') ?>">
									<i class="fa fa-pencil-square-o"></i> <?= __('Change Address') ?>
								</a>
								{{-- /change address button--}}
								
								{{-- select address button --}}
								<a ng-show="CartOrderCtrl.orderSupportData.sameAddress == false &&!CartOrderCtrl.orderSupportData.billingAddress" 
									href="" 
								 	class="btn btn-default btn-sm pull-right" 
								 	ng-click="CartOrderCtrl.openAddressListDialog(CartOrderCtrl.orderSupportData.sameAddress, 'billing')" 
								 	title="<?= __('Select Address') ?>"><?= __('Select Address') ?></a>
								{{-- /select address button --}}
							</div>
						</div>
					</div>
					{{-- /billing address--}}

				</fieldset>
			</div>
			{{-- /To show the shipping & billing address of login user --}}

			{{-- To show the tax related information --}}
			<div>
				<fieldset class="lw-fieldset-2" ng-if="CartOrderCtrl.orderSupportData.taxses != ''">
					<legend>
						<?=  __('Tax')  ?> 
					</legend>
					<div class="table-responsive lw-order-summery-table-container">
						<table class="table" cellspacing="0" width="100%">
							<tbody class="ng-scope">

						        {{-- tax section --}} 
						        <tr ng-repeat="tax in CartOrderCtrl.orderSupportData.taxses">
									<td colspan="3">
						          		<span ng-bind="tax.label"></span><br>
						          		<span ng-bind="tax.notes"></span>
						          	</td>	
						          	<td class="lw-amount-td" ng-switch="tax.type">
										{{-- Tax type flat  --}}
						          		<span ng-switch-when="1">
						          			+ <span ng-bind="tax.formatedTax"></span>
						          		</span>
										{{--/ Tax type flat  --}}

										{{-- Tax type percent  --}}
						          		<span ng-switch-when="2">
						          			+ <span ng-bind="tax.formatedTax"></span>
						          		</span>
										{{--/ Tax type percent  --}}
						          	</td>
						        </tr>
						        {{--/ tax section --}}

							</tbody>{{-- end ngIf: CartCtrl.cartDataStatus === true --}}
							{{-- ngIf: CartCtrl.cartDataStatus === false --}}
						</table>
					</div>
				</fieldset>
			</div>
			{{-- To show the tax related information --}}

			{{-- shipping and total payable amount section --}}
			<div ng-show="CartOrderCtrl.orderSupportData.shipping">
				<fieldset class="lw-fieldset-2">
					<legend>
						<?=  __('Shipping')  ?> 
					</legend>

					<div class="table-responsive lw-order-summery-table-container">
						<table class="table lw-order-summery-table" cellspacing="0" width="100%">
							<tbody>
							{{-- shipping section --}}
						        <tr ng-if="CartOrderCtrl.orderSupportData.shipping">

									<td colspan="3">
						          		<div>
					          				<span ng-bind="CartOrderCtrl.orderSupportData.shipping.notes"></span><br>
									    </div>
			      					</td>	

						          	<td class="lw-amount-td" ng-switch="CartOrderCtrl.orderSupportData.shipping.type">
										{{-- shipping type flat and amount null  --}}
					          			<div ng-switch-when="1">

					          				<span ng-show="CartOrderCtrl.orderSupportData.shipping.shippingAmt == 0"><?=  __('Free')  ?></span>

					          				<span 
					          					ng-show="CartOrderCtrl.orderSupportData.shipping.shippingAmt != 0">
					          					+ <span 
					          						ng-bind="CartOrderCtrl.orderSupportData.shipping.formattedShippingAmt">
					          						</span>
					          				</span>
										</div>
										{{--/ shipping type flat and amount null  --}}

										{{-- shipping type percent  --}}
						          		<div ng-switch-when="2">
											+ <span ng-bind="CartOrderCtrl.orderSupportData.shipping.formattedShippingAmt"></span>
										</div>
										{{--/ shipping type percent  --}}

										{{-- shipping type free  --}}
					          			<div ng-switch-when="3">
											<?=  __('Free')  ?>
										</div>
										{{--/ shipping type free  --}}

										{{-- shipping type not shiable  --}}
					          			<div ng-switch-when="4" >
											<span class="lw-danger"><?=  __('Sorry shipping not available in your country.')  ?></span>
										</div>
										{{--/ shipping type not shiable  --}}
										
						          	</td>
						        </tr>
						        {{--/ shipping section --}}
							</tbody>
							{{-- /tbody --}}
						</table>
						{{-- /table--}}
						
					</div>	

				</fieldset>
			</div>
			{{-- shipping and total payable amount section --}}

			{{-- total payable order amount table--}}
			<div class="table-responsive">
				
                <table class="table lw-order-summery-table-container" cellspacing="0" width="100%">   
                    {{-- /tbody --}}
                    <tbody class="ng-scope">
                        {{--/ total payable amount --}}
                        <tr>
                            <td colspan="3">
                                <h3><strong><?=  __('Total Payable Amount')  ?></strong></h3>
                            </td>   
                            <td align="right">
                                <h3><strong ng-bind="CartOrderCtrl.orderSupportData.totalPayableAmountFormated"></strong>
                                <strong ng-bind="CartOrderCtrl.orderSupportData.currency"></strong></h3>
                            </td>
                        </tr>
                        {{--/ total payable amount --}}
                    </tbody>
                    {{-- /tbody --}}
                </table>
                
			</div>
			{{-- /total payable order amount table--}}

		
			{{-- radio button for order payment method --}}
            <div class="panel panel-default lw-checkout-methods">
                <div class="panel-heading">
                    <h4><strong><?=  __('Checkout')  ?>  - <small><?=  __(' Please choose your payment method')  ?></small></strong></h4>
                </div>
				<div class="panel-body">
                    <lw-form-radio-field field-for="checkout_method" label="<?= __( 'Checkout Method' ) ?>">

                        <span>
                           
    	                    <label ng-repeat="method in CartOrderCtrl.orderSupportData.checkoutMethod" class="radio-inline"> 
								
								<input ng-model="CartOrderCtrl.orderData.checkout_method" type="radio" name="checkout_method" ng-value="[[ method ]]" ng-required="true">

    	                    	{{-- paypal --}}
    	                        <span ng-show="method == 1">
    	                           <img alt="<?= __('Checkout with PayPal') ?>" src="<?= url('resources/assets/imgs/paypal.jpg') ?>">
    	                        </span>
                            	{{-- paypal --}}

                            	{{-- check payment --}}
                        		<span ng-show="method == 2">
	    	                    	<img title="<?= __('Bank Check') ?>" alt="<?= __('Bank Check') ?>" src="<?= url('resources/assets/imgs/cheque.png') ?>"> 
    	                    	</span>
                            	{{-- /check payment --}}

                            	{{-- bank payment --}}
	                        	<span ng-show="method == 3">
	    	                     	<img title="<?= __('Bank Transfer') ?>" alt="<?= __('Bank Transfer') ?>" src="<?= url('resources/assets/imgs/bank-transfer.png') ?>"> 
	    	                    </span>
	        					{{-- /bank payment --}}


	        					{{-- cod payment --}}
	                        	<span ng-show="method == 4">
	    	                     	<img title="<?= __('COD - Cash on Delivery') ?>" alt="<?= __('COD - Cash on Delivery') ?>" src="<?= url('resources/assets/imgs/cod.png') ?>"> 
	    	                    </span>
	                            {{-- /cod payment --}}

	                            {{-- Other --}}   
	    	                    <span ng-show="method == 5">
	    	                    	<img title="<?= __('Other Payment Method - You can submit this order without payment for Now, Admin will contact you for payment.') ?>" alt="<?= __('Other Payment Method') ?>" src="<?= url('resources/assets/imgs/other.png') ?>"> 
	    	                    </span>
	                            {{-- Other --}}


    	                    </label>
                           
                        </span>
                    </lw-form-radio-field>

    				<div>
    					{{-- check payment discription --}}
    					<div ng-if="CartOrderCtrl.orderData.checkout_method == 2">
    						<fieldset class="lw-fieldset-2">
    							<legend>
    								<?=  __('Check Payment Discription')  ?> 
    							</legend>
    							<span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.checkText"></span>
    						</fieldset>
    					</div>
    					{{-- check payment discription --}}

    					{{-- bank payment discription --}}
    					<div ng-if="CartOrderCtrl.orderData.checkout_method == 3">
    						<fieldset class="lw-fieldset-2">
    							<legend>
    								<?=  __('Bank Payment Discription')  ?> 
    							</legend>
    							<span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.bankText"></span>
    						</fieldset>
    					</div>
    					{{-- bank payment discription --}}

    					{{-- cod payment discription --}}
    					<div ng-if="CartOrderCtrl.orderData.checkout_method == 4">
    						<fieldset class="lw-fieldset-2">
    							<legend>
    								<?=  __('COD Payment Discription')  ?> 
    							</legend>
    							<span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.codText"></span>
    						</fieldset>
    					</div>
    					{{-- cod payment discription --}}

    					{{-- Other payment discription --}}
    					<div ng-if="CartOrderCtrl.orderData.checkout_method == 5">
    						<fieldset class="lw-fieldset-2">
    							<legend>
    								<?=  __('Other Payment Discription')  ?> 
    							</legend>
    							<span ng-bind-html="CartOrderCtrl.orderSupportData.checkoutMethodInfo.otherText"></span>
    						</fieldset>
    					</div>
    					{{-- Other payment discription --}}
    				</div>
			</div>
			{{-- radio button for order payment method --}}
            </div>

			{{-- order action buttons --}}
			
			<div class="lw-form-actions">
				{{-- back to shopping cart button --}}
				<a  href="<?= e( route('cart.view') ) ?>" class="btn btn-default lw-btn lw-show-process-action lw-redirect-action" title="<?= __('Back To Shopping Cart') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __('Back To Shopping Cart') ?></a>
				{{-- back to shopping cart button --}}
				
				{{-- process order button --}}
				<button ng-disabled="!CartOrderCtrl.disabledStatus" type="submit" class="btn btn-success pull-right lw-btn btn-lg lw-btn-process" title="<?= __('Submit Order') ?>" ng-click="CartOrderCtrl.orderSubmit()" ><i class="fa fa-check-square-o"></i> <?= __('Submit Order') ?> <span></span></button>
				{{-- /process order button --}}

			</div>
			{{-- order action buttons --}}
		</form>

	</div>
	{{--/ main containder --}}

</div>