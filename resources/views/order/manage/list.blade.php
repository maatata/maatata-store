<div ng-controller="ManageOrderListController as orderListCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <div class="lw-section-heading">
		    <span ng-if="orderListCtrl.userFullName == ' '">
		    	<h3><span><?= __( 'Manage Orders' ) ?></span> </h3>
		    </span>
		    <span ng-if="orderListCtrl.userFullName != ' '">
		    	<h3><span ng-bind="orderListCtrl.manageOrdersTitle"></span></h3>
		    </span>
        </div>
        
        <!-- /main heading -->
    </div>
	<!--  admin tabs section -->
	<div>
		
	    <!-- Order for admin Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="adminOrderList">
	        <li role="presentation" ui-sref-active="active" class="active">
	            <a href="#active" ng-click="orderListCtrl.goToActiveTab($event,'orders.active')" aria-controls="active" role="tab" data-toggle="tab" title="<?=  __('Active')  ?>"><?=  __('Active')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active">
	            <a href="#cancelled" ng-click="orderListCtrl.goToCancelledTab($event,'orders.cancelled')" aria-controls="cancelled" role="tab" data-toggle="tab" title="<?=  __('Cancelled')  ?>"><?=  __('Cancelled')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active">
	            <a href="#completed" ng-click="orderListCtrl.goToCompletedTab($event,'orders.completed')" aria-controls="completed" role="tab" title="<?=  __('Completed')  ?>" data-toggle="tab"><?=  __('Completed')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Order admin Tabs -->

		<div class="tab-content">
	    	<!-- Active Tab -->
	        <div role="tabpanel" class="tab-pane fade in active" id="active">
				<!-- datatable -->
		        <table id="activeTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Name')  ?></th>
		                    <th><?=  __('Order Status')  ?></th>
		                    <th><?=  __('Payment Status')  ?></th>
		                    <th><?=  __('Payment Method')  ?></th>
		                    <th><?=  __('Placed On')  ?></th>
		                    <th><?=  __('Amount')  ?></th>
		                    <th><?=  __('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Active Tab -->
	        <!-- Cancelled Tab -->
	        <div role="tabpanel" class="tab-pane fade table-reponsive" id="cancelled">
				<!-- datatable -->
		        <table id="cancelledTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Name')  ?></th>
		                    <th><?=  __('Order Status')  ?></th>
		                    <th><?=  __('Payment Status')  ?></th>
		                    <th><?=  __('Payment Method')  ?></th>
		                    <th><?=  __('Canceled On')  ?></th>
		                    <th><?=  __('Amount')  ?></th>
		                    <th><?=  __('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Cancelled Tab -->
	        <!-- Completed Tab -->
	        <div role="tabpanel" class="tab-pane fade table-reponsive" id="completed">

				<!-- datatable -->
		        <table id="completedTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Name')  ?></th>
		                    <th><?=  __('Order Status')  ?></th>
		                    <th><?=  __('Payment Status')  ?></th>
		                     <th><?=  __('Payment Method')  ?></th>
		                    <th><?=  __('Completed On')  ?></th>
		                    <th><?=  __('Amount')  ?></th>
		                    <th><?=  __('Order Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	        <!-- /Completed Tab -->
	        </div>
		</div>
	</div>
	<!--  /admin tabs section -->

</div>

<!-- orderColumnIdTemplate -->
	<script type="text/template" id="orderColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
	</script>
<!-- orderColumnIdTemplate -->

<!-- orderColumnTotalAmountTemplate -->
	<script type="text/template" id="orderColumnTotalAmountTemplate">
	    <span class="lw-datatable-price-align"><%- __tData.totalAmount %></span>
	</script>
<!-- orderColumnTotalAmountTemplate -->

<!-- orderPaymentStatusColumnIdTemplate -->
	<script type="text/template" id="paymentActionColumnTemplate">
		
		<!-- Payment Status -->
		<% if(__tData.payment_status == 2) {%> <!-- 4(Pending) -->
		<a title="<?= __('Show Payment Details') ?>" 
			ng-click="orderListCtrl.paymentDetailsDialog(<%- __tData.orderPaymentID %>)" 
			href="" ><%- __tData.paymentStatus %> </a>
		<% } else { %>
			<%- __tData.paymentStatus %>
		<% } %>
		<!-- /Payment Status -->
		
		<!-- Update Payment Button -->
	    <% if(__tData.payment_status != 2&&__tData.status != 3&&__tData.payment_method != 1) { %>

	    <a title="<?= __('Update Payment') ?>" 
			ng-click="orderListCtrl.updatePaymentDetailsDialog(<%- __tData._id %>)" 

			class="btn btn-success btn-xs" href="" ><?= __('Update Payment') ?></a>

		<% } %>
		<!-- /Update Payment Button -->

	</script>
<!-- orderPaymentStatusColumnIdTemplate -->

<!-- userStatusColumnIdTemplate -->
	<script type="text/template" id="orderStatusColumnIdTemplate">
	
		<%- __tData.formated_status %>
		
		<!-- Update Button -->
		<% if (__tData.status !== 3&&__tData.status !== 6) { %> 
	    	<a title="<?= __('Update') ?>" ng-click="orderListCtrl.updateDialog('<%- __tData._id %>','<%- __tData.order_uid %>')" class="btn btn-warning btn-xs" href="" ><i class="fa fa-pencil-square-o"></i> <?= __('Update') ?></a>
	    <% } %>
	    <!-- /Update Button -->

	</script>
	<!-- userStatusColumnIdTemplate -->

	<!-- userTypeColumnIdTemplate -->
	<script type="text/template" id="orderPaymentMethodColumnIdTemplate">

		<%- __tData.paymentMethod %> 

		<!-- Cancelled order & payment Completed then show this btn -->
		<% if (__tData.status == 3&&__tData.payment_status == 2) { %> 
			
			<!-- Update Refund button -->
			<a title="<?= __('Refund') ?>" ng-click="orderListCtrl.refundPaymentDialog('<%- __tData._id %>', '<%- __tData.order_uid %>')" class="btn btn-success btn-xs" href="" ><?= __('Refund') ?></a>
			<!-- /Update Refund button -->

		<% } %>
		<!-- /Cancelled order & payment Completed then show this btn -->
		
	</script>
	<!-- userTypeColumnIdTemplate -->

	<!-- userNameColumnIdTemplate -->
	<script type="text/template" id="userNameColumnIdTemplate">
	  <span title="<?= __('name') ?>" class="tch-name word-wrap"><%- __tData.formated_name %></span>
	</script>
	<!-- userNameColumnIdTemplate -->

	<!-- orderColumnTimeTemplate -->
	<script type="text/template" id="orderColumnTimeTemplate">
	    <span class="custom-page-in-menu"><%- __tData.creation_date %></span>
	</script>
	<!-- orderColumnTimeTemplate -->

	<!-- orderActionColumnTemplate -->
	<script type="text/_template" id="orderActionColumnTemplate">

	<!-- View Details button -->
	<a title="<?= __('View Details') ?>" ng-click="orderListCtrl.orderDetailsDialog(<%- __tData._id %>)" class="btn btn-default btn-xs" href="" ><i class="fa fa-info"></i> <?= __('View Details') ?></a>
	<!-- /View Details button -->
	
	<!-- Order Log button -->
    <a title="<?= __('Order Log') ?>" ng-click="orderListCtrl.logDetailsDialog(<%- __tData._id %>)" class="btn btn-default btn-xs" href="" ><i class="fa fa-info"></i> <?= __('Order Log') ?></a>
    <!-- Order Log button -->
    
    <!-- Contact User button -->
    <a title="<?= __('Contact User') ?>" ng-click="orderListCtrl.contactUserDialog(<%- __tData._id %>)" class="btn btn-primary btn-xs" href="" ><i class="fa fa-envelope" aria-hidden="true"></i> <?= __('Contact User') ?></a>
    <!-- Contact User button -->
	
	<!-- Download Invoice -->
	<% if (__tData.payment_status == 2) { %> <!-- 2 = Completed -->
    	<a title="<?= __('Download Invoice') ?>" href="<%- __tData.pfdDownloadURL %>" class="btn btn-default btn-xs" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __('Download Invoice') ?></a>
    <% } %>
    <!-- Download Invoice -->

	</script>
	<!-- /orderActionColumnTemplate -->

