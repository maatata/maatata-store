<div ng-controller="PlacementSettingsController as placementSettingsCtrl" class="col-lg-9">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="placementSettingsCtrl.[[ placementSettingsCtrl.ngFormName ]]" 
        ng-submit="placementSettingsCtrl.submit()" 
        novalidate>
        
        <div class="lw-main-loader lw-show-till-loading" ng-if="placementSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>

		<div ng-if="placementSettingsCtrl.pageStatus">

			<div class="form-group">
				<!-- Categories Placement -->
		         <lw-form-selectize-field field-for="categories_menu_placement" label="<?= __( 'Categories Menu' ) ?>" class="lw-selectize">
		            <selectize config='placementSettingsCtrl.categories_menu_placement_select_config' class="lw-form-field" name="categories_menu_placement" ng-model="placementSettingsCtrl.editData.categories_menu_placement" options='placementSettingsCtrl.menu_placement' placeholder="<?= __( 'Select Categories' ) ?>" ng-required="true"></selectize>
		        </lw-form-selectize-field>
	        	<!-- Categories Placement -->
			</div>
			
			<div class="form-group">
		        <!-- Brand Menu -->
		        <lw-form-selectize-field field-for="brand_menu_placement" label="<?= __( 'Brand Menu' ) ?>" class="lw-selectize">
		            <selectize config='placementSettingsCtrl.brand_menu_placement_select_config' class="lw-form-field" name="brand_menu_placement" ng-model="placementSettingsCtrl.editData.brand_menu_placement" options='placementSettingsCtrl.menu_placement' placeholder="<?= __( 'Select Brand' ) ?>" ng-required="true"></selectize>
		        </lw-form-selectize-field>
		        <!-- Brand Menu -->
			</div>

			 <!-- Enable Credit Info -->
	        <lw-form-checkbox-field field-for="credit_info" label="<?= __( 'Enable Credit Info' ) ?>" ng-if="placementSettingsCtrl.pageStatus">
	            <input type="checkbox" 
	                 class="lw-form-field js-switch"
	            	ui-switch=""
	            	name="credit_info"
	            	ng-model="placementSettingsCtrl.editData.credit_info"/>
	        </lw-form-checkbox-field>
	        <!-- /Enable Credit Info -->

	        <!-- show default language menu -->
	        <lw-form-checkbox-field field-for="show_language_menu" label="<?= __( 'Show Language Menu' ) ?>" ng-if="placementSettingsCtrl.pageStatus">
	            <input type="checkbox" 
	                class="lw-form-field js-switch"
	                ui-switch=""
	                name="show_language_menu"
	                ng-model="placementSettingsCtrl.editData.show_language_menu"/>
	        </lw-form-checkbox-field>
	        <!-- /show default language menu  -->
		
			<div>
				 <!--  google Analytics  -->
	            <lw-form-field field-for="addtional_page_end_content" v-label="<?= __('Page End Additionals') ?>" label="<?= __('Page End Additionals, May Use For Scripts Like Google Analytics etc.') ?>"> 
	                <textarea name="addtional_page_end_content" class="lw-form-field form-control"
	                 cols="10" rows="3" ng-model="placementSettingsCtrl.editData.addtional_page_end_content"></textarea>
	            </lw-form-field>
		        <!--  /google Analytics  -->
	        </div>

			 <!-- Footer text -->
	        <lw-form-field field-for="footer_text" label="<?= __( 'Addition Footer Text After Name & Copy Right' ) ?>"> 
	            <input type="text" 
	                class="lw-form-field form-control"
	                name="footer_text"
	                ng-minlength="3"
	                ng-maxlength="50"
	                ng-model="placementSettingsCtrl.editData.footer_text" />
	        </lw-form-field>
	        <!-- Footer text -->
			
			<!-- button -->
			<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	        <!-- /button -->
	    </div>
	</form>
	<!-- /form action -->
</div>