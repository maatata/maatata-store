<div ng-controller="ShippingEditController as shippingEditCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Edit Shipping Rule' )  ?> </h3>
    </div>
	<!-- /main heading -->

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
			name="shippingEditCtrl.[[ shippingEditCtrl.ngFormName ]]" 
			ng-submit="shippingEditCtrl.submit()" 
			novalidate>

			<!-- Country -->
			<lw-form-field field-for="country" label="<?= __( 'Country' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="country"
					disabled="true" 
					value="[[shippingEditCtrl.shippingData.country]]" 
				/>
			</lw-form-field>
			<!-- /Country -->
			
			<!-- Type -->
            <lw-form-field field-for="type" label="<?= __('Type / Availability') ?>"> 
                <select class="lw-form-field form-control" 
	                name="type" ng-model="shippingEditCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingEditCtrl.discountType" ng-required="true">
	            </select>
            </lw-form-field>
	        <!-- /Type -->
			
			<!-- Charges -->
			<lw-form-field field-for="charges" label="<?= __( 'Charges' ) ?>" ng-if="shippingEditCtrl.shippingData.type != 3 && shippingEditCtrl.shippingData.type != 4"> 
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2" ng-if="shippingEditCtrl.shippingData.type == 1">[[shippingEditCtrl.currencySymbol]]</span>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="charges"
						ng-required="true"
						ng-model="shippingEditCtrl.shippingData.charges" 
					/>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="shippingEditCtrl.shippingData.type == 1">[[shippingEditCtrl.currency]]</span>
				  	<span class="input-group-addon" id="basic-addon2" ng-if="shippingEditCtrl.shippingData.type == 2">%</span>
				</div>
			</lw-form-field>
			<!-- /Charges -->

			<!-- Free After Amount -->
			<lw-form-field field-for="free_after_amount" label="<?= __( 'Free Shipping if Order Amount More than' ) ?>" v-label="<?= __( 'Shipping Free After Amount' ) ?>" ng-if="shippingEditCtrl.shippingData.type == 1"> 
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2">[[shippingEditCtrl.currencySymbol]]</span>
					<input type="number" 
						class="lw-form-field form-control"
						name="free_after_amount"
						ng-model="shippingEditCtrl.shippingData.free_after_amount" 
					/>
					<span class="input-group-addon" id="basic-addon2">[[shippingEditCtrl.currency]]</span>
				</div>
			</lw-form-field>
			<!-- /Free After Amount -->

			<!-- Amount Cap -->
			<lw-form-field field-for="amount_cap" label="<?= __( 'Maximum Shipping Amount' ) ?>" ng-if="shippingEditCtrl.shippingData.type == 2"> 
				<div class="input-group">
					<span class="input-group-addon" id="basic-addon2">[[shippingEditCtrl.currencySymbol]]</span>
					<input type="number" 
						class="lw-form-field form-control"
						name="amount_cap"
						min="0.1"
						ng-model="shippingEditCtrl.shippingData.amount_cap" 
					/>
					<span class="input-group-addon" id="basic-addon2">[[shippingEditCtrl.currency]]</span>
				</div>
			</lw-form-field>
			<!-- /Amount Cap -->

			<!-- notes -->
			<lw-form-field field-for="notes" label="<?= __( 'Notes' ) ?>"> 
				<textarea name="notes" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="shippingEditCtrl.shippingData.notes"></textarea>
			</lw-form-field>
			<!-- /notes -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="shippingEditCtrl.shippingData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
            <!-- /Status -->
		<div class="lw-dotted-line"></div> 			
			<!-- action -->
            <div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="shippingEditCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action -->

		</form>
		<!-- /form action -->
</div>