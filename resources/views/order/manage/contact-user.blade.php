<div class="lw-dialog" ng-controller="ManageContactUserController as manageContactCtrl">

     <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading">@section('page-title',  __( 'Contact' )) <?= __( 'Contact' ) ?></h3>
        <!--  /main heading  -->
    </div>
	
	<!--  form action  -->
    <form class="lw-form lw-ng-form" 
        name="manageContactCtrl.[[ manageContactCtrl.ngFormName ]]" 
        ng-submit="manageContactCtrl.submit(2)" 
        novalidate>
		
		<!--  user order id  -->
        <lw-form-field field-for="orderUID" label="<?= __( 'Order ID' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="orderUID"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.orderUID" readonly/>
        </lw-form-field>
        <!--  /user order id  -->

        <!--  user full name  -->
        <lw-form-field field-for="fullName" label="<?= __( 'Full Name' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="fullName"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.fullName" readonly/>
        </lw-form-field>
        <!--  /user full name  -->

        <!--  user Email  -->
        <lw-form-field field-for="email" label="<?= __( 'Email' ) ?>"> 
            <input type="email" 
              class="lw-form-field form-control"
              name="email"
              ng-required="true" 
              readonly
              ng-model="manageContactCtrl.userData.email" readonly/>
        </lw-form-field>
        <!--  /user Email  -->

        <!--  subject  -->
        <lw-form-field field-for="subject" label="<?= __( 'Subject' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="subject"
              ng-required="true" 
              ng-model="manageContactCtrl.userData.subject" />
        </lw-form-field>
        <!--  /subject  -->

         <div>
        <!--  message Description  -->
            <lw-form-field field-for="message" label="<?= __('Message') ?>"> 
                <textarea name="message" class="lw-form-field form-control"
                 cols="10" rows="3" ng-required="true" ng-model="manageContactCtrl.userData.message" lw-ck-editor></textarea>
            </lw-form-field>
        <!--  /message Description  -->
        </div>
		<div class="lw-dotted-line"></div>
		<!--  subit buton  -->
        <div class="form-group lw-form-actions">
        	<!-- submit button -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Submit') ?>"><?= __('Submit') ?></button>
           	<!-- /submit button -->

           	<!-- cancel button -->
            <button type="button" class="lw-btn btn btn-default" ng-click="manageContactCtrl.closeDialog()" title="<?= __('Close') ?>"><?= __('Close') ?></button>
            <!-- /cancel button -->
        </div>
        <!--  /subit buton  -->
    </form>
    <!--  form action  -->
</div>