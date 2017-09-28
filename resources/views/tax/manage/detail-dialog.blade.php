<div class="lw-dialog" ng-controller="TaxDetailController as taxDetailCtrl">
	
	<!--  main heading  -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Tax Details' )  ?></h3>
    </div>
    <!--  /main heading  -->
	
	
	<div class="panel panel-default">
	  <!--  Table  -->
	  <div class="table-responsive">
		  <table class="table table-bordered" border="1">
	    	<tr>
	    		<!--  Country  --> 
	    		<th><?=  __('Country')  ?></th>
	    		<td ng-bind="taxDetailCtrl.taxData.country"></td>
	    		<!--  /Country  --> 
	    	</tr>
	    	<tr>
	    		<!--  Tax Type  --> 
	    		<th><?=  __('Tax Type')  ?></th>
	    		<td ng-bind="taxDetailCtrl.taxData.type"></td>
	    		<!--  /Tax Type  --> 
	    	</tr>
	    	<tr>
	    		<!--  Label  --> 
	    		<th><?=  __('Label')  ?></th>
	    		<td ng-bind="taxDetailCtrl.taxData.label"></td>
	    		<!--  /Label  --> 
	    	</tr>
	    	<tr ng-if="taxDetailCtrl.taxData.applicable_tax != null">
	    		<!--  Applicable Tax  --> 
	    		<th><?=  __('Applicable Tax')  ?></th>
	    		<td>
	    			<span ng-if="taxDetailCtrl.taxData.taxType == 1">
	    				<span ng-bind="taxDetailCtrl.currencySymbol"></span>
	    			</span>
	   				<span ng-bind="taxDetailCtrl.taxData.applicable_tax"></span>
	   				<span ng-if="taxDetailCtrl.taxData.taxType == 2">%</span>
	    		</td>
	    		<!--  /Applicable Tax  --> 
	    	</tr>
	    	<tr>
	    		<!--  Status  --> 
	    		<th><?=  __('Status')  ?></th>
	    		<td>
	    			<span ng-if="taxDetailCtrl.taxData.active == true">
				   		<?=  __('Active')  ?>
				   	</span>
				   	<span ng-if="taxDetailCtrl.taxData.active == false">
				   		<?=  __('Inactive')  ?>
				   	</span>
	    		</td>
	    		<!--  /Status  --> 
	    	</tr>
		  </table>
	  </div>
	  <!--  /Table  -->  
	</div>
	
	<!--  Notes  --> 
	<div class="panel panel-default" ng-if="taxDetailCtrl.taxData.notes != ''">
	  <div class="panel-heading"><strong><?=  __('Notes')  ?></strong></div>
	  <div class="panel-body" ng-bind="taxDetailCtrl.taxData.notes"></div>
	</div>
	<!-- / Notes  --> 

	<!--  close dialog button  -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="taxDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!--  /close dialog button  -->
</div>