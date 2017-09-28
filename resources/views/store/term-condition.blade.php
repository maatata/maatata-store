<div ng-controller="TermAndConditionSettingsController as termAndConditionSettingsCtrl" class="col-lg-9">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="termAndConditionSettingsCtrl.[[ termAndConditionSettingsCtrl.ngFormName ]]" 
        ng-submit="termAndConditionSettingsCtrl.submit()" novalidate>
		
		<div class="lw-main-loader lw-show-till-loading" 
			 ng-if="termAndConditionSettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>
    	
		<div ng-if="termAndConditionSettingsCtrl.pageStatus">
	        <lw-form-field field-for="term_condition" label="<?= __( 'Terms & Conditions For User Registration' ) ?>" > 
		        <textarea 
		            name="term_condition" 
		            class="lw-form-field form-control" 
		            cols="30" 
		            rows="10" 
		            ng-minlength="6" 
		            lw-ck-editor
		            ng-model="termAndConditionSettingsCtrl.editData.term_condition" 
		            >
		        </textarea>
	    	</lw-form-field>
		  

			<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	    </div>
	</form>
	<!-- /form action -->
</div>