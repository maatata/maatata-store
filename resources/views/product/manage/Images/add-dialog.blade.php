<div ng-controller="ProductImageAddController as addImageCtrl" class="lw-dialog">
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?= __("Add Image") ?></h3>
    </div>
    <!-- /main heading -->

    <!-- form action -->
    <form class="lw-form lw-ng-form" 
        name="addImageCtrl.[[ addImageCtrl.ngFormName ]]" 
        novalidate>
        
        <!-- Title -->
        <lw-form-field field-for="title" label="<?= __( 'Title' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="title"
              ng-required="true" 
              ng-model="addImageCtrl.imageData.title" />
        </lw-form-field>
        <!-- /Title -->

        <!-- Select Image -->
        <div class="form-group">
	        <lw-form-selectize-field field-for="image" label="<?= __( 'Image' ) ?>" class="lw-selectize"><span class="badge lw-badge">[[addImageCtrl.images_count]]</span>
	            <selectize config='addImageCtrl.imagesSelectConfig' class="lw-form-field" name="image" ng-model="addImageCtrl.imageData.image" options='addImageCtrl.image_files' placeholder="<?= __( 'Select Image' ) ?>" ng-required="true"></selectize>
	        </lw-form-selectize-field>
	        <div class="lw-form-append-btns">
	            <span class="btn btn-primary btn-xs lw-btn-file">
	            	<i class="fa fa-upload"></i> 
							<?=   __('Upload New Images')   ?>
	                <input type="file" nv-file-select="" uploader="addImageCtrl.uploader" multiple/>
	            </span>
            	<button class="btn btn-default btn-xs" title="<?= __('Uploaded Images')  ?>" 
                	ng-click="addImageCtrl.showUploadedMediaDialog()"  type="button"><?=  __("Uploaded Images")  ?>
            	</button> 
          	</div>
        </div>
        <!-- /Select Image -->
		<div class="lw-dotted-line"></div>   
		<!-- action button -->
        <div class="lw-form-actions">
            <button type="submit" ng-click="addImageCtrl.submit()" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
            <button ng-click="addImageCtrl.cancel()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
        </div>
		<!-- /action button -->

    </form>
	<!-- /form action -->
</div>

<!-- image path and name -->
<script type="text/_template" id="imageListItemTemplate">
  <div class="lw-selectize-item lw-selectize-item-selected">
        <span class="lw-selectize-item-thumb">
        <img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->

<!-- image path and name -->
<script type="text/_template" id="imageListOptionTemplate">
    <div class="lw-selectize-item"><span class="lw-selectize-item-thumb"><img src="<%= __tData.path %>"/> </span> <span class="lw-selectize-item-label"><%= __tData.name%></span></div>
</script>
<!-- /image path and name -->
