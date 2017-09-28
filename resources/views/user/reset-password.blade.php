<div ng-controller="UserResetPasswordController as resetPasswordCtrl">
    
    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __('Reset Password'))<?=  __( 'Reset Password' )  ?></h3>
        <!--  /main heading  -->
    </div>
	<!--  form action  -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="resetPasswordCtrl.[[ resetPasswordCtrl.ngFormName ]]" 
        ng-submit="resetPasswordCtrl.submit()" 
        novalidate>

        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __( 'Email' )  ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="resetPasswordCtrl.userData.email" />
        </lw-form-field>
        <!--  /Email  -->

        <!--  Password  -->
        <lw-form-field field-for="password" label="<?=  __( 'Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="resetPasswordCtrl.userData.password" />
        </lw-form-field>
        <!--  /Password  -->

        <!--  Password Confirmation  -->
        <lw-form-field field-for="password_confirmation" label="<?=  __( 'Password Confirmation' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="password_confirmation"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="resetPasswordCtrl.userData.password_confirmation" />
        </lw-form-field>
        <!--  /Password Confirmation  -->
		
		<!--  submit button  -->
        <div class="form-group lw-form-actions">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Reset Password')  ?>"><?=  __('Reset Password')  ?></button>
        </div>
        <!--  /submit button  -->

    </form>
	<!--  /form action  -->
</div>