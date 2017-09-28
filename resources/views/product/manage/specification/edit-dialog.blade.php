<div ng-controller="ProductSpecificationEditController as editSpecificationCtrl">
	<!-- main heading -->
	 <div class="lw-section-heading-block">
        <h3 class="lw-header"> <?= __("Edit Specification") ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
        name="editSpecificationCtrl.[[ editSpecificationCtrl.ngFormName ]]" 
        novalidate>

		<div class="col-lg-6 form-group">
	    	<!-- Name -->
	    	<lw-form-selectize-field field-for="name" label="<?= __( 'Name' ) ?>" class="lw-selectize"> 

	    		<selectize 
		           		config='editSpecificationCtrl.specification_name_select_config' 
		           		class="lw-form-field" 
		           		name="name" 
		           		ng-model="editSpecificationCtrl.specificationData.name" 
		           		options='editSpecificationCtrl.specificationCollection' 
		           		ng-required="true"
		           		placeholder="<?= __( 'Select Name' ) ?>" 
		           	></selectize>

	        </lw-form-selectize-field>
	        <!-- /Name -->
		</div>

        <!-- Value -->
        <div class="col-lg-5 form-group">
            <!-- Value -->
            <lw-form-selectize-field field-for="[[ $index ]].value" label="<?= __( 'Value' ) ?>" class="lw-selectize"> 
	            <selectize 
	        		config='editSpecificationCtrl.specification_value_select_config' 
	                class="lw-form-field form-control" 
	                name="[[ $index ]].value" 
	                ng-model="editSpecificationCtrl.specificationData.value" 
	                options='editSpecificationCtrl.specificationCollection' 
	                ng-required="true" 
	                placeholder="<?= __( 'Select Value' ) ?>">
	                </selectize>

            </lw-form-selectize-field>
            <!-- /Value -->
        </div>
        <!-- /Value -->
        
		<!-- action button -->
		<div class="lw-form-actions">
            <button type="submit" ng-click="editSpecificationCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <button type="submit" ng-click="editSpecificationCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- action button -->
	</form>
	<!-- /form action -->
</div>