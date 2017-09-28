<div ng-controller="PrivacyPolicySettingsController as privacyPolicySettingsCtrl" class="col-lg-9">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="privacyPolicySettingsCtrl.[[ privacyPolicySettingsCtrl.ngFormName ]]" 
        ng-submit="privacyPolicySettingsCtrl.submit()" 
        novalidate>
		
			<div class="lw-main-loader lw-show-till-loading" 
				 ng-if="privacyPolicySettingsCtrl.pageStatus == false">
	        	<div class="loader"><?=  __('Loading...')  ?></div>
	    	</div>

		<div ng-if="privacyPolicySettingsCtrl.pageStatus">
			<div>
		        <lw-form-field field-for="privacy_policy" label="<?= __( 'Privacy' ) ?>" ng-if="privacyPolicySettingsCtrl.pageStatus"> 
		            <textarea name="privacy_policy" class="lw-form-field form-control" cols="30" rows="10" ng-minlength="6" ng-model="privacyPolicySettingsCtrl.editData.privacy_policy" lw-ck-editor></textarea>
		        </lw-form-field>
	        </div>
		    
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