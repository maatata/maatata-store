<div ng-controller="UserChangePasswordController as updatePasswordCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading -->
        <h3 class="lw-section-heading"><?=  __( 'Change Password' )  ?> @section('page-title', __('Change Password'))</h3>
        <!--  /main heading -->
    </div>
	
	<!--  form action -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="updatePasswordCtrl.[[ updatePasswordCtrl.ngFormName ]]" 
        ng-submit="updatePasswordCtrl.submit()" 
        novalidate>

        <!--  Current Password -->
        <lw-form-field field-for="current_password" label="<?=  __( 'Current Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="current_password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  autofocus
                  ng-model="updatePasswordCtrl.userData.current_password" />
        </lw-form-field>
        <!--  /Current Password -->

        <!--  New Password -->
        <lw-form-field field-for="new_password" label="<?=  __( 'New Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="new_password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="updatePasswordCtrl.userData.new_password" />
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
                  ng-model="updatePasswordCtrl.userData.new_password_confirmation" />
        </lw-form-field>
        <!--  /New Password Confirmation -->
		
		<!--  update password button -->
        <div class="form-group">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Update Password')  ?>"><?=  __('Update Password')  ?> <span></span></button>
        </div>
		<!--  /update password button -->

    </form>
	<!--  /form action -->
</div>