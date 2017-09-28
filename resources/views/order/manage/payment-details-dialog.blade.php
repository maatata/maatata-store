<div class="lw-dialog" ng-controller="ManagePaymentDetailsController as paymentdetailCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Payment Details' )  ?></h3>
    </div>
    <!-- main heading -->
	
	<div class="panel panel-default table-responsive">
	
	  <!-- payment detail table -->
	  <table class="table table-bordered" border="1">
		<tbody>
	  		<tr>
	  			<th><?=  __('Transaction ID')  ?></th>
	  			<td>
	  				<a href ng-bind="paymentdetailCtrl.paymentDetail.txn" ng-bind="paymentdetailCtrl.paymentDetail.txn" ng-click="paymentdetailCtrl.rawDataDialog(paymentdetailCtrl.paymentDetail.rawData)">
	  			</a>
	  			</td>
	  		</tr>
	  		<tr>
	  			<th><?=  __('Fee')  ?></th>
	  			<td ng-bind="paymentdetailCtrl.paymentDetail.fee"></td>
	  		</tr>
  			<tr>
  				<th><?=  __('Gross Amount')  ?></th>
  				<td ng-bind="paymentdetailCtrl.paymentDetail.grossAmount"></td>
  			</tr>
  			<tr>
  				<th><?=  __('Method')  ?></th>
  				<td ng-bind="paymentdetailCtrl.paymentDetail.paymentMethod"></td>
  			</tr>
  			<tr>
  				<th><?=  __('Payment On')  ?></th>
  				<td ng-bind="paymentdetailCtrl.paymentDetail.formatedPaymentOn"></td>
  			</tr>
	  	</tbody>
	  </table>
	  <!-- payment detail table -->
	</div>
	<div class="lw-dotted-line"></div> 	
	<!-- close dialog -->
	<button type="button" ng-click="paymentdetailCtrl.closeDialog()" class="lw-btn btn btn-default pull-right" title="<?= __('Close') ?>"><?= __('Close') ?></button>
	<!-- close dialog -->

</div>