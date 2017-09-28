<div ng-controller="ProductSettingsController as productSettingsCtrl" class="col-lg-9">

	<!-- form action -->
	<form class="lw-form lw-ng-form" name="productSettingsCtrl.[[ productSettingsCtrl.ngFormName ]]" 
        ng-submit="productSettingsCtrl.submit()" 
        novalidate>
		
		<div class="lw-main-loader lw-show-till-loading" 
			 ng-if="productSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>

    	<div ng-if="productSettingsCtrl.pageStatus">
			<!--Show Out of Stock Products    -->
	        <lw-form-checkbox-field field-for="show_out_of_stock" label="<?= __( 'Show Out Of Stock Products' ) ?>" ng-if="productSettingsCtrl.pageStatus">
	            <input type="checkbox" 
	                 class="lw-form-field js-switch"
	            	ui-switch=""
	            	name="show_out_of_stock"
	            	ng-model="productSettingsCtrl.editData.show_out_of_stock"/>
	        </lw-form-checkbox-field>
	        <!-- /Show Out of Stock Products   -->

	        <!-- Products per page -->
	        <lw-form-field field-for="pagination_count" label="<?= __( 'Products Per Page On List' ) ?>"> 
	            <input type="number" 
	                  class="lw-form-field form-control"
	                  autofocus
	                  name="pagination_count"
	                  ng-required="true"
	                  min="5"
	                  max="100" 
	                  step="1"
	                  ng-model="productSettingsCtrl.editData.pagination_count" />
	        </lw-form-field>
	        <!-- Products per page -->

			<!-- Update -->
			<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	        <!-- /Update -->
	    </div>
	</form>
	<!-- /form action -->
</div>