<div ng-controller="ManageUpdateOrderController as updateOrderCtrl" class="lw-dialog">
	
	<!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header" ng-bind="updateOrderCtrl.updateDialogTitle"></h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="updateOrderCtrl.[[ updateOrderCtrl.ngFormName ]]" 
        novalidate>
        
        <!--  current status  -->
        <div class="form-group ">
	      	<label for="current_status" class="control-label"><?= __('Current Status') ?></label>
		      <input readonly type="text" class="form-control" id="current_status" ng-model="updateOrderCtrl.orderData.statusName">
	    </div>
	    <!--  current status  -->

	    <!--  current payment status  -->
        <div class="form-group ">
	      	<label for="current_status" class="control-label"><?= __('Current Payment Status') ?></label>
		      <input readonly type="text" class="form-control" id="current_payment_status" value="[[ updateOrderCtrl.orderData.currentPaymentStatus ]]">
	    </div>
	    <!--  current payment status  -->

        <!-- status -->
        <lw-form-field field-for="status" label="<?= __( 'Status' ) ?>"> 
           <select class="form-control" 
                name="status" ng-model="updateOrderCtrl.orderData.status" ng-options="type.id as type.name for type in updateOrderCtrl.statuses" ng-required="true">
                <option value='' disabled selected><?=  __('-- Select Status --')  ?></option>
            </select> 
        </lw-form-field>
        <!-- /status-->

       <!-- Description -->
		<div>
	        <lw-form-field field-for="description" label="<?= __('Additional Notes') ?>"> 
	            <textarea name="description" class="lw-form-field form-control"
	             cols="10" rows="3" ng-model="updateOrderCtrl.orderData.description"></textarea>
	        </lw-form-field>
        <!-- Description -->
		</div>

		<!-- Checkout Options -->
        <div class="form-group checkbox">
            <label>
                <input type="checkbox" 
                    class="lw-form-field"
                    name="checkMail"
                    ng-model="updateOrderCtrl.orderData.checkMail" /> </span> <?= __('Notify Customer') ?>
            </label>
        </div>
        <!-- /Checkout Options -->
        
		<div class="lw-dotted-line"></div>

		<!-- Action -->
        <div class="lw-form-actions">
            <button type="submit" ng-click="updateOrderCtrl.update()" class="lw-btn btn btn-primary lw-btn-process" title="<?= __('Update') ?>"><?= __('Update') ?> </button>

            <button type="button" ng-click="updateOrderCtrl.closeDialog()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /Action -->
    </form>
	<!-- form action -->
</div>

