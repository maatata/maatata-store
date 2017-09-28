<div ng-controller="TaxEditController as taxEditCtrl" class="lw-dialog">
   	<!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Edit Tax' )  ?></h3>
    </div>
	<!-- /main heading --> 

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="taxEditCtrl.[[ taxEditCtrl.ngFormName ]]" 
			ng-submit="taxEditCtrl.submit()" 
			novalidate>

			<!-- Country -->
			<lw-form-field field-for="country" label="<?= __( 'Country' ) ?>"> 
				<selectize config='taxEditCtrl.countries_select_config' class="lw-form-field" name="country" ng-model="taxEditCtrl.taxData.country" options='taxEditCtrl.countries' placeholder="<?= __( 'Select Country' ) ?>" ng-required="true"></selectize>
			</lw-form-field>
			<!-- /Country -->

			<!-- Label -->
			<lw-form-field field-for="label" label="<?= __( 'Label' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="label"
					ng-required="true"
					autofocus
					ng-model="taxEditCtrl.taxData.label" 
				/>
			</lw-form-field>
			<!-- /Label -->
			
			<!-- Type -->
            <lw-form-field field-for="type" label="<?= __('Type') ?>"> 
                <select class="lw-form-field form-control" 
	                name="type" ng-model="taxEditCtrl.taxData.type" ng-options="type.id as type.name for type in taxEditCtrl.taxType" ng-required="true">
	            </select>
            </lw-form-field>
	        <!-- /Type -->

			<!-- Charges -->
			<lw-form-field field-for="applicable_tax" label="<?= __( 'Applicable Tax' ) ?>" ng-if="taxEditCtrl.taxData.type != 3"> 
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2" ng-if="taxEditCtrl.taxData.type == 1">[[taxEditCtrl.currencySymbol]]</span>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="applicable_tax"
						ng-required="true"
						min="0.1"
						ng-model="taxEditCtrl.taxData.applicable_tax" 
					/>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="taxEditCtrl.taxData.type == 1">[[taxEditCtrl.currency]]</span>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="taxEditCtrl.taxData.type == 2">%</span>
				</div>
			</lw-form-field>
			<!-- /Charges -->

			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __( 'Notes' ) ?>"> 
				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="taxEditCtrl.taxData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="taxEditCtrl.taxData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
		<div class="lw-dotted-line"></div> 			
			<!-- action -->
            <div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="taxEditCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>