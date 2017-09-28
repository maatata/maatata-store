<div class="lw-dialog" ng-controller="ManageRawDataController as rawDataCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Raw Data' )  ?></h3>
    </div>
    <!-- main heading -->
	
	<div class="panel panel-default table-responsive" ng-if="rawDataCtrl.rawData != null">
		
		<!-- raw data table -->
		<table class="table table-bordered" border="1">
			<tbody>
				<tr ng-repeat="(key, value) in rawDataCtrl.rawData">
					<th ng-bind="key"></th>
					<td ng-bind="value"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<!--  show message when no raw data found  -->
    <div class="alert alert-info" ng-if="rawDataCtrl.rawData == null">
        <?= __('There are no data found') ?>
    </div>
   	<!--  /show message when no raw data found  -->
	<div class="lw-dotted-line"></div>
	<!-- close dialog -->
	<button type="button" ng-click="rawDataCtrl.closeDialog()" class="lw-btn btn btn-default" title="<?= __('Close') ?>"><?= __('Close') ?></button>
	<!-- close dialog -->
</div>