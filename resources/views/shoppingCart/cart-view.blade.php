<div class="lw-shopping-cart-dialog-content" ng-controller="CartController as CartCtrl">

    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header">@section('page-title',  __('Shopping Cart')) <?= __('Shopping Cart') ?></h3>
    </div>
    <!-- /main heading -->

    <div  ng-if="!CartCtrl.cartDataStatus" class="text-center">
        <div class="loader"><?=  __('Loading...')  ?></div>
    </div>

    <form class="lw-form lw-ng-form" name="CartCtrl.[[ CartCtrl.ngFormName ]]" novalidate>

        <div ng-if="CartCtrl.cartDataStatus">
            
            <!-- table -->
            <div class="table-responsive lw-shopping-cart" ng-hide="CartCtrl.cartData.items == ''">
                
                <table class="table table-bordered" cellspacing="0" width="100%">
                    <thead class="page-header">
                        <tr>
                            <th class="text-center"><?= __('Thumbnail') ?></th>
                            <th><?= __('Item Description') ?></th>                            
                            <th class="text-right"><?= __('Price') ?></th>
                            <th class="text-center" style="min-width: 180px;"><?= __('Qty') ?></th>
                            <th class="text-right"><?= __('Subtotal') ?></th>
                            <th class="text-center"><?= __('Remove') ?></th>
                        </tr>
                    </thead>
                    <tbody ng-if="CartCtrl.cartData.items">
                        
                        <tr ng-repeat="item in CartCtrl.cartData.items track by item.rowid" id="rowid_[[item.rowid]]"> 
                            <td class="lw-product-thumbnail-column">
                                <a class="lw-product-thumbnail" href ng-href="[[item.productDetailURL]]"><img ng-src="[[ item.thumbnail_url ]]"></a>
                            </td>
                            <td>
                                <strong> <span ng-bind="item.name"> </span> : </strong> (<span> +[[item.formated_price]]</span>) <br>
                                <span ng-repeat="(key, option) in item.options">
                                    <span ng-show="option.addonPrice != 0">
                                        <strong>[[option.optionName]] : </strong> [[option.valueName]] ( +[[option.formated_addon_price]])<br>
                                    </span>
                                    <span ng-show="option.addonPrice == 0">
                                        <strong>[[option.optionName]] : </strong> [[option.valueName]]<br>

                                    </span>
                                </span>
                                <div class="lw-error-msg-order">
                                    <span class="lw-order-product-stock">
                                        <span ng-if="item.ERROR_MSG" ng-bind="item.ERROR_MSG"></span>
                                    </span>
                                </div>
                            </td>                            
                            <td class="text-right" ng-bind="item.new_price"></td>
                            <td  width="60">

                                <!-- Quantity -->     
                                <lw-form-field field-for="items.[[ item.rowid ]].qty" class="lw-form-group" label="">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button title="<?=  __('Decrement')  ?>" type="button" class=" btn btn-default btn-number" ng-click="CartCtrl.updateQuantity(false, item.rowid, item.qty, item.new_price)">
                                            <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>

                                        <input style="text-align:center;min-width:40px;" type="number"
                                        class="lw-form-field form-control"
                                        name="items.[[ item.rowid ]].qty"
                                        ng-model="item.qty"
                                        ng-blur="CartCtrl.updateQuantity('eventUp', item.rowid, item.qty, item.new_price)"  />

                                        <span class="input-group-btn">
                                            <button title="<?=  __('Loading..')  ?>" type="button" class="btn btn-default btn-number" ng-if="item.qtyStatus == false">
                                            <i class="fa fa-spinner fa-spin"></i> 
                                            </button>
                                            <button title="<?=  __('Increment')  ?>" type="button" ng-click="CartCtrl.updateQuantity(true, item.rowid, item.qty, item.new_price)" class="btn btn-default btn-number">
                                            <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                        
                                    </div>

                                </lw-form-field>
                                <!-- /Quantity -->

                            </td>
                            <td class="text-right" ng-bind="item.new_subTotal"></td>
                            <td class="text-center"><a class="btn btn-default" href="" title="<?= __('Remove') ?>" ng-click="CartCtrl.removeCartItem(item.rowid)"><span class="fa fa-trash-o fa-lg"></span></a></td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" id="shopping_cart_total" ng-hide="CartCtrl.cartData.items == ''"  class="alert text-right">
                                <strong><?=  __('Your Cart Total')  ?>:</strong> [[CartCtrl.cartData.totalPrice.total]] [[CartCtrl.cartData.totalPrice.currency]]                                 
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="alert alert-info">
                                <?= __( 'Please continue to proceed order' ) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>

            </div>
            <!-- /table -->

            <!--  if cart is empty  -->
            <div class="alert alert-info" ng-if="CartCtrl.cartData.items.length == 0">
                <?=  __("Your cart is empty.")  ?>
            </div>
            <!--  /if cart is empty -->

			<div class="lw-dotted-line"></div>
			
            <!-- action button -->
            <div class="lw-form-actions">
            
                <a ng-click="CartCtrl.cancel()" ng-if="CartCtrl.pageType == false" class="btn btn-default lw-btn lw-xs-dblock" title="<?= __('Continue Shopping') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __('Continue Shopping') ?></a>

                <a href="<?=  route('home.page')  ?>" ng-if="CartCtrl.pageType == true" class="btn btn-default lw-btn lw-show-process-action lw-xs-dblock" title="<?= __('Close for Now & do some more Shopping!!') ?>"><i class="fa fa-arrow-circle-left"></i> <?= __('Continue Shopping') ?></a>
				
				{{-- Disabled btn when any cart item is invalid then display btn conditionalty --}}
            	<span class="pull-right lw-xs-dblock" ng-switch="CartCtrl.disabledStatus">

                	<a  ng-switch-when="true" 
	                	href="<?=  route('order.summary.view')  ?>" 
	                	class="btn btn-success lw-btn btn-lg lw-show-process-action lw-xs-dblock" 
	                	title="<?= __('Complete your Order') ?>">
	                	<?=  __('Complete your Order')  ?> 
	                	<i class="fa fa-check-circle-o fa-fw"></i>
                	</a>

                	<a ng-switch-when="false" 
	                	disabled 
	                	href 
	                	class="btn btn-success lw-btn btn-lg lw-xs-dblock" 
	                	title="<?= __('Complete your Order') ?>">
	                	<?= __('Complete your Order') ?> 
	                	<i class="fa fa-check-circle-o fa-fw"></i>
	                </a>

				</span>
				{{--/ Disabled btn ....... conditionalty  --}}

                <button ng-hide="CartCtrl.cartData.items == ''" type="button" ng-click="CartCtrl.removeAllItemsItem()" class="btn btn-danger lw-btn lw-show-process-action lw-xs-dblock" title="<?= __('Empty Cart') ?>"><?= __('Empty Cart') ?></button>

            </div>
            <!-- /action button -->
            
        </div>

    </form>
</div>