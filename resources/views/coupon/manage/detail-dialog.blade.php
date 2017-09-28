<div class="lw-dialog" ng-controller="CouponDetailDialogController as couponDetailCtrl">
	
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Coupon Details' )  ?></h3>
    </div>
    <!-- /main heading -->
	
	
	<div class="panel panel-default">
	  <!-- Table -->
	  <div class="table-responsive">
		  <table class="table table-bordered" border="1">
		    	<tr>
		    		<!-- title of the coupon -->
		    		<th><?=  __('Title')  ?></th>
		    		<td ng-bind="couponDetailCtrl.couponDate.title"></td>
		    		<!-- /title of the coupon -->
		    	</tr>
		    	<tr>
		    		<!-- code of the coupon -->
		    		<th><?=  __('Code')  ?></th>
		    		<td ng-bind="couponDetailCtrl.couponDate.code"></td>
		    		<!-- /code of the coupon -->
		    	</tr>
		    	<tr>
					<!-- Discount -->
		    		<th><?=  __('Discount')  ?></th>
		    		<td>
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
		    				<span ng-bind="couponDetailCtrl.couponDate.currencySymbol"></span>
		    				<span ng-bind="couponDetailCtrl.couponDate.discount"></span>
		    			</span>
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
		    				<span ng-bind="couponDetailCtrl.couponDate.discount"></span>
		    				<span>%</span>
		    			</span>
		    		</td>
					<!-- /Discount -->	
		    	</tr>
		    	<tr>
		    		<th>
		    			<!-- Max Discount -->
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
		    				<?=  __('Max Discount in % of product price')  ?>
		    			</span>
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
		    				<?=  __('Max Discount')  ?>
		    			</span>
		    			<!-- /Max Discount -->
		    		</th>
		    		<td>
		    			<!-- Discount type -->
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 2">
		    				<span ng-bind="couponDetailCtrl.couponDate.currencySymbol"></span>
		    				<span ng-bind="couponDetailCtrl.couponDate.max_discount"></span>
		    			</span>
		    			<span ng-if="couponDetailCtrl.couponDate.discount_type == 1">
		    				<span ng-bind="couponDetailCtrl.couponDate.max_discount"></span>
		    				<span>%</span>
		    			</span>
		    			<!-- /Discount type -->
		    		</td>
		    	</tr>
		    	<tr>
		    		<!-- minimum order amount -->
		    		<th><?=  __('Minimum Order Amount')  ?></th>
		    		<td>
		    			<span ng-bind="couponDetailCtrl.couponDate.currencySymbol"></span>
		    			<span ng-bind="couponDetailCtrl.couponDate.minimum_order_amount"></span>
		    		</td>
		    		<!-- /minimum order amount -->
		    	</tr>
		  </table>  
	  	<!--/ Table -->
	  </div>
	</div>
	
	
	<!-- description -->
	<div ng-if="couponDetailCtrl.couponDate.description != ''">
   		<hr><span ng-bind="couponDetailCtrl.couponDate.description"></span><hr>
	</div>
	<!-- /description -->

	<!-- action button -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="couponDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!-- /action button -->
</div>