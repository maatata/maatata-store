<div ng-controller="TaxAddController as taxAddCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Add Tax' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="taxAddCtrl.[[ taxAddCtrl.ngFormName ]]" 
			ng-submit="taxAddCtrl.submit()" 
			novalidate>
		
			<!-- Country -->
			<lw-form-field field-for="country" label="<?= __( 'Country' ) ?>"> 
				<selectize config='taxAddCtrl.countries_select_config' class="lw-form-field" name="country" ng-model="taxAddCtrl.taxData.country" options='taxAddCtrl.countries' placeholder="<?= __( 'Select Country' ) ?>" ng-required="true"></selectize>
			</lw-form-field>
			<!-- /Country -->
			
			<!-- Label -->
			<lw-form-field field-for="label" label="<?= __( 'Label' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="label"
					ng-required="true"
					ng-model="taxAddCtrl.taxData.label" 
				/>
			</lw-form-field>
			<!-- /Label -->
			
			<!-- Type -->
            <lw-form-field field-for="type" label="<?= __('Type') ?>"> 
                <select class="lw-form-field form-control" 
	                name="type" ng-model="taxAddCtrl.taxData.type" ng-options="type.id as type.name for type in taxAddCtrl.taxType" ng-required="true">
	            </select>
            </lw-form-field>
	        <!-- /Type -->

			<!-- Charges -->
			<lw-form-field field-for="applicable_tax" label="<?= __( 'Applicable Tax' ) ?>" ng-if="taxAddCtrl.taxData.type != 3"> 
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2" ng-if="taxAddCtrl.taxData.type == 1">[[taxAddCtrl.currencySymbol]]</span>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="applicable_tax"
						ng-required="true"
						min="0.1"
						ng-model="taxAddCtrl.taxData.applicable_tax" 
					/>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="taxAddCtrl.taxData.type == 1">[[taxAddCtrl.currency]]</span>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="taxAddCtrl.taxData.type == 2">%</span>
				</div>
			</lw-form-field>
			<!-- /Charges -->

			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __( 'Notes' ) ?>"> 
				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="taxAddCtrl.taxData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="taxAddCtrl.taxData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
		<div class="lw-dotted-line"></div> 			
			<!-- action -->
            <div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="taxAddCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>