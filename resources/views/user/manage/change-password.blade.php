<div class="lw-dialog" ng-controller="ManageUserChangePasswordController as userChangePassword">
	
	<div class="lw-section-heading-block">
        <!--  main heading -->
        <h3 class="lw-section-heading"  ng-bind="userChangePassword.title">
        @section('page-title', __('Change Password'))</h3>
        <!--  /main heading -->
    </div>

    <!--  form action -->
    <form class="lw-form lw-ng-form" 
        name="userChangePassword.[[ userChangePassword.ngFormName ]]" 
        ng-submit="userChangePassword.submit()" 
        novalidate>

        <!--  New Password -->
        <lw-form-field field-for="new_password" label="<?=  __( 'New Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="new_password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="userChangePassword.changePasswordData.new_password" />
        </lw-form-field>
        <!--  /New Password -->

        <!--  New Password Confirmation -->
        <lw-form-field field-for="new_password_confirmation" label="<?=  __( 'New Password Confirmation' )  ?>">
            <input type="password" 
                  class="lw-form-field form-control"
                  name="new_password_confirmation"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="userChangePassword.changePasswordData.new_password_confirmation" />
        </lw-form-field>
        <!--  /New Password Confirmation -->
		
			<div class="lw-dotted-line"></div>
        <div class="form-group">
        	<!--  update password button -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Update Password')  ?>"><?=  __('Update Password')  ?> <span></span></button>
			<!--  /update password button -->

			<!--  close button -->
            <button type="button" ng-click="userChangePassword.closeDialog()" class="lw-btn btn btn-default" title="<?=  __('Cancel')  ?>"><?=  __('Cancel')  ?></button>
            <!--  close button -->
        </div>
		

    </form>
	<!--  /form action -->

</div>