<div ng-controller="ProductSpecificationAddController as addSpecificationCtrl">
	<!-- main heading -->
	 <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __("Add Specification") ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	 <form class="lw-form lw-ng-form" 
        name="addSpecificationCtrl.[[ addSpecificationCtrl.ngFormName ]]" 
        novalidate>

        <div class="col-lg-12" ng-repeat="value in addSpecificationCtrl.specificationData track by $index">

           <!-- Name -->
           <div class="col-lg-5">
   	        <lw-form-selectize-field field-for="[[ $index ]].name" label="<?= __( 'Name' ) ?>"> 
   	            <selectize 
   	           		config='addSpecificationCtrl.specification_name_select_config' 
   	           		class="lw-form-field form-control" 
   	           		name="[[ $index ]].name" 
   	           		ng-model="addSpecificationCtrl.specificationData[$index]['name']"
   	           		ng-change="addSpecificationCtrl.checkUnique($index, addSpecificationCtrl.specificationData[$index]['name'])" 
   	           		options='addSpecificationCtrl.specificationCollection' 
   	           		ng-required="true"
   	           		placeholder="<?= __( 'Select / Add New Name' ) ?>" 
   	           	></selectize>
   	        </lw-form-selectize-field>
   	        </div>
            <!-- /Name -->

            <!-- Value -->
            <div class="col-lg-5">
                <lw-form-selectize-field field-for="[[ $index ]].value" label="<?= __( 'Value' ) ?>"> 
                    <selectize 
                    	config='addSpecificationCtrl.specification_value_select_config' 
	                    class="lw-form-field form-control" 
	                    name="[[ $index ]].value" 
	                    ng-model="addSpecificationCtrl.specificationData[$index]['value']" 
	                    options='addSpecificationCtrl.specificationCollection' 
	                    ng-required="true" 
	                    placeholder="<?= __( 'Select Value' ) ?>" ></selectize>
				</lw-form-selectize-field>  
            </div>
            <div class="lw-option-value-remove-container">
                <!-- /Value -->
            <button ng-disabled=""  class="btn btn-default lw-option-value-remove-btn"  ng-click="addSpecificationCtrl.remove($index)" title="<?= __( 'Remove' ) ?>" class="lw-form-field"><i class="fa fa-times"></i> </button>
            </div>

		</div>
		<!-- button -->
		<div class="form-group">
            <button class="btn btn-default" title="<?= __( 'Add More' ) ?>" ng-click="addSpecificationCtrl.addNewValue()"><i class="fa fa-plus"></i> <?= __( 'Add More' ) ?></button>
        </div>
        <div class="lw-dotted-line"></div> 
        <div class="lw-form-actions">
            <button type="submit" ng-click="addSpecificationCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
            <button type="submit" ng-click="addSpecificationCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /button -->
	</form>
	<!-- /form action -->
</div>