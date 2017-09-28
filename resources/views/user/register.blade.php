<div ng-controller="UserRegisterController as registerCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __( 'Register' )) <?=  __( 'Register' )  ?></h3>
        <!--  /main heading  -->
    </div>
	
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="registerCtrl.[[ registerCtrl.ngFormName ]]" 
        ng-submit="registerCtrl.submit()" 
        novalidate>
        
        <!--  First Name  -->
        <lw-form-field field-for="first_name" label="<?=  __( 'First Name' )  ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="first_name"
              ng-required="true"
              ng-minlength="2"
              ng-maxlength="30"
              ng-model="registerCtrl.userData.first_name" />
        </lw-form-field>
        <!--  /First Name  -->

        <!--  Last Name  -->
        <lw-form-field field-for="last_name" label="<?=  __( 'Last Name' )  ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="last_name"
              ng-required="true" 
              ng-minlength="2"
              ng-maxlength="30"
              ng-model="registerCtrl.userData.last_name" />
        </lw-form-field>
        <!--  /Last Name  -->

        <!--  Email  -->
        <lw-form-field field-for="email" label="<?=  __( 'Email' )  ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="registerCtrl.userData.email" />
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
              ng-model="registerCtrl.userData.password" />
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
                  ng-model="registerCtrl.userData.password_confirmation" />
        </lw-form-field>
        <!--  /Password Confirmation  -->
		
		<!--  Confirmation Code  -->
        <lw-form-field field-for="confirmation_code" label="<?=  __( 'Prove you are not robot' )  ?>" v-label="<?=  __( 'Confirmation Code' )  ?>"> 
            <div class="input-group">
                <span class="input-group-addon"><img ng-src="[[ registerCtrl.captchaURL ]]" alt=""></span>
                <input type="text" 
                  class="lw-form-field form-control input-lg"
                  name="confirmation_code"
                  ng-required="true" 
                  ng-model="registerCtrl.userData.confirmation_code" />
                <span class="input-group-addon">
                    <a href="" title="<?=  __('Refresh Captcha')  ?>" ng-click="registerCtrl.refreshCaptcha()"><i class="fa fa-refresh"></i></a>
                </span>
            </div>
        </lw-form-field>
        <!--  Confirmation Code  -->

		<!--  terms and conditions  -->
		@if(getStoreSettings('term_condition')) 
			<div class="lw-form-inline-elements">
				<!--  read and accept checkbox  -->
				<lw-form-checkbox-field field-for="term_condition" label="<?=  __( 'I have read and accept' )  ?>" class="lw-margin-link">
		            <input type="checkbox" 
		                class="lw-form-field"
		                name="term_condition" 
		                ng-model="registerCtrl.userData.term_condition"/>
	        	</lw-form-checkbox-field>
				<!--  /read and accept checkbox  -->

	        	<div class="lw-margin-link">
	        		<small>
	        			<a  title="<?=  __('Terms &amp; Conditions')  ?>" 
	        				href="" ng-click="registerCtrl.showTermsAndConditionsDialog()" 
	        				class="pull-right">
							| <?=  __('Terms &amp; Conditions')  ?>
						</a>
					</small>
				</div>
			</div>
			<!--  /terms and conditions  -->

			<!--  register button  -->
	        <div class="form-group lw-form-actions">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Register')  ?>" ng-disabled="registerCtrl.userData.term_condition != true"><?=  __('Register')  ?> <span></span></button>
	        </div>
	        <!--  /register button  -->
	    @else

	    	<div class="form-group lw-form-actions">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Register')  ?>"><?=  __('Register')  ?> <span></span></button>
	        </div>
			<!--  /action button  -->
        @endif


    </form>

</div>