<div ng-controller="ProductImagesController as productImagesCtrl">

    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __( 'Manage Images' ) ?></h3>
        <!-- /main heading -->
        <div class="lw-section-right-content">
            <button class="lw-btn btn btn-default pull-right btn-sm" title="<?= __( 'Add New Image' ) ?>" ng-href="" ng-click="productImagesCtrl.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Image' ) ?></button>
        </div>
    </div>

    <!-- datatable container -->
    <div>
        <!-- datatable -->
        <table class="table table-striped table-bordered" id="productImagesList" cellspacing="0" width="100%">
            <thead class="page-header">
                <tr>
                    <th><?=  __('Thumbnail')  ?></th>
                    <th><?=  __('Title')  ?></th>
                    <!-- <th><?=  __('Description')  ?></th> -->
                    <th><?=  __('Action')  ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <!-- /datatable -->
    </div>
    <!-- /datatable container -->
    
</div>

<!-- productImageThumbnailColumnTemplate -->
<script type="text/_template" id="productImageThumbnailColumnTemplate">
   <a href="<%- __tData.thumbnail_url %>" lw-ng-colorbox class="image-name lw-image-thumbnail"><img src="<%- __tData.thumbnail_url %>"></a> 
</script>
<!-- /productImageThumbnailColumnTemplate -->

<!-- productActionColumnTemplate -->
<script type="text/_template" id="productImageActionColumnTemplate">
    <a title="<?= __('Edit') ?>" class="btn btn-default btn-xs" ng-click="productImagesCtrl.edit('<%- __tData.id %>')" ng-href> 
        <i class="fa fa-pencil-square-o"></i> <?= __('Edit') ?></a>
    <a title="<?= __('Delete') ?>" class="btn btn-danger btn-xs delete-sw" ng-click="productImagesCtrl.delete('<%- __tData.id %>','<%- __tData.title %>' )" ng-href>
        <i class="fa fa-trash-o fa-lg"></i> <?= __('Delete') ?>
    </a>
</script>
<!-- /productActionColumnTemplatee -->

