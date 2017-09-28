<div ng-controller="ProductOptionValuesController as productOptionValuesCtrl" class="lw-dialog">
    <!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __("Add / Edit Values") ?> </h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="productOptionValuesCtrl.[[ productOptionValuesCtrl.ngFormName ]]" 
        novalidate>
		<!-- note -->
        <div class="alert alert-info alert-dismissible" role="alert"><strong><?= __( 'Note : ' ) ?> </strong><span ng-bind="productOptionValuesCtrl.notification_message"></span></div>
        <!-- /note -->

        <div class="col-lg-12" ng-repeat="value in productOptionValuesCtrl.optionData.values track by $index">
            
            <!-- Name -->
            <lw-form-field field-for="values.[[ $index ]].name" label="<?= __( 'Value Name' ) ?>" class="col-xs-5"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="values.[[ $index ]].name"
                  ng-change="productOptionValuesCtrl.checkUnique($index, productOptionValuesCtrl.optionData.values[$index]['name'])"
                  ng-required="true" 
                  ng-model="productOptionValuesCtrl.optionData.values[$index]['name']" />
            </lw-form-field>
            <!-- /Name -->
            
             <!-- Addon Price and button -->
            <lw-form-field field-for="values.[[ $index ]].addon_price" label="<?= __( 'Addon Price' ) ?>"> 
                <div class="input-group">
                
                	<span class="input-group-addon"><?= getStoreSettings('currency_symbol') ?></span>

                    <input type="number" 
                          class="lw-form-field form-control"
                          name="values.[[ $index ]].addon_price"
                          min="0"
                          ng-model="productOptionValuesCtrl.optionData.values[$index]['addon_price']" />

                    <span class="input-group-addon"><?= getStoreSettings('currency_value') ?></span>

                    <span class="lw-remove-button input-group-btn" ng-hide="value.id">
                        <button ng-disabled="$first" ng-click="productOptionValuesCtrl.remove($index)" title="<?= __( 'Remove' ) ?>" class="btn btn-secondary btn btn-default"><i class="fa fa-times"></i> </button>
                    </span>
                    <span class="lw-remove-button input-group-btn" ng-if="value.id">
                        <button ng-disabled="productOptionValuesCtrl.optionData.values.length == 1"ng-click="productOptionValuesCtrl.delete(value.id)" title="<?= __( 'Remove' ) ?>" class="btn btn-secondary btn btn-default"><i class="fa fa-times"></i> </button>
                    </span>
                </div>
            </lw-form-field>
            <!-- /Addon Price and button -->
            
        </div>
		<!-- action button -->
        <div class="form-group">
            <button class="lw-btn btn btn-default" title="<?= __( 'Add More' ) ?>" ng-click="productOptionValuesCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __( 'Add More' ) ?></button>
        </div>		
        <div class="lw-dotted-line"></div>   
        <div class="lw-form-actions">
            <button type="submit" ng-click="productOptionValuesCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <button type="submit" ng-click="productOptionValuesCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /action button -->
    </form>
	<!-- /form action -->
</div>
