<div class="lw-dialog" ng-controller="ManageUpdateOrderPaymentController as updateOrderPaymentCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Update Order Payment' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- order payment status messages -->
	<div>
		<!-- status 1 (Awaiting Payment Confirmation) -->
		<div ng-if="updateOrderPaymentCtrl.orderDetails.paymentStatus == 1">
			<div class="alert alert-danger">
			  <strong><?= ( __('Awaiting Payment Confirmation') ) ?></strong>
			</div>
		</div>
		<!-- /status 1 (Awaiting Payment Confirmation) -->

		<!-- status 2 (Completed) -->
		<div ng-if="updateOrderPaymentCtrl.orderDetails.paymentStatus == 2">
			<div class="alert alert-success">
			  <strong><?= ( __('Order Payment Completed') ) ?></strong>
			</div>
		</div>
		<!-- status 2 (Completed) -->

		<!-- status 3 (Payment failed) -->
		<div ng-if="updateOrderPaymentCtrl.orderDetails.paymentStatus == 3">
			<div class="alert alert-danger">
			  <strong><?= ( __('Order Payment failed') ) ?></strong>
			</div>
		</div>
		<!-- status 3 (Payment failed) -->

		<!-- status 4 (N/A) -->
		<div ng-if="updateOrderPaymentCtrl.orderDetails.paymentStatus == 4">
			<div class="alert alert-danger">
			  <strong><?= ( __('Order Payment N/A') ) ?></strong>
			</div>
		</div>
		<!-- status 4 (N/A) -->
	</div>
	<!-- order payment status messages -->

	<!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="updateOrderPaymentCtrl.[[ updateOrderPaymentCtrl.ngFormName ]]" 
        novalidate>


		<!--  current order status  -->
	    <div class="form-group ">
	      	<label for="current_status" class="control-label"><?= __('Current Order Status') ?></label>
		      <input readonly type="text" class="form-control" id="current_status" ng-model="updateOrderPaymentCtrl.orderDetails.orderStatus">
	    </div>
	    <!--  current order status  -->
        
		<!-- payment method -->
	    <div> 
	        <lw-form-field field-for="paymentMethod" label="<?= __( 'Payment Method' ) ?>"> 
	           <select class="form-control" 
	                name="paymentMethod" ng-model="updateOrderPaymentCtrl.orderDetails.paymentMethod" ng-options="type.id as type.name for type in updateOrderPaymentCtrl.paymentMethodList" ng-required="true">
	                <option value='' disabled selected><?=  __('-- Select Payment Method --')  ?></option>
	            </select> 
	        </lw-form-field>
	    </div>
	    <!-- /payment method-->

	    <!-- Amount Paid -->
	    <lw-form-field field-for="txn" label="<?= __( 'Transaction ID' ) ?>"> 
	        <input type="text" 
	          class="lw-form-field form-control"
	          name="txn"
	          ng-required="true" 
	          ng-model="updateOrderPaymentCtrl.orderDetails.txn" />
	    </lw-form-field>
	    <!-- /txn -->

	    <!-- fee -->
	    <lw-form-field field-for="fee" label="<?= __( 'Fee' ) ?>"> 
	        <input type="number" 
	          class="lw-form-field form-control"
	          name="fee"
	          ng-model="updateOrderPaymentCtrl.orderDetails.fee" />
	    </lw-form-field>
	    <!-- /fee -->

	     <!-- comment -->
		<div>
	        <lw-form-field field-for="comment" label="<?= __('Comment') ?>"> 
	            <textarea name="comment" class="lw-form-field form-control" ng-required="true" 
	            cols="10" rows="3" ng-minlength="6" ng-model="updateOrderPaymentCtrl.orderDetails.comment"></textarea>
	        </lw-form-field>
		</div>
		<!-- /comment -->
		
		<div class="lw-dotted-line"></div>

		<div class="lw-form-actions">
		 	<!-- update button -->
	        <button type="submit" ng-click="updateOrderPaymentCtrl.update()" class="lw-btn btn btn-primary lw-btn-process" title="<?= __('Update') ?>">
	        <?= __('Update') ?></button>
			<!-- /update button -->

			<!-- cancel button -->
	        <button type="button" ng-click="updateOrderPaymentCtrl.closeDialog()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= ( __('Cancel') ) ?></button>
	        <!-- /cancel button -->
	    </div>
	</form>

</div>