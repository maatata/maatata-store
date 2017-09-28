<div ng-controller="ManageEditProductDetailsController as editProductCtrl">
	
    <div class="lw-section-heading-block" ng-if="editProductCtrl.productName">
        <!-- main heading -->
        <h3 class="lw-section-heading" ng-bind="editProductCtrl.productName"></h3>
        <!-- /main heading -->
    </div>

    <div class="lw-clear pull-right">

        <!-- update form status form -->
        <form class="lw-form lw-ng-form form-inline" name="editProductCtrl.[[ editProductCtrl.ngFormName ]]" 
            novalidate>

            <!-- Active -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Publically Available' ) ?>" class="lw-form-item-box" ng-if="editProductCtrl.initialContentLoaded">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    title="<?= __( 'Update Status' ) ?>" 
                    ng-model="editProductCtrl.productData.active" 
                    ui-switch="" ng-change="editProductCtrl.submit()"/>
            </lw-form-checkbox-field>
            <!-- /Active -->

        </form>
        <!-- /update form status form -->

    </div>
    
    <!-- Product Edit Tabs -->
    <ul class="nav nav-tabs">
        <li role="presentation" ui-sref-active="active"><a ui-sref="product_edit.details"><?= __( 'Details' ) ?></a></li>
        <li role="presentation" ui-sref-active="active"><a ui-sref="product_edit.options"><?= __( 'Options' ) ?></a></li>
        <li role="presentation" ui-sref-active="active"><a ui-sref="product_edit.images"><?= __( 'Images' ) ?></a></li>
        <li role="presentation" ui-sref-active="active"><a ui-sref="product_edit.specification"><?= __( 'Specification' ) ?></a></li>
    </ul>
    <!-- /Product Edit Tabs -->

    <div ui-view></div>

</div>
