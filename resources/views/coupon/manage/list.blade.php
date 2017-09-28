<div ng-controller="CouponListController as couponListCtrl">
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
			<span>
	        	<?= __( 'Manage Coupons' ) ?>
	        </span>
        </h3>
        <!-- /main heading -->

        <!-- action button -->
        <div class="lw-section-right-content">
            <button class="lw-btn btn btn-sm btn-default pull-right" title="<?= __( 'Add New Coupon' ) ?>" ng-click="couponListCtrl.openCouponDialog()"><i class="fa fa-plus"></i> <?= __( 'Add New Coupon' ) ?></button>
        </div>
        <!-- action button -->
    </div>

    <div>
    	<!-- Coupons for Tabs -->
	    <ul class="nav nav-tabs lw-tabs" role="tablist" id="manageCouponList">
	        <li role="presentation" ui-sref-active="active" class="active">
	            <a href="#current" ng-click="couponListCtrl.tabClick($event,'coupons.current')" aria-controls="current" role="tab" data-toggle="tab" title="<?=  __('Current')  ?>"><?=  __('Current')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active">
	            <a href="#expired" ng-click="couponListCtrl.tabClick($event,'coupons.expired')" aria-controls="expired" role="tab" data-toggle="tab" title="<?=  __('Expired')  ?>"><?=  __('Expired')  ?></a>
	        </li>
	        <li role="presentation" ui-sref-active="active">
	            <a href="#upcoming" ng-click="couponListCtrl.tabClick($event,'coupons.upcoming')" aria-controls="upcoming" role="tab" title="<?=  __('Up-coming')  ?>" data-toggle="tab"><?=  __('Up-coming')  ?></a>
	        </li>
	    </ul><br>
	    <!-- /Coupons Tabs -->
		<div class="tab-content">
		    <!-- current Tab -->
	        <div role="tabpanel" class="tab-pane fade in active" id="current">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="currentCoupon" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __('Title')  ?></th>
			                    <th><?=  __('Code')  ?></th>
			                    <th><?=  __('Start Date')  ?></th>
			                    <th><?=  __('End Date')  ?></th>
			                    <th><?=  __('status')  ?></th>
			                    <th><?=  __('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /current Tab -->

	        <!-- expired Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="expired">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="expiredCoupon" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __('Title')  ?></th>
			                    <th><?=  __('Code')  ?></th>
			                    <th><?=  __('Start Date')  ?></th>
			                    <th><?=  __('End Date')  ?></th>
			                    <th><?=  __('status')  ?></th>
			                    <th><?=  __('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /expired Tab -->

	        <!-- upcoming Tab -->
	        <div role="tabpanel" class="tab-pane fade" id="upcoming">
				<!-- datatable -->
		        <div class="table-responsive">
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="upcomingCoupon" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __('Title')  ?></th>
			                    <th><?=  __('Code')  ?></th>
			                    <th><?=  __('Start Date')  ?></th>
			                    <th><?=  __('End Date')  ?></th>
			                    <th><?=  __('status')  ?></th>
			                    <th><?=  __('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
	 		</div>
	        <!-- /upcoming Tab -->
	    </div>
    </div>

    <!-- place on date column _template -->
<script type="text/template" id="titleColumnTemplate">

   <span class="custom-page-in-menu"><a href ng-click="couponListCtrl.detailDialog('<%-__tData._id %>')" title="<%-__tData.title %>"><%-__tData.title %></a></span>
   
</script>
<!-- /place on date column _template -->

<!-- place on date column _template -->
<script type="text/template" id="startDateColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.start_date %></span>
   
</script>
<!-- /place on date column _template -->

<!-- place on date column _template -->
<script type="text/template" id="endDateColumnTemplate">

	<span class="custom-page-in-menu"><%-__tData.end_date %></span>

</script>
<!-- /place on date column _template -->

<!--  list row status column  _template -->
<script type="text/template" id="statusColumnTemplate">
 	<% if (__tData.status === 1) { %> 
        <span title="<?= __( 'Active' ) ?>"><i class="fa fa-eye"></i></span>
   <% } else { %>
    <span title="<?= __( 'Inactive' ) ?>"><i class="fa fa-eye-slash"></i></span>
   <% } %>
</script>
<!--  list row status column  _template -->

<!-- list row action column  _template -->
<script type="text/template" id="columnActionTemplate">
 	<a href ng-click="couponListCtrl.openEditCouponDialog('<%-__tData._id %>')" class="btn btn-default btn-xs" title="<?= __( 'Edit' ) ?>">
 		<i class="fa fa-pencil-square-o"></i> <?= __( 'Edit' ) ?>
 	</a>
 	<a href="" ng-click="couponListCtrl.delete('<%-__tData._id %>', '<%- escape(__tData.title) %>')" class="btn btn-danger btn-xs" title="<?= __( 'Delete' ) ?>">
 		<i class="fa fa-trash-o fa-lg"></i> <?= __( 'Delete' ) ?>
 	</a>
</script>
<!-- list row action column  _template -->
</div>
