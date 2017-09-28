<div ng-controller="ProductSpecificationController as productSpecificationCtrl">
	
	<div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __( 'Manage Specification' ) ?></h3>
        <!-- /main heading -->
        <div class="lw-section-right-content">
            <button class="lw-btn btn btn-default pull-right btn-sm" title="<?= __( 'Add New Specification' ) ?>" ng-href="" ng-click="productSpecificationCtrl.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Specification' ) ?></button>
        </div>
    </div>

    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="productSpecificationList" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?=  __('Name')  ?></th>
                    <th><?=  __('Values')  ?></th>
                    <th><?=  __('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
    </div>
    <!-- /datatable container -->

    <script type="text/_template" id="productSpecificationColumnTemplate">
    
	</script>

	<!-- productActionColumnTemplate -->
	<script type="text/_template" id="productSpecificationActionColumnTemplate">
	    <a title="<?= __('Edit') ?>" class="lw-btn btn btn-default btn-xs" ng-click="productSpecificationCtrl.edit('<%- __tData._id %>')" ng-href> 
	        <i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
	    <a title="<?= __('Delete') ?>" class="lw-btn btn btn-danger btn-xs delete-sw" ng-click="productSpecificationCtrl.delete('<%- __tData._id %>','<%- __tData.name %>' )" ng-href>
	        <i class="fa fa-trash-o fa-lg"></i> <?= __('Delete') ?>
	    </a>
	</script>

</div>