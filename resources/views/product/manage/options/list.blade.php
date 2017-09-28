<div ng-controller="ProductOptionsController as productOptionsCtrl">
    
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __( 'Manage Options' ) ?></h3>
        <!-- /main heading -->

        <!-- button -->
        <div class="lw-section-right-content">
            <button class="lw-btn btn btn-default pull-right btn-sm" title="<?= __( 'Add New Option' ) ?>" ng-href="" ng-click="productOptionsCtrl.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Option' ) ?></button>
        </div>
        <!-- /button -->
    </div>

    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="productOptionList" cellspacing="0" width="100%">
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
    
</div>

<!-- product Option Values Column Template -->
<script type="text/_template" id="productOptionValuesColumnTemplate">
    <a  title="<?= __('Add / Edit values for') ?> <%- __tData.name %>" 
	    class="lw-btn btn btn-default btn-xs" 
	    ng-click="productOptionsCtrl.values('<%- __tData.id %>', '<%- __tData.name %>')" 
	    ng-href>
	    <i class="fa fa-plus"></i> <?= __('Add / ') ?>
	    <i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?>
    </a>
</script>
<!-- product Option Values Column Template -->

<!-- product Action Column Template -->
<script type="text/_template" id="productOptionActionColumnTemplate">
    <a title="<?= __('Edit') ?>" class="lw-btn btn btn-default btn-xs" ng-click="productOptionsCtrl.edit('<%- __tData.id %>')" ng-href> 
        <i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
    <a title="<?= __('Delete') ?>" class="lw-btn btn btn-danger btn-xs delete-sw" ng-click="productOptionsCtrl.delete('<%- __tData.id %>','<%- __tData.name %>' )" ng-href>
        <i class="fa fa-trash-o fa-lg"></i> <?= __('Delete') ?>
    </a>
</script>
<!-- /product Action Column Templatee -->

