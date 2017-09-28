<div class="lw-dialog" ng-controller="ManageRefundOrderPaymentController as refundOrderPaymentCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Refund Order Payment' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="refundOrderPaymentCtrl.[[ refundOrderPaymentCtrl.ngFormName ]]" 
        novalidate>
		
		<fieldset class="lw-fieldset-2">
			<legend>
				<?=  __('Order Payment Details')  ?> 
			</legend>
			<!-- order detail table -->
			<div class="panel panel-default table-responsive">
				<table class="table table-bordered" border="1">
					<tbody>
						<tr>
							<!-- order UID -->
							<th><?=  __('Order ID')  ?></th>
							<td ng-bind="refundOrderPaymentCtrl.paymentDetails.orderUID"></td>
							<!-- /order UID -->
						</tr>
						<tr>
							<!-- transaction ID -->
							<th><?=  __('Transaction ID')  ?></th>
							<td ng-bind="refundOrderPaymentCtrl.paymentDetails.txn"></td>
							<!-- transaction ID -->
						</tr>
						<tr>
							<!-- Fee -->
							<th><?=  __('Fee')  ?></th>
							<td ng-bind="refundOrderPaymentCtrl.paymentDetails.fee"></td>
							<!-- /Fee -->
						</tr>
						<tr>
							<!-- Total Amount -->
							<th><?=  __('Total Amount')  ?></th>
							<td ng-bind="refundOrderPaymentCtrl.paymentDetails.grossAmount"></td>
							<!-- /Total Amount -->
						</tr>
						<tr>
							<!-- Payment On -->
							<th><?=  __('Payment On')  ?></th>
							<td ng-bind="refundOrderPaymentCtrl.paymentDetails.paymentOn"></td>
							<!-- /Payment On -->
						</tr>
					</tbody>
				</table>
			</div>
			<!-- /order detail table -->
		</fieldset>

		<fieldset class="lw-fieldset-2">
			<legend>
				<?=  __('Refund Payment Options')  ?> 
			</legend>

			<!-- payment method -->
		    <div> 
		        <lw-form-field field-for="paymentMethod" label="<?= __( 'Payment Method' ) ?>"> 
		           <select class="form-control" 
		                name="paymentMethod" ng-model="refundOrderPaymentCtrl.paymentDetails.paymentMethod" ng-options="type.id as type.name for type in refundOrderPaymentCtrl.paymentMethodList" ng-required="true">
		                <option value='' disabled selected><?=  __('-- Select Payment Method --')  ?></option>
		            </select> 
		        </lw-form-field>
		    </div>
		    <!-- /payment method-->

		    <!-- Checkout Options -->
	        <div class="form-group checkbox">
	            <label>
	                <input type="checkbox" 
	                    class="lw-form-field"
	                    name="checkMail"
	                    ng-model="refundOrderPaymentCtrl.paymentDetails.checkMail" /> </span> <?= __('Notify Customer') ?>
	            </label>
	        </div>
	        <!-- /Checkout Options -->

	        <!-- Description -->
			<div>
		        <lw-form-field field-for="description" label="<?= __('Additional Notes') ?>"> 
		            <textarea name="description" class="lw-form-field form-control"
		             cols="10" rows="3" ng-model="refundOrderPaymentCtrl.paymentDetails.description"></textarea>
		        </lw-form-field>
			</div>
			<!-- Description -->

		    <!-- Action -->
	        <div class="lw-form-actions">
	        	<!-- Submit Button -->
	            <button type="submit" ng-click="refundOrderPaymentCtrl.update()" class="lw-btn btn btn-primary lw-btn-process" title="<?= __('Update') ?>"><?= __('Update') ?> </button>
	            <!-- /Submit Button -->
				
				<!-- Cancel Button -->
	            <button type="button" ng-click="refundOrderPaymentCtrl.closeDialog()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
	            <!-- /Cancel Button -->
	        </div>
			<!-- /Action -->

		</fieldset>

    </form>

</div>