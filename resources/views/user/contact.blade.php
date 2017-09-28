<div ng-controller="UserContactController as contactCtrl">

     <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __( 'Contact' )) <?= __( 'Contact' ) ?></h3>
        <!--  /main heading  -->
    </div>
	
	<!--  form action  -->
    <form class="lw-form lw-ng-form form-horizontal col-lg-6 col-md-8 col-sm-12 col-xs-12" 
        name="contactCtrl.[[ contactCtrl.ngFormName ]]" 
        ng-submit="contactCtrl.submit(1)" 
        novalidate>

        <!--  user full name  -->
        <lw-form-field field-for="fullName" label="<?= __( 'Full Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="fullName"
              ng-required="true" 
              ng-model="contactCtrl.userData.fullName" />
        </lw-form-field>
        <!--  /user full name  -->

        <!--  user Email  -->
        <lw-form-field field-for="email" label="<?= __( 'Email' ) ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              ng-model="contactCtrl.userData.email" />
        </lw-form-field>
        <!--  /user Email  -->

        <!--  subject  -->
        <lw-form-field field-for="subject" label="<?= __( 'Subject' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="subject"
              ng-required="true" 
              ng-model="contactCtrl.userData.subject" />
        </lw-form-field>
        <!--  /subject  -->

         <div>
        <!--  message Description  -->
            <lw-form-field field-for="message" label="<?= __('Message') ?>"> 
                <textarea name="message" class="lw-form-field form-control"
                 cols="10" rows="3" ng-required="true" ng-model="contactCtrl.userData.message" lw-ck-editor limited-options="true"></textarea>
            </lw-form-field>
        <!--  /message Description  -->
        </div>
		
		<!--  submit button  -->
        <div class="form-group lw-form-actions">
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Submit') ?>"><?= __('Submit') ?> <span></span></button>
        </div>
        <!--  /submit button  -->
    </form>
    <!--  form action  -->
</div>
<!--  load ck editor js file  -->
<script src="<?= __yesset('dist/ckeditor/ckeditor*.js') ?>"></script>