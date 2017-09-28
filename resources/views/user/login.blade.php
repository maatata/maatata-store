<div ng-controller="UserLoginController as loginCtrl" ng-show="loginCtrl.request_completed == true"> 
	@if(!empty(Session::get('invalidUserMessage')))
		<div class="alert alert-danger">
	        <!--  invalid user message  -->
	        <?= Session::get('invalidUserMessage') ?>
	        <!--  /invalid user message  -->
	    </div>
	@endif

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __( 'Login' )) <?=  __( 'Login' )  ?></h3>
        <!--  /main heading  -->
    </div>
	
	<!--  form action  -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="loginCtrl.[[ loginCtrl.ngFormName ]]" 
        ng-submit="loginCtrl.submit()" 
        novalidate>

        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __( 'Email' )  ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="loginCtrl.loginData.email" />
        </lw-form-field>
        <!--  Email  -->

        <!--  Password  -->
        <lw-form-field field-for="password" label="<?=  __( 'Password' )  ?>"> 
            
            <div class="input-group">
                <input type="password" 
                  class="lw-form-field form-control"
                  name="password"
                  ng-minlength="6"
                  ng-maxlength="30"
                  ng-required="true" 
                  ng-model="loginCtrl.loginData.password" />
                <span class="input-group-addon">
                    <a href="<?=  route('user.forgot_password')  ?>" title="<?=  __('Forgot Password?')  ?>"><?=  __('Forgot Password?')  ?></a>
                </span>
            </div>
            
        </lw-form-field>
        <!--  Password  -->

        <div ng-if="loginCtrl.show_captcha == true">
            <!--  Confirmation Code  -->
            <lw-form-field field-for="confirmation_code" label="<?=  __( 'I know you are a human' )  ?>"> 
                <div class="input-group">
                    <span class="input-group-addon"><img ng-src="[[ loginCtrl.captchaURL ]]" alt=""></span>
                    <input type="text" 
                      class="lw-form-field form-control input-lg"
                      name="confirmation_code"
                      ng-required="true" 
                      ng-model="loginCtrl.loginData.confirmation_code" />
                    <span class="input-group-addon">
                        <a href="" title="<?=  __('Refresh Captcha')  ?>" ng-click="loginCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
                    </span>
                </div>
            </lw-form-field>
            <!--  Confirmation Code  -->
        </div>
        <div class="lw-form-inline-elements">
	        <!--  Remember me  -->
	        <lw-form-checkbox-field field-for="remember_me" label="<?=  __( 'Remember me' )  ?>" class="lw-margin-link lw-contain-remember-me-link">
	            <input type="checkbox" 
	                class="lw-form-field"
	                name="remember_me"
	                ng-model="loginCtrl.loginData.remember_me" />
	        </lw-form-checkbox-field>
	        <div class="lw-margin-link">
	        	<small>  <a href="<?=  route('user.resend.activation.email.fetch.view')  ?>">| <?= __("Didn't received activation key yet? Request again") ?></a></small>
	        <!--  /Remember me  -->
	        </div>
		</div>
		<!--  button  -->
        <div class="form-group">
            <button type="submit" class="lw-btn btn btn-success" title="<?=  __('Login')  ?>"><?=  __('Login')  ?> <span></span></button>
        </div>
		<!--  /button  --> 
         <div class="form-group"> 
            <small><?= __( 'Not Registered Yet?' ) ?></small> <br>
            <a class="lw-btn btn btn-warning" title="<?=  __('Register Now')  ?>" href="<?=  route('user.register')  ?>"><?=  __('Register Now')  ?> <span></span></a>
          </div>
    </form>
	<!--  /form action  -->
    
</div>