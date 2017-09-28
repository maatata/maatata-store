<div ng-controller="AddressListController as addressListCtrl">
	<!--  form action  -->
	<form class="lw-form lw-ng-form"
        name="addressListCtrl.[[ addressListCtrl.ngFormName ]]"
        novalidate> 
        <div class="lw-section-heading-block">
	        <!--  main heading  -->
	        <h3 class="lw-section-heading">@section('page-title',  __( 'Addresses' )) <?= __( 'Addresses' ) ?></h3>
	        <!--  /main heading  -->
	    </div>

	    <!--  add address button  -->
	    <div class="">
	    	<a ng-click="addressListCtrl.addAddressDialog()" title="<?= __('Add New Address') ?>" class="pull-right btn btn-default btn-sm"><?= __('Add New Address') ?></a> <br><br>
	    </div>
	    <!--  /add address button  -->

		<!--  panel for address list  -->
		<div class="panel panel-default" ng-hide="addressListCtrl.addressData.addresses.length === 0" ng-if="addressListCtrl.pageStatus == true">
			<!--  panel heading  -->
			<div class="panel-heading" >
				<h3 class="panel-title"><?= __( 'Addresses' ) ?></h3>
			</div>
			<!--  /panel heading  -->
			<div class="list-group-item" id="address_[[address.id]]" ng-repeat="address in addressListCtrl.addressData.addresses track by address.id">
				
				<!--  primary label  -->
				<span class="label label-primary pull-right" ng-if="address.primary == 1">
					<?= __( 'Primary' ) ?>
				</span>
				<!--  /primary label  -->
				
				<!--  address  -->
				<address class="lw-address">
                    <strong>
                    	<span ng-bind="address.type"></span>
                    </strong><br>
					<span ng-bind="address.address_line_1"></span><br>
	                <span ng-bind="address.address_line_2"></span><br>
	                <span ng-bind="address.city"></span>,
	                <span ng-bind="address.state"></span>,
	                <span ng-bind="address.country"></span><br>
					<?= __( 'Pin Code' ) ?> : <span ng-bind="address.pin_code"></span>
				</address>
				<!--  /address  -->
				
                <!--  edit Action button  -->
	            <a ng-click="addressListCtrl.editAddressDialog(address.id)" title="<?= __('Edit') ?>" class="btn btn-default btn-xs"><i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a> 
	            <!--  / edit Action button  -->

	            <!--  delete Action button  -->
	            <a href ng-click="addressListCtrl.delete(address.id)" title="<?= __('Delete') ?>" class="btn btn-danger btn-xs delete-sw"><i class="fa fa-trash-o fa-lg"></i> <?= __('Delete')  ?></a>
                <!--  / delete Action button  -->
            </div>
		</div>

		<!--  no addresses found message  -->
        <div class="alert alert-info" ng-show="addressListCtrl.addressData.addresses.length === 0">
            <?= __('There are no addresses found.') ?>
        </div>
       	<!--  /no addresses found message  -->
       	
    </form>
	<!--  /form action  -->

</div>