<div ng-controller="ProductOptionAddController as addOptionCtrl" class="lw-dialog">
   	<!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __("Add Option") ?> </h3>
    </div>
    <!-- /main heading --> 
    <!-- form action -->
    <form class="lw-form lw-ng-form " 
        name="addOptionCtrl.[[ addOptionCtrl.ngFormName ]]" 
        novalidate>

        <div class="alert alert-info alert-dismissible" role="alert"><strong><?= __( 'Note : ' ) ?></strong> <?= __( 'Adding an option will create dropdown menu on product page.' ) ?></div>
        
        <!-- Name -->
        <lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="name"
              ng-required="true"
              autofocus 
              ng-model="addOptionCtrl.optionData.name" />
        </lw-form-field>
        <!-- Name -->

        <div class="col-lg-12" ng-repeat="value in addOptionCtrl.optionData.values track by $index">
            
            <!-- Name --> 
            <lw-form-field field-for="values.[[ $index ]].name" label="<?= __( 'Value Name' ) ?>" class="col-xs-5"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="values.[[ $index ]].name"
                  ng-change="addOptionCtrl.checkUnique($index, ddOptionCtrl.optionData.values[$index]['name'])"
                  ng-required="true" 
                  ng-model="addOptionCtrl.optionData.values[$index]['name']" />
            </lw-form-field>
            <!-- Name -->
            
            <!-- Addon Price -->
            <lw-form-field field-for="values.[[ $index ]].addon_price" label="<?= __( 'Addon Price' ) ?>"> 
                <div class="input-group">
                		<span class="input-group-addon"><?= getStoreSettings('currency_symbol') ?></span>
                        <input type="number" 
                              class="lw-form-field form-control"
                              name="values.[[ $index ]].addon_price"
                              min="0"
                              ng-model="addOptionCtrl.optionData.values[$index]['addon_price']" />

                        <span class="input-group-addon"><?= getStoreSettings('currency_value') ?></span>
                        <span class="lw-remove-button input-group-btn">
                            <button ng-disabled="$first" ng-click="addOptionCtrl.remove($index)" title="<?= __( 'Remove' ) ?>" class="btn btn-secondary btn btn-default "><i class="fa fa-times"></i> </button>
                        </span>
                    </div>
            </lw-form-field>
        	<!-- /Addon Price -->
        	
        </div>

		<!-- button action -->
        <div class="form-group">
            <button class="btn btn-default btn-sm" title="<?= __( 'Add More' ) ?>" ng-click="addOptionCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __( 'Add More' ) ?></button>
        </div>
		<div class="lw-dotted-line"></div>   
        <div class="lw-form-actions">
            <button type="submit" ng-click="addOptionCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
            <button type="submit" ng-click="addOptionCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /button action -->
		
    </form>
	<!-- /form action -->
</div>