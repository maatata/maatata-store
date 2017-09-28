<div ng-controller="UserProfileEditController as profileEditCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __( 'Profile Update' ))<?=  __( 'Profile Update' )  ?></h3>
        <!--  /main heading  -->
    </div>
	<div ng-if="profileEditCtrl.request_completed">
	    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
	        name="profileEditCtrl.[[ profileEditCtrl.ngFormName ]]" 
	        ng-submit="profileEditCtrl.submit()" 
	        novalidate>

	        <!--  First Name  -->
	        <lw-form-field field-for="first_name" label="<?=  __( 'First Name' )  ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="first_name"
	              ng-required="true" 
	              ng-model="profileEditCtrl.profileData.first_name" />
	        </lw-form-field>
	        <!--  First Name  -->

	        <!--  Last Name  -->
	        <lw-form-field field-for="last_name" label="<?=  __( 'Last Name' )  ?>"> 
	            <input type="text" 
	              class="lw-form-field form-control"
	              name="last_name"
	              ng-required="true" 
	              ng-model="profileEditCtrl.profileData.last_name" />
	        </lw-form-field>
	        <!--  Last Name  -->

			<!--  update button  -->
	        <div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?=  __('Update')  ?>"><?=  __('Update')  ?> <span></span> </button>
	        </div>
	        <!--  /update button  -->

	    </form>
    </div>

</div>