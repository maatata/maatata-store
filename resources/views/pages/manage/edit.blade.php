<div ng-controller="ManagePagesEditController as editPageCtrl" class="lw-dialog">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Edit Page' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
    <form class="lw-form lw-ng-form" 
        name="editPageCtrl.[[ editPageCtrl.ngFormName ]]" 
        novalidate>
        
        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true"
              autofocus 
              ng-model="editPageCtrl.pageData.title" />
        </lw-form-field>
        <!-- Title -->

        <!-- Description -->
       	<div ng-if="editPageCtrl.descriptionRequired">
        	<lw-form-field field-for="description" label="<?= __( 'Description' ) ?>"> 
            	<textarea name="description" class="lw-form-field form-control" ng-required="[[editPageCtrl.descriptionRequired ]]"
             	cols="30" rows="10" min-length="6" ng-model="editPageCtrl.pageData.description" lw-ck-editor></textarea>
        	</lw-form-field>
        </div>
        <!-- Description -->
		
		<!-- Type -->
		<div ng-if="editPageCtrl.openAsRequired">
	        <lw-form-field field-for="type" label="<?= __( 'Type' ) ?>"> 
	            <select class="form-control" 
	                name="type" ng-model="editPageCtrl.pageData.open_as" ng-options="open_as.value as open_as.text for open_as in editPageCtrl.pageLink" ng-required="true">
	            </select> 
	        </lw-form-field>
	    </div>
        <!-- /Type -->
    
		<!-- Link -->
		<div ng-if="editPageCtrl.externalLinkRequired">
        	<lw-form-field field-for="link" label="<?= __( 'Link' ) ?>"> 
            	<input type="text" 
	              class="lw-form-field form-control"
	              name="link"
	              ng-required="[[ editPageCtrl.externalLinkRequired ]]" 
	              ng-model="editPageCtrl.pageData.link" />
        	</lw-form-field>
        </div>
        <!-- Link -->

		<!-- pages tree -->
	    <div class="form-group">
	        <label for="parent_id" class="control-label"><?= __("Parent Page") ?></label>
	        <div 
			    ng-model="editPageCtrl.pageData.parent_page" 
			    class="select fancytree-list" 
			    name="temp_row_id" 
			    lw-fancytree 
			    source='[[ editPageCtrl.pages ]]'
			    listing-for='pages'
			    form-type='catEdit'
			    form-id='[[ editPageCtrl.pageData.id ]]'
	        >
	        </div>
	    </div>
		<!-- pages tree -->
		
		<div class="form-inline">
			<!-- Active -->
	        <lw-form-checkbox-field field-for="active" label="<?= __( 'Active' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="active"
	                ng-model="editPageCtrl.pageData.status"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Active -->

	        <!-- Add to menu -->
	        <lw-form-checkbox-field field-for="add_to_menu" label="<?= __( 'Add to menu' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="add_to_menu"
	                ng-model="editPageCtrl.pageData.add_to_menu"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Add to menu -->

	        <!-- Hide Sidebar -->
	        <lw-form-checkbox-field field-for="hide_sidebar" label="<?= __( 'Hide Sidebar' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="hide_sidebar"
	                ng-model="editPageCtrl.pageData.hide_sidebar"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Hide Sidebar -->
		</div><br>

		<div class="lw-dotted-line"></div> 

        <!-- Action -->
        <div class="lw-form-actions">
            <button type="submit" ng-click="editPageCtrl.update()" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <button type="button" ng-click="editPageCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /Action -->

    </form>
    <!-- /form section -->

</div>

