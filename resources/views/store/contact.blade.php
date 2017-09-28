<div ng-controller="ContactSettingsController as contactSettingsCtrl" class="col-lg-9">
		
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="contactSettingsCtrl.[[ contactSettingsCtrl.ngFormName ]]" 
        ng-submit="contactSettingsCtrl.submit()" 
        novalidate>
		
		<div class="lw-main-loader lw-show-till-loading" 
			ng-if="contactSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>
    	
    	<div ng-if="contactSettingsCtrl.pageStatus">
			<!-- Contact -->
	       	<lw-form-field field-for="contact_email" label="<?= __( 'Email Address For Contact Form' ) ?>"> 
	            <input type="email" 
	                  class="lw-form-field form-control"
	                  autofocus
	                  name="contact_email"
	                  ng-required="true" 
	                  ng-model="contactSettingsCtrl.editData.contact_email" />
	        </lw-form-field>
	        <!-- /Contact -->

	        <!-- Contact Address -->
	        <lw-form-field field-for="contact_address" label="<?= __('Address, Telephone For Contact Page') ?>" ng-if="contactSettingsCtrl.pageStatus"> 
	            <textarea name="contact_address" class="lw-form-field form-control" cols="10" rows="3" ng-minlength="6" ng-required="true" ng-model="contactSettingsCtrl.editData.contact_address" lw-ck-editor></textarea>
	        </lw-form-field>
	        <!-- Contact Address --> 

			<!-- button -->
			<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	        <!-- button -->
        </div>
	</form>
	<!-- /form action -->
</div>