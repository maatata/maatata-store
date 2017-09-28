<!-- 
    View        : payment-list 
    Component   : Order
    Engine      : ManagePaymentEngine 
    Controller  : PaymentListController 
    ----------------------------------------------- -->

<div ng-controller="PaymentListController as paymentListCtrl">
	
    	<div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading">
				<span>
		        	<?= __( 'Manage Payments' ) ?>
		        </span>
	        </h3>
	        <!-- /main heading -->
	    </div>

    <div class="clearfix">

		<!-- form section -->
		<form class="lw-form lw-ng-form lw-ng-form" 
			name="paymentListCtrl.[[ paymentListCtrl.ngFormName ]]" 
			novalidate>
			
			<div class="col-lg-2">
			    <!-- duration -->
		         <lw-form-field field-for="duration" label="<?= __( 'Duration' ) ?>" advance="true">
		               <select class="lw-form-field form-control" 
		                name="duration" ng-model="paymentListCtrl.paymentData.duration" ng-options="type.id as type.name for type in paymentListCtrl.paymentDuration" ng-required="true" ng-change="paymentListCtrl.durationChange(paymentListCtrl.paymentData.duration)">
		            </select>  
		        </lw-form-field>
		        <!-- /duration -->
	        </div>
			
			<div class="col-lg-2">
		        <!-- Start Date -->
				<lw-form-field field-for="start" label="<?= __( 'Start Date' ) ?>"> 
					<input type="text" 
							class="lw-form-field form-control lw-readonly-control"
							name="start"
							id="start"
							lw-bootstrap-md-datetimepicker
							ng-required="true" 
							ng-change="paymentListCtrl.endDateUpdated(paymentListCtrl.paymentData.start)"
							options="[[ paymentListCtrl.startDateConfig ]]"
							readonly
							ng-model="paymentListCtrl.paymentData.start" 
						/>
				</lw-form-field>
				<!-- /Start Date -->
			</div>
		
			<div class="col-lg-2">
				<!-- end Date -->
				<lw-form-field field-for="end" label="<?= __( 'End Date' ) ?>"> 
					<input type="text" 
							class="lw-form-field form-control lw-readonly-control"
							name="end"
							id="end"
							lw-bootstrap-md-datetimepicker
							ng-change="paymentListCtrl.endDateUpdated(paymentListCtrl.paymentData.end)"
							options="[[ paymentListCtrl.endDateConfig ]]"
							ng-required="true" 
							readonly
							ng-model="paymentListCtrl.paymentData.end" 
						/>
				</lw-form-field>
				<!-- /end Date -->
			</div>
			
			<div class="col-lg-3 lw-show-btn">
				<!-- show data button -->
				<a ng-click="paymentListCtrl.getPaymentList()"   class="lw-btn btn btn-primary btn-sm" title="<?= __('Show') ?>"><?= __('Show') ?></a>
				<!-- show data button -->
				
				<!-- generate Excel sheet button -->
				<span ng-if="paymentListCtrl.tableStatus != ''">
					<a ng-href="[[ paymentListCtrl.excelDownloadURL ]]" target="_self" class="lw-btn btn btn-default btn-sm" ng-show="reportCtrl.tableStatus != ''" title="<?= __('Generate Excel file') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __(' Generate Excel file') ?></a>
				</span>
				<!-- generate Excel sheet button -->
			</div>	
		</form>
	</div>

	    <!-- datatable container -->
	    <div>
	        <!-- datatable -->
	        <table class="table table-striped table-bordered" id="managePaymentList" cellspacing="0" width="100%">
	            <thead class="page-header">
	                <tr>
	                    <th><?=  __('Order ID')  ?></th>
	                    <th><?=  __('Transaction ID')  ?></th>
	                    <th><?=  __('Fee')  ?></th>
	                    <th><?=  __('Created On')  ?></th>
	                    <th><?=  __('Payment Method')  ?></th>
	                    <th><?=  __('Total Amount')  ?></th>
	                </tr>
	            </thead>
	            <tbody></tbody>
	        </table>
	        <!-- /datatable -->
	    </div>
	    <!-- /datatable container -->
	<div ui-view></div>
<div>

<!-- Order UID column template -->
<script type="text/template" id="orderPaymentColumnUIDTemplate">

    <span class="custom-page-in-menu"><a href ng-click="paymentListCtrl.orderDetailsDialog(<%- __tData._id %>)"><%- __tData.order_uid %></a></span>
</script>
<!-- Order UID column template -->

<!-- Order UID column template -->
<script type="text/template" id="orderPaymentTransactionIDTemplate">

    <span class="custom-page-in-menu"><a href ng-click="paymentListCtrl.paymentDetailsDialog(<%- __tData.order_payment_id %>)"><%- __tData.txn %></a></span>
</script>
<!-- Order UID column template -->

<!-- order payment fee column -->
<script type="text/template" id="orderPaymentFeeTemplate">

    <span class="lw-datatable-price-align"><%- __tData.formattedFee %></span>
</script>
<!-- order payment fee column -->

<!-- Order refund and payment total amount column -->
<script type="text/template" id="orderPaymentTotalAmountTemplate">

	<% if (__tData.type == 2) { %> <!-- Refund -->
		<span class="lw-danger"><%- __tData.totalAmount %></span>
		<span class="label label-danger"><?= __('Refunded')  ?></span>
	<% } else { %>
	<%- __tData.totalAmount %>
	<% } %>
</script>