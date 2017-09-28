<!--     
    View        : Report List 
    Component   : Report
    Engine      : ReportEngine 
    Controller  : ReportController
---------------------------------------------------------------------------  -->
<div ng-controller="ReportController as reportCtrl">

	<div class="lw-section-heading-block">
	    <!-- main heading -->
	    <h3 class="lw-section-heading">
			<span>
	        	<?= __( 'Reports' ) ?>
	        </span>
	    </h3>
	    <!-- /main heading -->
	</div>

	<div class="clearfix">
		<!-- form section -->
		<form class="lw-form lw-ng-form lw-ng-form" 
			name="reportCtrl.[[ reportCtrl.ngFormName ]]" 
			novalidate>

			<div class="col-lg-2">
			    <!-- duration -->
		         <lw-form-field field-for="duration" label="<?= __( 'Duration' ) ?>" advance="true">
		               <select class="lw-form-field form-control" 
		                name="duration" ng-model="reportCtrl.duration" ng-options="type.id as type.name for type in reportCtrl.reportDuration" ng-required="true" ng-change="reportCtrl.durationChange(reportCtrl.duration)">
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
							ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.start)"
							options="[[ reportCtrl.startDateConfig ]]"
							readonly
							ng-model="reportCtrl.reportData.start" 
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
							ng-change="reportCtrl.endDateUpdated(reportCtrl.reportData.end)"
							options="[[ reportCtrl.endDateConfig ]]"
							ng-required="true" 
							readonly
							ng-model="reportCtrl.reportData.end" 
						/>
				</lw-form-field>
				<!-- /end Date -->
			</div>
				
			
		    <!-- status -->	
	        <div class="col-lg-2"> 
				<lw-form-field field-for="status" label="<?= __( 'Status' ) ?>"> 
	               <select class="lw-form-field form-control" 
	                    name="status" ng-model="reportCtrl.reportData.status" ng-options="type.id as type.name for type in reportCtrl.statuses" ng-required="true" ng-change="reportCtrl.statusChange(reportCtrl.reportData.status)">
	                </select> 
	            </lw-form-field>
	        </div>
	        <!-- /status-->
			
	        <!-- order -->	
	        <div class="col-lg-2"> 
				<lw-form-field field-for="order" label="<?= __( 'Order' ) ?>"> 
	               <select class="lw-form-field form-control" 
	                    name="order" ng-model="reportCtrl.reportData.order" ng-options="type.id as type.name for type in reportCtrl.orderList" ng-required="true">
	                </select> 
	            </lw-form-field>
	        </div>
	        <!-- /order-->

			<!-- show button for show order-->
			<div class="col-lg-3 lw-show-btn">
				 <a type="submit" ng-click="reportCtrl.getReports()"   class="lw-btn btn btn-primary btn-sm" title="<?= __('Show') ?>"><?= __('Show') ?></a>
				 
				 <a ng-href="[[ reportCtrl.excelDownloadURL ]]" target="_self" class="lw-btn btn btn-default btn-sm" ng-if="reportCtrl.tableStatus != ''" title="<?= __('Generate Excel file') ?>"><i class="fa fa-file-excel-o" aria-hidden="true"></i> <?= __(' Generate Excel file') ?></a>
			</div>
			<!-- /show button for show order-->

		</form>
		<!-- / form section -->
	</div><br>
	
	<!-- table for total amount by currency -->
	<div class="panel panel-default" ng-if="reportCtrl.totalAmounts != ''">
		<div class="panel-heading">
			<strong>
				<?=  __('Total order Payments ')  ?>
			</strong>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered" border="1">
				<thead>
					<tr>
						<!-- Currency Code and Order Amount -->
						<th><?=  __('Currency')  ?></th>
						<th class="lw-text-right"><?=  __('Credit Amount')  ?></th>
						<th class="lw-text-right"><?=  __('Debit Amount')  ?></th>
						<th class="lw-text-right"><?=  __('Difference Amount')  ?></th>
						<!-- /Currency Code and Order Amount -->
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="amountDetail in reportCtrl.totalAmounts">
						<!-- Currency Code and Order Amount -->
						<td ng-bind="amountDetail.currencyCode"></td>
						<td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.credit < 0}" ng-bind="amountDetail.formattedCredit"></td>
						<td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.debit < 0}" ng-bind="amountDetail.formattedDebit"></td>
						<td class="lw-amount-td" ng-class="{'lw-danger' : amountDetail.total < 0}" ng-bind="amountDetail.formattedTotal"></td>
						<!-- /Currency Code and Order Amount -->
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<!-- /table for total amount by currency -->
	
	<div class="lw-section-heading-block" ng-if="reportCtrl.tableStatus != ''">
	    <!-- main heading -->
	    <h4 class="lw-section-heading">
			<span>
	        	<?= __( 'Orders' ) ?>
	        </span>
	    </h4>
	    <!-- /main heading -->
	</div>

	<!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="manageReportList" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                	<th><?=  __('Order ID')  ?></th>
                    <th><?=  __('Name')  ?></th>
                    <th><?=  __('Status')  ?></th>
                    <th><?=  __('Placed On')  ?></th>
                    <th class="text-right"><?=  __('Total')  ?></th>
                    <th><?=  __('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
    </div>
    <!-- /datatable container -->
	<div ui-view></div>

	

</div>

<!-- categories list row Action column _template order UID column -->
<script type="text/template" id="orderColumnIdTemplate">
    <span class="custom-page-in-menu"><%- __tData.order_uid %></span>
</script>
<!-- order UID column -->

<!-- userNameColumnIdTemplate -->
<script type="text/template" id="userNameColumnIdTemplate">
    <span class="custom-page-in-menu"><%- __tData.formated_name %></span>
</script>
<!-- userNameColumnIdTemplate -->

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

<!-- order total amount column template -->
<script type="text/template" id="orderColumnTotalAmountTemplate">
    <span class="lw-datatable-price-align"><%- __tData.totalAmount %></span>
</script>
<!-- order total amount column template -->

<!-- orderActionColumnTemplate -->
<script type="text/_template" id="orderActionColumnTemplate">
	
	<a title="<?= __('View Details') ?>" class="btn btn-default btn-xs" ng-click="reportCtrl.orderDetailsDialog(<%- __tData._id %>)"><i class="fa fa-info"></i> <?= __('View Details') ?></a>
	

	<% if (__tData.payment_status == 2) { %> <!-- 2 = Completed -->
	<a title="<?= __('Download Invoice') ?>" href="<%- __tData.pfdDownloadURL %>" class="btn btn-default btn-xs" href="" ><i class="fa fa-download" aria-hidden="true"></i> <?= __('Download Invoice') ?></a>
	<% } %>
</script>
<!-- /orderActionColumnTemplate -->

