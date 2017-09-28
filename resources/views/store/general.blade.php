<div ng-controller="GeneralSettingsController as generalSettingsCtrl" class="col-lg-9" id="general">

	<!-- form action -->
	<form class="lw-form lw-ng-form" name="generalSettingsCtrl.[[ generalSettingsCtrl.ngFormName ]]" 
        ng-submit="generalSettingsCtrl.submit()" 
        novalidate>
		
		<div class="lw-main-loader lw-show-till-loading" ng-if="generalSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>
    
		<div ng-if="generalSettingsCtrl.pageStatus">
	        <!-- Store Name -->
	        <lw-form-field field-for="store_name" label="<?= __( 'Store Name' ) ?>"> 
	            <input type="text" 
	                  class="lw-form-field form-control"
	                  autofocus
	                  name="store_name"
	                  ng-required="true" 
	                  ng-model="generalSettingsCtrl.editData.store_name" />
	        </lw-form-field>
	        <!-- Store Name -->

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

<!-- New logo drop down list item template -->
<script type="text/_template" id="imageListItemTemplate">
    <div>
        <span class="lw-selectize-item lw-selectize-item-selected"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
    </div>
</script>
<!-- /New logo drop down list item template -->

<!-- New logo drop down list options template -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item">
        <span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span>
    </div>
</script>
<!-- /New logo drop down list options template -->