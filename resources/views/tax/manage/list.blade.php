<div ng-controller="TaxListController as taxListCtrl">
    <div>
    	<div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading">
				<span>
		        	<?= __( 'Manage Taxes' ) ?>
		        </span>
	        </h3>
	        <!-- /main heading -->

	        <!-- button -->
	        <div class="lw-section-right-content">
	            <button class="lw-btn btn btn-sm btn-default pull-right" title="<?= __( 'Add New Tax' ) ?>" ui-sref="taxes.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Tax' ) ?></button>
	        </div>
	        <!-- /button -->
	    </div>

	    <!-- datatable container -->
	    <div>
	        <!-- datatable -->
	        <table class="table table-striped table-bordered" id="manageTaxList" cellspacing="0" width="100%">
	            <thead class="page-header">
	                <tr>
	                    <th><?=  __('Label')  ?></th>
	                    <th><?=  __('Country')  ?></th>
	                    <th><?=  __('Type')  ?></th>
	                    <th><?=  __('Applicable Tax')  ?></th>
	                    <th><?=  __('Date')  ?></th>
	                    <th><?=  __('status')  ?></th>
	                    <th><?=  __('Action')  ?></th>
	                </tr>
	            </thead>
	            <tbody></tbody>
	        </table>
	        <!-- /datatable -->
	    </div>
	    <!-- /datatable container -->
    </div>
	<div ui-view></div>
</div>

<!-- label column _template -->
<script type="text/template" id="labelColumnTemplate">

   <span class="custom-page-in-menu"><a href ng-click="taxListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.label %>"><%-__tData.label %></a></span>
   
</script>
<!-- /label column _template -->

<!-- place on date column _template -->
<script type="text/template" id="creationDateColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.creation_date %></span>
   
</script>
<!-- /place on date column _template -->

<!-- type column _template -->
<script type="text/template" id="typeColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.type %></span>
   
</script>
<!-- /type column _template -->

<!-- country column _template -->
<script type="text/template" id="countryColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.name %></span>
   
</script>
<!-- /country column _template -->


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
 	<a href ui-sref="taxes.edit({taxID:<%- __tData._id %>})" class="btn btn-default btn-xs" title="<?= __( 'Edit' ) ?>">
 		<i class="fa fa-pencil-square-o"></i> <?= __( 'Edit' ) ?>
 	</a>
 	<a href="" ng-click="taxListCtrl.delete('<%-__tData._id %>')" class="btn btn-danger btn-xs" title="<?= __( 'Delete' ) ?>">
 		<i class="fa fa-trash-o fa-lg"></i> <?= __( 'Delete' ) ?>
 	</a>
</script>
<!-- list row action column  _template -->