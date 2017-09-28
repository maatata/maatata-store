<div class="dialog-contents" ng-controller="CategoryEditController as categoryEditCtrl" class="lw-dialog">
	
	<!-- main heading -->
    <div class="lw-section-heading-block" ng-if="categoryEditCtrl.categoryStatus == false">
		<h3 class="lw-header"><?= __("Edit Category") ?></h3>
	</div>

	<div class="lw-section-heading-block" ng-if="categoryEditCtrl.categoryStatus == true" >
        <h3 class="lw-header"><?=  __( 'Edit Category in ' )  ?>[[ categoryEditCtrl.categoryName ]]</h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
    <form class="lw-form lw-ng-form" 
        name="categoryEditCtrl.[[ categoryEditCtrl.ngFormName ]]" 
        ng-submit="categoryEditCtrl.update()" 
        novalidate>

        <!-- Name -->
		<lw-form-field field-for="name" label="<?= __('Name') ?>"> 
			<input type="name" 
				class="lw-form-field form-control"
				name="name"
				ng-required="true"
				autofocus
				ng-model="categoryEditCtrl.categoryData.name" 
			/>
		</lw-form-field>
		<!-- /Name -->
        
		<!-- categories tree -->
	    <div class="form-group">
	        <label for="parent_id" class="control-label"><?= __("Parent Category") ?></label>
	        <div 
	        	ng-model="categoryEditCtrl.categoryData.parent_cat"
	         	class="select fancytree-list"
	          	name="temp_row_id"
	           	lw-fancytree 
	           	source='[[ categoryEditCtrl.categoryData.categories ]]'
	           	listing-for='categories'
				form-type='catEdit'
				form-id='[[categoryEditCtrl.categoryData.id]]'
	           >
	       </div>
	    </div>
		<!-- /categories tree -->
		
		<!-- Status -->
        <lw-form-checkbox-field field-for="status" label="<?= __( 'Status' ) ?>" advance="true">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="status"
                ng-model="categoryEditCtrl.categoryData.active"
                ui-switch="[[switcheryConfig]]" />
        </lw-form-checkbox-field>
        <!-- /Status -->

		<div class="lw-dotted-line"></div>
		
		<!-- action button -->
        <div class="form-group lw-form-actions">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <button type="button" class="lw-btn btn btn-default" ng-click="categoryEditCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- action button -->
    </form>
    <!-- /form section -->

</div>