<div ng-controller="UserChangeEmailController as changeEmailCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">
        @section('page-title', __('Change Email'))        
        <?=  __( 'Change Email' )  ?></h3>
        <!--  /main heading  -->
    </div>
	<!--  note  -->
    <div ng-if="changeEmailCtrl.changeEmail == true">
		<div class="alert alert-success">
		  <div class="header">
		    <strong><?=  __("Activate your new email address") ?></strong>
		  </div>
		  <p><?=  __("Almost finished... You need to confirm your email address. To complete the activation process, please click the link in the email we just sent you.")  ?></p>
		</div>
	</div>
	<!--  /note  -->

	<!--  form action  -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="changeEmailCtrl.[[ changeEmailCtrl.ngFormName ]]" 
        ng-submit="changeEmailCtrl.submit()" 
        novalidate>

        <!--  Current Email  -->
		<lw-form-field field-for="current_email" label="<?=  __( 'Current Email' )  ?>"> 
			<input type="text" 
					class="lw-form-field form-control lw-readonly-control"
					name="current_email"
					readonly
					value="<?=  Auth::user()->email  ?>" 
				/>
		</lw-form-field>
		<!--  /Current Email  -->

        <!--  Current Password  -->
        <lw-form-field field-for="current_password" label="<?=  __( 'Current Password' )  ?>"> 
            <input type="password" 
                  class="lw-form-field form-control"
                  name="current_password"
                  min-length="6"
                  max-length="30"
                  ng-required="true"
                  autofocus 
                  ng-model="changeEmailCtrl.userData.current_password" autofocus />
        </lw-form-field>
        <!--  /Current Password  -->

        <!--  New Email  -->
        <lw-form-field field-for="new_email" label="<?=  __( 'New Email' )  ?>"> 
            <input type="email" 
                  class="lw-form-field form-control"
                  name="new_email"
                  ng-required="true" 
                  ng-model="changeEmailCtrl.userData.new_email" />
        </lw-form-field>
        <!--  /New Email  -->
		
		<!--  update button  -->
        <div class="form-group">
            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Update Request')  ?>"><?=  __('Update Request')  ?> <span></span></button>
        </div>
		<!--  /update button  -->
    </form>
	<!--  /form action  -->
</div>