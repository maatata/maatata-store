<div class="lw-dialog" ng-controller="CategoryAddController as categoryAddCtrl">
	
	<!-- main heading -->
	<div class="lw-section-heading-block" ng-if="categoryAddCtrl.categoryStatus == false" >
        <h3 class="lw-header"><?=  __( 'Add Category' )  ?></h3>
    </div>

    <div class="lw-section-heading-block" ng-if="categoryAddCtrl.categoryStatus == true" >
        <h3 class="lw-header" ng-bind="categoryAddCtrl.categoryName"></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
		name="categoryAddCtrl.[[ categoryAddCtrl.ngFormName ]]" 
		ng-submit="categoryAddCtrl.submit()" 
		novalidate>

		<!-- Name -->
		<lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
			<input type="name" 
				class="lw-form-field form-control"
				name="name"
				ng-required="true"
				autofocus
				ng-model="categoryAddCtrl.categoryData.name" 
			/>
		</lw-form-field>
		<!-- /Name -->

		<!-- categories tree -->
	    <div class="form-group">
	        <label for="parent_id" class="control-label"><?= __("Parent Category") ?></label>
	        <div 
			    ng-model="categoryAddCtrl.categoryData.parent_cat" 
			    class="select fancytree-list" 
			    name="temp_row_id" 
			    lw-fancytree 
			    source='[[ categoryAddCtrl.categoryData.categories ]]'
			    listing-for='categories'
			    form-type='catAdd'
			    form-id='[[ categoryAddCtrl.categoryData.parent_cat ]]'
	        >
	        </div>
	    </div>
		<!-- /categories tree -->

		<!-- Status -->
        <lw-form-checkbox-field field-for="status" label="<?= __( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="status"
                ng-model="categoryAddCtrl.categoryData.status"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->
		
		<div class="lw-dotted-line"></div>
		
		<!-- action button -->
		<div class="lw-form-actions">
			<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Submit') ?>"><?= __('Add') ?> <span></span></button>
			<button type="button" class="lw-btn btn btn-default" ng-click="categoryAddCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
		</div>
		<!-- /action button -->

	</form>
	<!-- /form section -->
</div>
