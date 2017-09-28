<div class="lw-dialog" ng-controller="BrandDetailDialogController as brandDetailCtrl">
	
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Brand Details' )  ?></h3>
    </div>
    <!-- /main heading -->

	<div class="panel panel-default">
		<div class="table-responsive">
		   <!-- Table -->
		   <table class="table table-bordered" border="1">

		  		<!-- Name -->
		    	<tr>
		    		<th><?=  __('Name')  ?></th>
		    		<td ng-bind="brandDetailCtrl.brandData.name"></td>
		    	</tr>
		    	<!-- /Name -->

		    	<!-- Status -->
		    	<tr>
		    		<th><?=  __('Status')  ?></th>
		    		<td>
		    			<span ng-if="brandDetailCtrl.brandData.active == true">
					   		<?=  __('Active')  ?>
					   	</span>
					   	<span ng-if="brandDetailCtrl.brandData.active == false">
					   		<?=  __('Deactive')  ?>
					   	</span>
					</td>
		    	</tr>
		    	<!-- / Status -->
		   </table>  
		   <!--/ Table -->
		</div>
	</div>

  	<!-- discription -->
  	<hr>
	<div>
   		<span ng-bind="brandDetailCtrl.brandData.description"></span>
	</div>
	<!-- /discription -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"></h3>
    </div>
	<!-- action button -->
	<div>
   		<button type="button" class="lw-btn btn btn-default" ng-click="brandDetailCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
    </div>
   <!-- /action button -->
</div>