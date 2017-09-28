<div ng-controller="UserProfileController as profileCtrl" class="">
    <div class="lw-section-heading-block"> 
        <!--  main heading  -->
        <h3 class="lw-section-heading"> @section('page-title',  __( 'Profile' ))<?= __( 'Profile' ) ?></h3>
        <!--  /main heading  -->
    </div>
	<div ng-if="profileCtrl.request_completed" class="form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12">
		<!--  first name  -->
        <div class="form-group ">
	      	<label for="fname" class="control-label"><?= __('First Name') ?></label>
		      <input readonly type="text" class="form-control" id="fname" value="[[ profileCtrl.profileData.first_name ]]">
	    </div>
	    <!--  first name  -->

	    <!--  last name  -->
		<div class="form-group">
	      	<label  for="lname" class="control-label"><?= __('Last Name') ?></label>
		    <input readonly type="text" class="form-control" id="lname" value="[[ profileCtrl.profileData.last_name ]]">
	    </div>
	    <!--  last name  -->

		<!--  edit button  -->
      	<div class="form-group">
        	<a href ng-href="<?=  route('user.profile.update')  ?>" title="<?= __('Edit') ?>" class="btn btn-primary"><?=  __('Edit')  ?></a>
        </div>    
        <!--  /edit button  -->
    </div>
</div>