<div ng-controller="ManageOrderDetailsController as OrderDetailsCtrl" ng-show="OrderDetailsCtrl.initialContendLoaded">
	
	<!-- main heading -->
   	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Order Details' )  ?> (<em ng-bind="OrderDetailsCtrl.order.orderUID"></em>)</h3>
        <a title="<?= __('Contact User') ?>" ng-click="OrderDetailsCtrl.contactUserDialog()" class="btn btn-default pull-right btn-primary btn-sm" href="" ><i class="fa fa-envelope" aria-hidden="true"></i> <?= __('Contact User') ?></a>&nbsp;
        <a class="btn btn-default pull-right btn-sm" title="<?=  __('Go to Manage Orders')  ?>" ui-sref="orders.active"><?=  __("Manage Orders")  ?></a>
    </div>
   	<!-- /main heading -->
  	

	{{-- order & payment --}}
    <div class="row">

        <div class="col-xs-12">

            <div class="row">

                {{-- order section--}}
                <div class="col-xs-12 col-md-3 col-lg-6 pull-left">

                     <fieldset class="lw-fieldset-2">
                        <legend>
                            <?=  __('Details')  ?> 
                        </legend><br>

                        <ul class="list-group">
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="OrderDetailsCtrl.order.orderUID"></span>
                            <strong><?= __('ID') ?></strong>
                          </li>
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="OrderDetailsCtrl.order.formatedOrderPlacedOn"></span>
                            <strong><?= __('Placed On') ?></strong>
                          </li>
                          <li class="list-group-item" ng-switch="OrderDetailsCtrl.order.orderStatus">

                            <span  ng-switch-when="1" 
                                   class="label label-primary pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="2" 
                                    class="label label-warning pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="3" 
                                    class="label label-danger pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="4" 
                                    class="label label-warning pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="5" 
                                    class="label label-warning pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="6" 
                                    class="label label-success pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="7" 
                                    class="label label-warning pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <span  ng-switch-when="11" 
                                    class="label label-warning pull-right" 
                                   ng-bind="OrderDetailsCtrl.order.formatedOrderStatus"></span>

                            <strong><?= __('Status') ?></strong>
                          </li>
                          <li class="list-group-item">
                            <span class="pull-right" ng-bind="OrderDetailsCtrl.order.formatedOrderType"></span>
                            <strong><?= __('Type') ?></strong>
                          </li>
                        </ul>

                    </fieldset>
                </div>
                {{-- / order section--}}


                {{-- payment section--}}
                <div class="col-xs-12 col-md-3 col-lg-6">

                     <fieldset class="lw-fieldset-2">
                        <legend>
                            <?=  __('Payment Details')  ?> 
                        </legend><br>

                        <ul class="list-group">

                            <li class="list-group-item">
                                <span class="pull-right" ng-bind="OrderDetailsCtrl.order.formatedPaymentMethod"></span>
                                <strong><?= __('Method') ?></strong>
                            </li>

                            <li class="list-group-item" ng-switch="OrderDetailsCtrl.order.paymentStatus">
                                <span 
                                    ng-switch-when="1"  
                                    class="label label-warning pull-right" 
                                    ng-bind="OrderDetailsCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="2"  
                                    class="label label-success pull-right" 
                                    ng-bind="OrderDetailsCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="3"  
                                    class="label label-danger pull-right" 
                                    ng-bind="OrderDetailsCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="4"  
                                    class="label label-warning pull-right" 
                                    ng-bind="OrderDetailsCtrl.order.formatedPaymentStatus"></span>

                                <span 
                                    ng-switch-when="5"  
                                    class="label label-info pull-right" 
                                    ng-bind="OrderDetailsCtrl.order.formatedPaymentStatus"></span>

                                <strong><?= __('Status') ?></strong>
                            </li>

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
                                <tr ng-repeat="item in OrderDetailsCtrl.orderProducts.products">
                              
                                    <td class="lw-product-thumbnail-column">
                                        <a class="lw-product-thumbnail" href ng-href="[[item.detailURL]]"><img ng-src="[[ item.imagePath ]]"></a>
                                    </td>
                                    <td>
                                        {{-- product name and price --}}
                                        <strong> <span ng-bind="item.productName"> </span> : </strong> (<span ng-bind="item.formatedPrice"></span>) <br>
                                        {{-- product name and price --}}
                                        <div>
                                            <span ng-repeat="(key, option) in item.option"><strong>[[option.optionName]] : </strong>[[option.valueName]] ([[option.formatedOptionPrice]])<br></span>
                                        </div>
                                    </td>
                                    <td ng-bind="item.quantity"></td>
                                    <td align="right" ng-bind="item.formatedProductPrice"></td>
                                    <td align="right" ng-bind="item.formatedTotal"></td>
                                </tr>
                                
                                <tr>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-highrow"></td>
                                    <td class="lw-amount-td lw-highrow text-center"><h4><strong><?=  __('Subtotal')  ?></strong></h4></td>
                                    <td class="lw-highrow lw-amount-td">
                                        <h4><span ng-bind="OrderDetailsCtrl.orderProducts.formatedSubtotal"></span></h4>
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
    <div class="row" ng-show="OrderDetailsCtrl.coupon">

        <div class="col-md-12">

            <ul class="list-group">

                <li class="list-group-item">
                    <span class="pull-right"> - [[ OrderDetailsCtrl.order.formatedOrderDiscount ]]</span>
                    <strong ng-bind="OrderDetailsCtrl.coupon.title"></strong> :- <em><strong> <?= __('Applied Coupon Code')  ?></strong></em>
                    <span class="label label-success"  ng-bind="OrderDetailsCtrl.coupon.code"></span>
                </li>


                <li class="list-group-item" ng-if="OrderDetailsCtrl.coupon.description">
                    <span ng-bind="OrderDetailsCtrl.coupon.description"></span>
                </li>

            </ul>

        </div>

    </div>
    {{--/ coupon details section --}}
    
    {{-- Address section --}}
    <div class="row">

        <div ng-if="OrderDetailsCtrl.sameAddress == false">
            {{-- Shipping Address section --}}
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong><?= __('Shipping Address')  ?></strong>
                    </div>
                    <div class="panel-body">
                        <address class="lw-address">
                            <strong ng-bind="OrderDetailsCtrl.user.fullName"></strong><br>
                            <strong><?= __('Address')  ?></strong> (<em ng-bind="OrderDetailsCtrl.shippingAddress.type"></em>)
                            <br>
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.addressLine1"></span><br>
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.addressLine2"></span><br>
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.city"></span>, 
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.state"></span>, 
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.country"></span><br>
                            <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
                            <span ng-bind="OrderDetailsCtrl.shippingAddress.pinCode"></span>
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
                                <span ng-bind="OrderDetailsCtrl.billingAddress.type"></span>
                            </strong><br>
                            <span ng-bind="OrderDetailsCtrl.billingAddress.addressLine1"></span><br>
                            <span ng-bind="OrderDetailsCtrl.billingAddress.addressLine2"></span><br>
                            <span ng-bind="OrderDetailsCtrl.billingAddress.city"></span>, 
                            <span ng-bind="OrderDetailsCtrl.billingAddress.state"></span>, 
                            <span ng-bind="OrderDetailsCtrl.billingAddress.country"></span><br>
                            <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
                            <span ng-bind="OrderDetailsCtrl.billingAddress.pinCode"></span>
                        </address>
                    </div>
                </div>
            </div>
        </div>
        {{-- Billing Address section --}}

        {{-- Shipping / Billing Address section --}}
        <div class="col-lg-6">
            <div class="panel panel-default" ng-if="OrderDetailsCtrl.sameAddress == true"> 
                <div class="panel-heading">
                    <strong><?= __('Shipping / Billing Address')  ?></strong>
                </div>
                <div class="panel-body">
                    <address class="lw-address">
                        <strong ng-bind="OrderDetailsCtrl.user.fullName"></strong><br>
                        <strong><?= __('Address')  ?></strong> (<em ng-bind="OrderDetailsCtrl.shippingAddress.type"></em>)
                        <br>
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.addressLine1"></span><br>
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.addressLine2"></span><br>
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.city"></span>, 
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.state"></span>, 
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.country"></span><br>
                        <span title="<?=  __('Pin Code')  ?>"><?=  __('Pin Code')  ?> : </span> 
                        <span ng-bind="OrderDetailsCtrl.shippingAddress.pinCode"></span>
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

                <li class="list-group-item" ng-if="OrderDetailsCtrl.shipping.notes">
                    <span class="pull-right" ng-show="OrderDetailsCtrl.order.shippingAmount"> 
                        + <span ng-bind="OrderDetailsCtrl.order.formatedShippingAmount"></span>
                    </span>
                    <span ng-show="!OrderDetailsCtrl.order.shippingAmount">
                        <?=  __('Free')  ?>
                    </span>
                    <strong><?= __('Shipping Charges') ?></strong>
                </li>

                <li class="list-group-item" ng-if="OrderDetailsCtrl.shipping.notes">
                    <span ng-bind="OrderDetailsCtrl.shipping.notes"></span>
                </li>

            </ul>

        </div>

    </div>
    {{--/ Shipping details section --}}

    {{-- Tax details section --}}
    <div class="row" ng-show="OrderDetailsCtrl.taxes">

        <div class="col-md-12">

            <table class="table table-bordered">
                <thead>
                    <tr ng-repeat="tax in OrderDetailsCtrl.taxes">
                        <td>
                            <sapn class="pull-left">
                                <strong ng-show="tax.label" ng-bind="tax.label"></strong><br>
                                <sapn ng-show="tax.notes" ng-bind="tax.notes"></sapn>
                            </sapn>
                            <strong ng-show="!tax.label"><?=  __('Tax')  ?></strong>
                        </td>
                        <td>
                            <!-- Tax Amount -->
                            <span ng-show="tax.taxAmount" >
                                + <span ng-bind="tax.formatedTaxAmount"></span>
                            </span>
                            <!-- /Tax Amount -->
                            <span ng-show="!tax.taxAmount">
                                <?=  __('Free')  ?>
                            </span>
                        </td>
                    </tr>

                </thead>

            </table>

        </div>

    </div>
    {{--/ Tax details section --}}
        
    {{-- Total payable amount --}}
    <div class="panel panel-default">
        <table class="table table-bordered" border="1">
            <thead>
                <tr>
                    <td class="lw-emptyrow text-center"><h3><?= __('Total Amount') ?></h3></td>
                    <td class="lw-emptyrow lw-amount-td text-center">
                        <h3><span ng-bind="OrderDetailsCtrl.order.formatedTotalOrderAmount"></span>
                        <span ng-bind="OrderDetailsCtrl.order.currencyCode"></span></h3>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    {{-- /Total payable amount --}}

</div>