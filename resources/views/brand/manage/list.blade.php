<div ng-controller="BrandListController as brandListCtrl">
    
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading">
            <span>
            <?= __( 'Manage Brands' ) ?>
            </span>
        </h3>
        <!-- /main heading -->

        <!-- action button -->
        <div class="lw-section-right-content">
            <button class="lw-btn btn btn-sm btn-default pull-right" title="<?= __( 'Add New Brand' ) ?>" ui-sref="brands.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Brand' ) ?></button>
        </div>
        <!-- /action button -->
    </div>

    <!-- data table -->
    <table class="table table-striped table-bordered" id="manageBrandList" cellspacing="0" width="100%">
        <thead class="page-header">
            <tr>
                <th><?=  __('Logo')  ?></th>
                <th><?=  __('Name')  ?></th>
                <th><?=  __('Created at')  ?></th>
                <th><?=  __('Status')  ?></th>
                <th><?=  __('Action')  ?></th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <!-- /data table -->

    <div ui-view></div>

    <!-- brand list row logo column  _template -->
    <script type="text/template" id="brandLogoColumnTemplate">
        <span class="lw-image-thumbnail"><img src="<%- __tData.logo_url %>"></span>  
    </script>
    <!-- /brand list row logo column  _template -->

    <!-- place on date column _template -->
    <script type="text/template" id="brandCreatedDateColumnTemplate">

       <span class="custom-page-in-menu"><%-__tData.creation_date %></span>
       
    </script>
    <!-- /place on date column _template -->

    <!-- brand list row name column  _template -->
    <script type="text/template" id="brandNameColumnTemplate">
        <span title="<?= __( 'Name' ) ?>">
            <a href ng-click="brandListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.country %>"><%- __tData.name %></a>
        </span> 
        <span class="pull-right">
            <a ui-sref="products({ brandID : '<%- __tData._id %>'})" title="<%- __tData.productCount %> <?= __('products under this brand') ?>" class="badge"> <%- __tData.productCount %> </a>
        </span>
    </script>
    <!-- /brand list row name column  _template -->

    <!-- brand list row status column  _template -->
    <script type="text/template" id="statusColumnTemplate">
        <% if (__tData.status === 1) { %> 
            <span title="<?= __( 'Active' ) ?>"><i class="fa fa-eye"></i></span>
       <% } else { %>
        <span title="<?= __( 'Inactive' ) ?>"><i class="fa fa-eye-slash"></i></span>
       <% } %>
    </script>
    <!-- /brand list row status column  _template -->

    <!-- brand list row action column  _template -->
    <script type="text/template" id="brandColumnActionTemplate">
        <a href ui-sref="brands.edit({brandID:<%- __tData._id %>})" class="btn btn-default btn-xs" title="<?= __( 'Edit' ) ?>">
            <i class="fa fa-pencil-square-o"></i> <?= __( 'Edit' ) ?>
        </a>
        <a href="" ng-click="brandListCtrl.delete('<%-__tData._id %>', '<%- escape(__tData.name) %>')" class="btn btn-danger btn-xs" title="<?= __( 'Delete' ) ?>">
            <i class="fa fa-trash-o fa-lg"></i> <?= __( 'Delete' ) ?>
        </a>
    </script>
    <!-- /brand list row action column  _template -->

</div>