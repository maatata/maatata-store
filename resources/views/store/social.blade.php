<div ng-controller="SocialSettingsController as socialSettingsCtrl" class="col-lg-6">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" name="socialSettingsCtrl.[[ socialSettingsCtrl.ngFormName ]]" 
        ng-submit="socialSettingsCtrl.submit()" 
        novalidate>
		
		<div class="lw-main-loader lw-show-till-loading" 
			 ng-if="socialSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>
    	
    	<div ng-if="socialSettingsCtrl.pageStatus">
			<!-- social facebook field -->
	       	<lw-form-field field-for="social_facebook" label="<?= __( 'Facebook Username' ) ?>"> 
	            <input type="social_facebook" 
	                  class="lw-form-field form-control"
	                  autofocus
	                  name="social_facebook"
	                  ng-model="socialSettingsCtrl.editData.social_facebook" />
	        </lw-form-field>
	        <!-- /social facebook field -->

	        <!-- social twetter field -->
	       	<lw-form-field field-for="social_twitter" label="<?= __( 'Twitter Handle' ) ?>"> 
	            <input type="social_twitter" 
	                  class="lw-form-field form-control"
	                  name="social_twitter"
	                  ng-model="socialSettingsCtrl.editData.social_twitter" />
	        </lw-form-field>
	        <!-- /social twetter field -->
			
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