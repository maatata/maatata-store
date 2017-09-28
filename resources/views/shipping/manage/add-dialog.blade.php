<div ng-controller="ShippingAddController as shippingAddCtrl" class="lw-dialog">
   <!-- main heading --> 
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Add New Shipping Rule' )  ?></h3>
    </div>
	<!-- /main heading --> 

	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="shippingAddCtrl.[[ shippingAddCtrl.ngFormName ]]" 
		ng-submit="shippingAddCtrl.submit()" 
		novalidate>

		<!-- Country -->
		<lw-form-field field-for="country" label="<?= __( 'Country' ) ?>"> 
			<selectize config='shippingAddCtrl.countries_select_config' class="lw-form-field" name="country" ng-model="shippingAddCtrl.shippingData.country" options='shippingAddCtrl.countries' placeholder="<?= __( 'Select Country' ) ?>" ng-required="true"></selectize>
		</lw-form-field>
		<!-- /Country -->
		
		<!-- Type -->
        <lw-form-field field-for="type" label="<?= __('Type / Availability') ?>"> 
            <select class="lw-form-field form-control" 
                name="type" ng-model="shippingAddCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingAddCtrl.shippingType" ng-change="shippingAddCtrl.onChangeType(shippingAddCtrl.shippingData.type)" ng-required="true">
            </select>
        </lw-form-field>
        <!-- /Type -->

		<!-- Charges -->
		<lw-form-field field-for="charges" label="<?= __( 'Charges' ) ?>" ng-if="shippingAddCtrl.charges"> 
			<div class="input-group">
			  	<span class="input-group-addon" ng-if="shippingAddCtrl.shippingData.type == 1">[[shippingAddCtrl.currencySymbol]]</span>
			  	<input type="number" 
					class="lw-form-field form-control"
					name="charges"
					ng-required="true"
					min="0.1"
					ng-model="shippingAddCtrl.shippingData.charges" 
				/>
			  	<span class="input-group-addon" ng-if="shippingAddCtrl.shippingData.type == 1">[[shippingAddCtrl.currency]]</span>
			  	<span class="input-group-addon" ng-if="shippingAddCtrl.shippingData.type == 2">%</span>
			</div>
		</lw-form-field>
		<!-- /Charges -->

		<!-- Free After Amount -->
		<lw-form-field field-for="free_after_amount" label="<?= __( 'Free Shipping If Order Amount More Than' ) ?>" v-label="<?= __( 'Shipping Free After Amount' ) ?>" ng-if="shippingAddCtrl.freeAfterAmount"> 
			<div class="input-group">
				<span class="input-group-addon">[[shippingAddCtrl.currencySymbol]]</span>
				<input type="number" 
					class="lw-form-field form-control"
					name="free_after_amount"
					ng-model="shippingAddCtrl.shippingData.free_after_amount" 
				/>
				<span class="input-group-addon">[[shippingAddCtrl.currency]]</span>
			</div>
		</lw-form-field>
		<!-- /Free After Amount -->

		<!-- Maximum Shipping Amount -->
		<lw-form-field field-for="amount_cap" label="<?= __( 'Maximum Shipping Amount' ) ?>" ng-if="shippingAddCtrl.amountCap"> 
			<div class="input-group">
				<span class="input-group-addon" >[[shippingAddCtrl.currencySymbol]]</span>
				<input type="number" 
					class="lw-form-field form-control"
					name="amount_cap"
					min="0.1"
					ng-model="shippingAddCtrl.shippingData.amount_cap" 
				/>
				<span class="input-group-addon">[[shippingAddCtrl.currency]]</span>
			</div>
		</lw-form-field>
		<!-- /Maximum Shipping Amount -->

		<!-- notes -->
		<lw-form-field field-for="notes" label="<?= __( 'Notes' ) ?>"> 
			<textarea name="notes" class="lw-form-field form-control"
             cols="10" rows="3" ng-model="shippingAddCtrl.shippingData.notes"></textarea>
		</lw-form-field>
		<!-- /notes -->

		<!-- Status -->
        <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="active"
                ng-model="shippingAddCtrl.shippingData.active"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->
		<div class="lw-dotted-line"></div> 
		<!-- action -->
        <div class="lw-form-actions">
			<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
			<button type="button" class="lw-btn btn btn-default" ng-click="shippingAddCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
		</div>
		<!-- /action -->

	</form>
	<!-- /form action -->
</div>