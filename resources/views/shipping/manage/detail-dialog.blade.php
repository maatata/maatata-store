<div class="lw-dialog" ng-controller="ShippingDetailController as shippingDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Shipping Rule Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	
	<div class="panel panel-default">
	  <!--  Table  -->
	  <div class="table-responsive">
		  <table class="table table-bordered" border="1">
		  	<tbody>
		    	<tr>
		    		<!-- / Country  --> 
		    		<th><?=  __('Country')  ?></th>
		    		<td ng-bind="shippingDetailCtrl.shippingDetail.country"></td>
		    		<!-- / Country  --> 
		    	</tr>
		    	<tr>
		    		<!-- / Shipping Type  --> 
		    		<th><?=  __('Shipping Type')  ?></th>
		    		<td ng-bind="shippingDetailCtrl.shippingDetail.type"></td>
		    		<!-- / Shipping Type  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.charges != null">
		    		<!-- / Charges  --> 
		    		<th><?=  __('Charges')  ?></th>
		    		<td>
		    			<span ng-if="shippingDetailCtrl.shippingDetail.shippingType == 1">
		    				<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		    			</span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.charges"></span>
		   				<span ng-if="shippingDetailCtrl.shippingDetail.shippingType == 2">%</span>
		    		</td>
		    		<!-- / Charges  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.free_after_amount != null">
		    		<!-- / Free after Amount  --> 
		    		<th><?=  __('Free Shipping if Order Amount more than')  ?></th>
		    		<td>
		    			<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.free_after_amount"></span>
		    		</td>
		    		<!-- / Free after Amount  --> 
		    	</tr>
		    	<tr ng-if="shippingDetailCtrl.shippingDetail.amount_cap != null">
		    		<!-- / Amount Cap  --> 
		    		<th><?=  __('Maximum Shipping Amount')  ?></th>
		    		<td>
		    			<span ng-bind="shippingDetailCtrl.currencySymbol"></span>
		   				<span ng-bind="shippingDetailCtrl.shippingDetail.amount_cap"></span>
		    		</td>
		    		<!-- / Amount Cap  --> 
		    	</tr>
		   	</tbody>
		  </table>
		  <!--  /Table  -->  
	   </div>
	</div>
	
	<!--  Notes  --> 
	<div class="panel panel-default" ng-if="shippingDetailCtrl.shippingDetail.notes != ''">
	  <div class="panel-heading"><strong><?=  __('Notes')  ?></strong></div>
	  <div class="panel-body" ng-bind="shippingDetailCtrl.shippingDetail.notes"></div>
	</div>
	<!-- / Notes  --> 

	<!--  close button  -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="shippingDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!--  /close button  -->
</div>