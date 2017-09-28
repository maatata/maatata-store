<div ng-controller="MyOrderDetailsController as orderDialogCtrl">
	
	<!-- New Order Placed success message -->
	<div ng-if="orderDialogCtrl.order.newOrderStatus">
  		<div class="alert alert-success lw-text-center">
		  <strong><?=  __("Thank you for order..!!")  ?></strong>
		</div>
  	</div>
  	<!-- /New Order Placed success message -->

    <!-- main heading -->
    @if(isAdmin())
	   	<div class="lw-section-heading-block">
	        <h3 class="lw-header pull-left"> <?=  __( 'Order Details' )  ?> @section('page-title', __('Order Details'))  (<em ng-bind="orderDialogCtrl.order.orderUID"></em>)</h3>

	        <span class="pull-right">

	        	<a class="btn btn-default btn-sm" title="<?=  __('Go To My Orders')  ?>" href="<?=  route('cart.order.list')  ?>"><?=  __("My Orders")  ?></a>

	         	<a class="btn btn-default btn-sm" title="<?=  __( 'Contact Store' ) ?>" ng-click="orderDialogCtrl.contactStoreDialog(orderDialogCtrl.order.orderUID)"><i class="fa fa-envelope" aria-hidden="true"></i> <?= __( 'Contact Store' ) ?></a>
	       
	        	<a class="btn btn-default btn-sm" title="<?=  __('Go To Manage Orders')  ?>" href="<?=  ngLink('manage.app','orders_active', [], [])  ?>"><?=  __("Manage Orders")  ?></a>
	        </span>
	    </div>

    @else

    	<div class="lw-section-heading-block">
	        <h3 class="lw-header pull-left"> @section('page-title', __('Order Details')) <?=  __( 'Order Details' )  ?> (<em ng-bind="orderDialogCtrl.order.orderUID"></em>)</h3>

	        <span class="pull-right">
	            <a class="btn btn-default btn-sm" title="<?=  __( 'Contact Store' ) ?>" ng-click="orderDialogCtrl.contactStoreDialog(orderDialogCtrl.order.orderUID)"><i class="fa fa-envelope" aria-hidden="true"></i> <?= __( 'Contact Store' ) ?></a>
	        &nbsp;<a class="btn btn-default btn-sm" title="<?=  __('Go To My Orders')  ?>" href="<?=  route('cart.order.list')  ?>"><?=  __("My Orders")  ?></a>
	        </span>
	    </div>

    @endif
   	<!-- /main heading -->

	{{-- order & payment --}}
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="row">

				{{-- order section--}}
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 pull-left">

	                 <fieldset class="lw-fieldset-2">
						<legend>
							<?=  __('Details')  ?> 
						</legend><br>

						<ul class="list-group">
						  <li class="list-group-item">
						    <span class="pull-right" ng-bind="orderDialogCtrl.order.orderUID"></span>
						    <strong><?= __('ID') ?></strong>
						  </li>
						  <li class="list-group-item">
						    <span class="pull-right" ng-bind="orderDialogCtrl.order.formatedOrderPlacedOn"></span>
						    <strong><?= __('Placed On') ?></strong>
						  </li>
						  <li class="list-group-item" ng-switch="orderDialogCtrl.order.orderStatus">

						    <span  ng-switch-when="1" 
						    	   class="label label-primary pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="2" 
						    		class="label label-warning pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="3" 
						    		class="label label-danger pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="4" 
						    		class="label label-warning pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="5" 
						    		class="label label-warning pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="6" 
						    		class="label label-success pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <span  ng-switch-when="7" 
						    		class="label label-warning pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						   	<span  ng-switch-when="11" 
						    		class="label label-warning pull-right" 
						    	   ng-bind="orderDialogCtrl.order.formatedOrderStatus"></span>

						    <strong><?= __('Status') ?></strong>
						  </li>
						  <li class="list-group-item">
						    <span class="pull-right" ng-bind="orderDialogCtrl.order.formatedOrderType"></span>
						    <strong><?= __('Type') ?></strong>
						  </li>
						</ul>

					</fieldset>
                </div>
                {{-- / order section--}}

            	{{-- payment section--}}
            	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

                	 <fieldset class="lw-fieldset-2">
						<legend>
							<?=  __('Payment Details')  ?> 
						</legend><br>

	                    <ul class="list-group">

						  	<li class="list-group-item">
						    	<span class="pull-right" ng-bind="orderDialogCtrl.order.formatedPaymentMethod"></span>
						    	<strong><?= __('Method') ?></strong>
						  	</li>

						  	<li class="list-group-item" ng-switch="orderDialogCtrl.order.paymentStatus">
						    	<span 
						    		ng-switch-when="1"  
						    		class="label label-warning pull-right" 
						    		ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

						    	<span 
						    		ng-switch-when="2"  
						    		class="label label-success pull-right" 
						    		ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

					    		<span 
					    			ng-switch-when="3"  
					    			class="label label-danger pull-right" 
					    			ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

					    		<span 
					    			ng-switch-when="4"  
					    			class="label label-warning pull-right" 
					    			ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

					    		<span 
					    			ng-switch-when="5"  
					    			class="label label-Info pull-right" 
					    			ng-bind="orderDialogCtrl.order.formatedPaymentStatus"></span>

						    	<strong><?= __('Status') ?></strong>
						  	</li>

							{{-- PaymentCompletedOn --}}
						  	<li class="list-group-item" ng-if="orderDialogCtrl.order.paymentCompletedOn">
						  	    <strong><?= __('Completed On') ?></strong>
						    	<span class="pull-right" ng-bind="orderDialogCtrl.order.paymentCompletedOn"></span>
						  	</li>
				  			{{--/ PaymentCompletedOn --}}

							{{-- Update your order payment using PayPal --}}
						  	<li class="list-group-item" ng-if="orderDialogCtrl.order.paymentStatus == 4"><!-- pending -->
						    	<span ><strong><a href ng-click="orderDialogCtrl.updatePayment(orderDialogCtrl.order.orderUID)"><?= __('Update your order payment using PayPal') ?></a></strong>
				  				</span>
						  	</li>
				  			{{--/ Update your order payment using PayPal --}}

						</ul>

					</fieldset>
				</div>
            </div>
            {{-- payment section--}}
            
        </div>

    </div>
    {{--/ order & payment --}}

	{{-- cart summary table --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4><strong><?=  __('Cart summary')  ?></strong></h4>
                </div>
                <div class="panel-body">
                    <div class="table-responsive lw-shopping-cart">
                        <table class="lw-order-cart-table table table-condensed">
                            <thead class="page-header">
				                <tr>
				                	<th class="text-center"><?=  __('Thumbnail')  ?></th>
				                    <th><?=  __('Item Description')  ?></th>
				                    <th class="text-center"><?=  __('Qty')  ?></th>
				                    <th class="text-right"><?=  __('Price')  ?></th>
				                    <th class="text-right"><?=  __('Subtotal')  ?></th>
				                </tr>
				            </thead>
                            <tbody>
                            
			            	{{-- products image, option and price come in this section --}}
				                <tr ng-repeat="item in orderDialogCtrl.orderProducts.products">
				              
				                    <td class="lw-product-thumbnail-column">
				                    	<a class="lw-product-thumbnail" href ng-href="[[item.detailsURL]]"><img ng-src="[[ item.imagePath ]]"></a>
				                    </td>
				                    <td>
				                    	{{-- product name and price --}}
				                    	<strong> <span ng-bind="item.productName"> </span> : </strong> (<span>+ [[ item.formatedPrice ]]</span>) <br>
				                    	{{-- product name and price --}}
										<div>
				                            <span ng-repeat="(key, option) in item.option">
				                            	<strong>[[option.optionName]] : </strong>[[option.valueName]] <span ng-show="option.addonPrice">( +[[option.formatedOptionPrice]])</span><br>
				                            </span>
				                        </div>
				                    </td>
				                    <td ng-bind="item.quantity"></td>
				                    <td align="right" ng-bind="item.formatedProductPrice"></td>
				                    <td align="right" ng-bind="item.formatedTotal"></td>
				                </tr>
								
				                <tr>
                                    <td colspan="3" class="lw-highrow"></td>
                                    <td class="lw-amount-td lw-highrow text-center"><h4><strong><?=  __('Subtotal')  ?></strong></h4></td>
                                    <td class="lw-highrow lw-amount-td">
                                    	<h4><span ng-bind="orderDialogCtrl.orderProducts.formatedSubtotal"></span></h4>
							        </td>
                                </tr>
				            </tbody>
			            	{{--/ products image, option and price come in this section --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	{{--/ cart summary table --}}
		
    {{-- coupon details section --}}
    <div class="row" ng-show="orderDialogCtrl.coupon">

        <div class="col-md-12">

			<ul class="list-group">

				<li class="list-group-item">
					<span class="pull-right"> - [[ orderDialogCtrl.order.formatedOrderDiscount ]]</span>
                	<strong ng-bind="orderDialogCtrl.coupon.title"></strong> :- <em><strong> <?= __('Applied Coupon Code')  ?></strong></em>
					<span class="label label-success"  ng-bind="orderDialogCtrl.coupon.code"></span>
			  	</li>


			  	<li class="list-group-item" ng-if="orderDialogCtrl.coupon.description">
			    	<span ng-bind="orderDialogCtrl.coupon.description"></span>
			  	</li>

			</ul>

        </div>

    </div>
	{{--/ coupon details section --}}
	
	{{-- Address section --}}
    <div class="row">

        <div ng-if="orderDialogCtrl.sameAddress == false">
			{{-- Shipping Address section --}}
        	<div class="col-lg-6">
		        <div class="panel panel-default">
	                <div class="panel-heading">
	                    <strong><?= __('Shipping Address')  ?></strong>
	                </div>
	                <div class="panel-body">
	                    <address class="lw-address">
	                        <strong ng-bind="orderDialogCtrl.user.fullName"></strong><br>
			            	<strong><?= __('Address')  ?></strong> (<em ng-bind="orderDialogCtrl.shippingAddress.type"></em>)
			                <br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.addressLine1"></span><br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.addressLine2"></span><br>
	                        <span ng-bind="orderDialogCtrl.shippingAddress.city"></span>, 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.state"></span>, 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.country"></span><br>
	                        <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
	                        <span ng-bind="orderDialogCtrl.shippingAddress.pinCode"></span>
	                    </address>
	                </div>
		        </div>
	        </div>
			{{-- Shipping Address section --}}

			{{-- Billing Address section --}}
			<div class="col-lg-6">
		        <div class="panel panel-default">
	                <div class="panel-heading">
	                    <strong><?= __('Billing Address')  ?></strong>
	                </div>
	                <div class="panel-body">
	                    <address class="lw-address">
	                        <strong>
	                        	<span ng-bind="orderDialogCtrl.billingAddress.type"></span>
	                        </strong><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.addressLine1"></span><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.addressLine2"></span><br>
	                        <span ng-bind="orderDialogCtrl.billingAddress.city"></span>, 
	                        <span ng-bind="orderDialogCtrl.billingAddress.state"></span>, 
	                        <span ng-bind="orderDialogCtrl.billingAddress.country"></span><br>
	                        <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
	                        <span ng-bind="orderDialogCtrl.billingAddress.pinCode"></span>
	                    </address>
	                </div>
		        </div>
	        </div>
	    </div>
		{{-- Billing Address section --}}

		{{-- Shipping / Billing Address section --}}
        <div class="col-lg-12">
			<div class="panel panel-default" ng-if="orderDialogCtrl.sameAddress == true"> 
		        <div class="panel-heading">
		            <strong><?= __('Shipping / Billing Address')  ?></strong>
		        </div>
		        <div class="panel-body">
		            <address class="lw-address">
		            	<strong ng-bind="orderDialogCtrl.user.fullName"></strong><br>
		            	<strong><?= __('Address')  ?></strong> (<em ng-bind="orderDialogCtrl.shippingAddress.type"></em>)
		                <br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.addressLine1"></span><br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.addressLine2"></span><br>
		                <span ng-bind="orderDialogCtrl.shippingAddress.city"></span>, 
		                <span ng-bind="orderDialogCtrl.shippingAddress.state"></span>, 
		                <span ng-bind="orderDialogCtrl.shippingAddress.country"></span><br>
		                <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
		                <span ng-bind="orderDialogCtrl.shippingAddress.pinCode"></span>
		            </address>
		        </div>
		    </div>
        </div>
		{{-- Shipping / Billing Address section --}}
    </div>
	{{--/ Address section --}}
	
	{{-- Shipping details section --}}
    <div class="row">

        <div class="col-md-12">
				
			<ul class="list-group">

				<li class="list-group-item" ng-if="orderDialogCtrl.order.shippingAmount">
			    	<span class="pull-right" ng-show="orderDialogCtrl.order.shippingAmount"> 
                		+ <span ng-bind="orderDialogCtrl.order.formatedShippingAmount"></span>
	                </span>
					<span ng-show="!orderDialogCtrl.order.shippingAmount">
                		<?=  __('Free')  ?>
                	</span>
                	<strong><?= __('Shipping Charges') ?></strong>
			  	</li>

			  	<li class="list-group-item" ng-if="orderDialogCtrl.shipping.notes">
			    	<span ng-bind="orderDialogCtrl.shipping.notes"></span>
			  	</li>

			</ul>

        </div>

    </div>
	{{--/ Shipping details section --}}

	{{-- Tax details section --}}
    <div class="row" ng-show="orderDialogCtrl.taxes">
        <div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr ng-repeat="tax in orderDialogCtrl.taxes">
							<td colspan="3">
								<sapn>
							   		<strong ng-show="tax.label" ng-bind="tax.label"></strong><br>
							   		<sapn ng-show="tax.notes" ng-bind="tax.notes"></sapn>
							   	</sapn>
							   	<strong ng-show="!tax.label"><?=  __('Tax')  ?></strong>
			               	</td>
			               	<td class="lw-amount-td">
							   	<!-- Tax Amount -->
			                	<span ng-show="tax.taxAmount" >
			                		+ <span ng-bind="tax.formatedTaxAmount"></span>
			                	</span>
			                	<!-- /Tax Amount -->
			                </td>
						</tr>
					</thead>
				</table>
			</div>
        </div>
    </div>
	{{--/ Tax details section --}}
		
	{{-- Total payable amount --}}
	<div class="panel panel-default">
		<div class="table-responsive">
			<table class="table table-bordered" border="1">
				<thead>
					<tr>
						<td class="lw-emptyrow text-center"><h3><?= __('Total Amount') ?></h3></td>
			            <td class="lw-emptyrow lw-amount-td text-center">
			            	<h3><span ng-bind="orderDialogCtrl.order.formatedTotalOrderAmount"></span>
							<span ng-bind="orderDialogCtrl.order.currencyCode"></span></h3>
						</td>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	{{-- /Total payable amount --}}

</div>