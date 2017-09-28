<div ng-controller="ManagePagesAddController as addPageCtrl" class="lw-dialog">
	
	<!-- main heading -->
	<div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Add Page' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
    <form class="lw-form lw-ng-form" 
        name="addPageCtrl.[[ addPageCtrl.ngFormName ]]" 
        novalidate>
            
       <!-- Title -->
        <lw-form-field field-for="title" label="<?= __( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true"
              autofocus 
              ng-model="addPageCtrl.pageData.title" />
        </lw-form-field>
        <!-- /Title -->
        
       
        <!-- type -->
         <lw-form-field field-for="type" label="<?= __( 'Type' ) ?>" advance="true">
               <select class="form-control" 
                name="type" ng-model="addPageCtrl.pageData.type" ng-options="open_as.value as open_as.text for open_as in addPageCtrl.pageType" ng-required="true" ng-change="addPageCtrl.pageTypeChanged()">
                <option value='' disabled selected>--Select--<option>
            </select>  
        </lw-form-field>
        <!-- /type -->
       
        <!-- Description -->
        <div ng-if="addPageCtrl.descriptionRequired" class="lw-image-width">
            <lw-form-field field-for="description" label="<?= __( 'Description' ) ?>"> 
                <textarea name="description" class="lw-form-field form-control" ng-required="[[addPageCtrl.descriptionRequired ]]"
                cols="30" rows="10" min-length="6" ng-model="addPageCtrl.pageData.description" lw-ck-editor limited-options="false"></textarea>
            </lw-form-field>
        </div>
        <!-- /Description -->

        <!-- Link -->
        <div ng-if="addPageCtrl.externalLinkRequired">
            <lw-form-field field-for="link" label="<?= __( 'Link' ) ?>"> 
                <input type="text" 
                  class="lw-form-field form-control"
                  name="link"
                  ng-required="[[ addPageCtrl.externalLinkRequired ]]" 
                  ng-model="addPageCtrl.pageData.link" />
            </lw-form-field>
        </div>
        <!-- /Link -->

        <!-- pages tree -->
        <div class="form-group">
            <label for="parent_id" class="control-label"><?= __("Parent Page") ?></label>
            <div 
                ng-model="addPageCtrl.pageData.parent_page" 
                class="select fancytree-list fancytree-id" 
                name="temp_row_id" 
                lw-fancytree 
                source='[[ addPageCtrl.pages ]]'
                id="fancytreeExample"
                listing-for='pages'
                form-type='catAdd'
                form-id=''
            >
            </div>
        </div>
        <!-- /pages tree -->
        
        <!-- open as -->
        <div ng-if="addPageCtrl.openAsRequired">
            <lw-form-field field-for="type" label="<?= __( 'Open As' ) ?>" advance="true">
                    <select class="form-control" 
                    name="type" ng-model="addPageCtrl.pageData.open_as" ng-options="open_as.value as open_as.text for open_as in addPageCtrl.pageLink" ng-required="[[ addPageCtrl.openAsRequired ]]">
                </select>  
            </lw-form-field>
        </div>
        <!-- /open as -->

		<div class="form-inline">
			<!-- Active -->
	        <lw-form-checkbox-field field-for="active" label="<?= __( 'Active' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="active"
	                ng-model="addPageCtrl.pageData.active"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Active -->

	        <!-- Add to menu -->
	        <lw-form-checkbox-field field-for="add_to_menu" label="<?= __( 'Add to menu' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="add_to_menu"
	                ng-model="addPageCtrl.pageData.add_to_menu"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Add to menu -->

	        <!-- Hide Sidebar -->
	        <lw-form-checkbox-field field-for="hide_sidebar" label="<?= __( 'Hide Sidebar' ) ?>" advance="true">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                name="hide_sidebar"
	                ng-model="addPageCtrl.pageData.hide_sidebar"
	                ui-switch="[[switcheryConfig]]" />
	        </lw-form-checkbox-field>
	        <!-- /Hide Sidebar -->
		</div><br>

		<div class="lw-dotted-line"></div>   
		     
        <!-- /Action -->
        <div class="lw-form-actions">
            <button type="submit" ng-click="addPageCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
            <button type="button" ng-click="addPageCtrl.close()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
        <!-- /Action -->

    </form>
    <!-- /form section -->

</div>

