<div ng-controller="BrandEditController as brandEditCtrl">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Edit Brand' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="brandEditCtrl.[[ brandEditCtrl.ngFormName ]]" 
			ng-submit="brandEditCtrl.submit()" 
			novalidate>

			<!-- Name -->
			<lw-form-field field-for="name" label="<?= __( 'Name' ) ?>"> 
				<input type="name" 
					class="lw-form-field form-control"
					name="name"
					ng-required="true" 
					autofocus
					ng-model="brandEditCtrl.brandData.name" 
				/>
			</lw-form-field>
			<!-- /Name -->
			
			<!-- thumbnail -->
	        <div class="form-group">
				<div class="lw-thumb-logo" ng-if="brandEditCtrl.brandData.logo">
		        	<img  ng-src="[[brandEditCtrl.brandData.logo_url]]" alt="">
		        </div>
	        </div>
			<!-- /thumbnail -->
	        
			<!-- Select Logo -->
			<div class="form-group">
		        <lw-form-selectize-field field-for="logo" label="<?= __( 'Logo' ) ?>" class="lw-selectize"><span class="badge lw-badge">[[brandEditCtrl.images_count]]</span>
		            <selectize config='brandEditCtrl.imagesSelectConfig' class="lw-form-field" name="logo" ng-model="brandEditCtrl.brandData.logo" options='brandEditCtrl.image_files' placeholder="<?= __( 'Select Logo' ) ?>" ng-required="true"></selectize>
		        </lw-form-selectize-field>
                <div class="lw-form-append-btns">
                    <span class="btn btn-primary btn-xs lw-btn-file">
                        <i class="fa fa-upload"></i> 
                                <?=   __('Browse')   ?>
                        <input type="file" nv-file-select="" uploader="brandEditCtrl.uploader" multiple/>
                    </span>
                    <button class="btn btn-default btn-xs" title="<?= __('Uploaded files')  ?>" 
                        ng-click="brandEditCtrl.showUploadedMediaDialog()"  type="button"><?=  __("Uploaded files")  ?>
                    </button> 
                </div>
	        </div>
	        <!-- /Select Logo -->

			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="brandEditCtrl.brandData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="brandEditCtrl.brandData.active"
                    ui-switch="[[switcheryConfig]]" />
            </lw-form-checkbox-field>
			<!-- /Status -->
			
			<div class="lw-dotted-line"></div>

			<!-- action button -->
			<div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
				<?= __('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="brandEditCtrl.close()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action button -->

		</form>
		<!-- form section -->

</div>

<!-- image path -->
<script type="text/_template" id="logoListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path -->

<!-- image name -->
<script type="text/_template" id="logoListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image name -->