<div ng-controller="MyOrderListController as MyOrderListCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?=  __( 'Orders' ) ?>
        	@section('page-title') 
	        	<?= 'Orders' ?>
	        @endsection
	    </h3>
        <!-- /main heading -->
    </div>

	<!--  users tabs section -->
	<div>
	    <!-- Order for user Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="adminOrderList">
	        <li role="presentation" class="active">
	            <a href="#active" lwOnLoadClicker aria-controls="active" role="tab" data-toggle="tab" title="<?=  __('Active')  ?>"><?=  __('Active')  ?></a>
	        </li>
	        <li role="presentation">
	            <a href="#cancelled" lwOnLoadClicker aria-controls="cancelled" role="tab" data-toggle="tab" title="<?=  __('Cancelled')  ?>"><?=  __('Cancelled')  ?></a>
	        </li>
	        <li role="presentation">
	            <a href="#completed" lwOnLoadClicker aria-controls="completed" role="tab" title="<?=  __('Completed')  ?>" data-toggle="tab"><?=  __('Completed')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Order user Tabs -->

		<div class="tab-content">
	    	<!-- Active Tab -->
	        <div role="tabpanel" class="tab-pane fade in active" id="active">
				<!-- datatable -->
		        <table id="activeTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Status')  ?></th>
		                    <th><?=  __('Placed On')  ?></th>
		                    <th><?=  __('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Active Tab -->
	        <!-- Cancelled Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="cancelled">
				<!-- datatable -->
		        <table id="cancelledTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Status')  ?></th>
		                    <th><?=  __('Canceled On')  ?></th>
		                    <th><?=  __('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	 		</div>
	        <!-- /Cancelled Tab -->
	        <!-- Completed Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="completed">

				<!-- datatable -->
		        <table id="completedTabList" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead class="page-header">
		                <tr>
		                    <th><?=  __('Order ID')  ?></th>
		                    <th><?=  __('Status')  ?></th>
		                    <th><?=  __('Completed On')  ?></th>
		                    <th><?=  __('Action')  ?></th>
		                </tr>
		            </thead>
		            <tbody></tbody>
		        </table>
		        <!-- datatable -->
	        <!-- /Completed Tab -->
	        </div>
		</div>
	</div>
	<!--  /users tabs section -->

	<!-- orderColumnIdTemplate -->
	<script type="text/template" id="orderColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
	</script>
	<!-- orderColumnIdTemplate -->

	<!-- userStatusColumnIdTemplate -->
	<script type="text/template" id="orderStatusColumnIdTemplate">
	    <span class="custom-page-in-menu"><%- __tData.formated_status %></span>
	</script>
	<!-- userStatusColumnIdTemplate -->

	<!-- orderColumnTimeTemplate -->
	<script type="text/template" id="orderColumnTimeTemplate">
	    <span class="custom-page-in-menu"><%- __tData.creation_date %></span>
	</script>
	<!-- orderColumnTimeTemplate -->

	<!-- orderActionColumnTemplate -->
	<script type="text/_template" id="orderActionColumnTemplate">
			 
		
	    <a title="<?= __('View Details') ?>" class="btn btn-default btn-xs" href="<%= __tData.get_order_details_Route %>"><i class="fa fa-info"></i> <?= __('View Details') ?></a>
		
		<!-- 2 = (Completed) -->
		<% if (__tData.payment_status == 2 && __tData.status == 6) { %>
        <a title="<?= __('Download Invoice') ?>" href="<%- __tData.invoiceDownloadURL %>" class="btn btn-default btn-xs" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __('Download Invoice') ?></a>
        <% } %>
	</script>
	<!-- /orderActionColumnTemplate -->

</div>