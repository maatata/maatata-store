<div ng-controller="BrandDeleteDialogController as BrandDeleteDialogCtrl" class="text-center">
	
    <!--  Main heading  -->
    <div>
        <span class="lw-header"><strong><h2><?=  __( 'Are You Sure ?' )  ?></h2></strong></span>
    </div>
    <div>
    	<h5 ng-bind-html="BrandDeleteDialogCtrl.notificationMessage"></h5>
    </div>
    <!--  /Main heading  -->

	<!--  form section  -->
	<form class="lw-form lw-ng-form" 
		name="BrandDeleteDialogCtrl.[[ BrandDeleteDialogCtrl.ngFormName ]]" 
		ng-submit="BrandDeleteDialogCtrl.submit()" 
		novalidate>
        
        <!--  Delete Products  -->
        <lw-form-checkbox-field field-for="delete_related_products" label="<?=  __( 'Delete Related Products' )  ?>" class="lw-margin-link">
            <input type="checkbox" 
                class="lw-form-field"
                name="delete_related_products"
                ng-model="BrandDeleteDialogCtrl.brandData.delete_related_products" />
        </lw-form-checkbox-field>
        <!--  Delete Products  -->

        <!--  Current Password  -->
		<lw-form-field field-for="current_password" label="<?= __( 'Confirm product delete by entering password' ) ?>" v-label="<?= __('Password') ?>" ng-show="BrandDeleteDialogCtrl.brandData.delete_related_products"> 
			<input type="password" 
				class="lw-form-field form-control"
                ng-minlength="6"
                ng-maxlength="30"
				name="current_password"
				ng-required="BrandDeleteDialogCtrl.brandData.delete_related_products" 
				ng-model="BrandDeleteDialogCtrl.brandData.current_password" 
			/>
		</lw-form-field>
		<!--  /Current Password  -->

		<!--  Action button  -->
		<div class="lw-form-actions lw-text-center">
			<!--  delete button  -->
			<button type="submit" class="lw-btn btn btn-danger" title="<?= __('Delete') ?>"><?= __('Yes, Delete it') ?> <span></span></button>
			<!--  /delete button  -->

			<!--  cancel button  -->
			<button type="button" class="lw-btn btn btn-default" ng-click="BrandDeleteDialogCtrl.close()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			<!--  /cancel button  -->
		</div>
		<!--  /Action button  -->

	</form>
	<!--  /form section  -->

</div>