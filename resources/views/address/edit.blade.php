<div ng-controller="AddressEditController as addressEditCtrl">

    <div class="lw-section-heading-block">
        <!--  main heading  -->
        <h3 class="lw-section-heading"><?= __( 'Edit Address' ) ?></h3>
        <!--  /main heading  -->
    </div>
    
    <!--  form action  -->
    <form class="lw-form lw-ng-form"
        name="addressEditCtrl.[[ addressEditCtrl.ngFormName ]]"
        ng-submit="addressEditCtrl.update()"
        novalidate>
        
        <!--  address type  -->
        <div>
          <lw-form-field field-for="type" label="<?= __( 'Type' ) ?>"> 
            <select class="form-control" 
                name="type" ng-model="addressEditCtrl.addressData.type" ng-options="type.id as type.name for type in addressEditCtrl.addressType">
            </select>
          </lw-form-field> 
        </div>
        <!--  /address type  -->

        <!--  Address Line 1  -->
        <lw-form-field field-for="address_line_1" label="<?= __( 'Address 1' ) ?>"> 
            <input type="text"  
              class="lw-form-field form-control"
              name="address_line_1"
              ng-required="true" 
              ng-model="addressEditCtrl.addressData.address_line_1" />
        </lw-form-field>
        <!--  Address Line 1  -->

        <!--  Address Line 2  -->
        <lw-form-field field-for="address_line_2" label="<?= __( 'Address 2' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="address_line_2"
              ng-required="true" 
              ng-model="addressEditCtrl.addressData.address_line_2" />
        </lw-form-field>
        <!--  Address Line 2  -->

        <!--  City  -->
        <lw-form-field field-for="city" label="<?= __( 'City' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="city"
              ng-required="true" 
              ng-model="addressEditCtrl.addressData.city" />
        </lw-form-field>
        <!--  City  -->

        <!--  State  -->
        <lw-form-field field-for="state" label="<?= __( 'State' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="state"
              ng-required="true" 
              ng-model="addressEditCtrl.addressData.state" />
        </lw-form-field>
        <!--  State  -->
        
         <!--  Country  -->
        <lw-form-field field-for="country" label="<?= __( 'Country' ) ?>"> 
           <selectize config='addressEditCtrl.countries_select_config' class="lw-form-field" name="country" ng-model="addressEditCtrl.addressData.country" options='addressEditCtrl.countries' placeholder="<?=  __( 'Select Country' ) ?>" ng-required="true"></selectize>
        </lw-form-field>
        <!--  /Country  -->

        <!--  Pin Code  -->
        <lw-form-field field-for="pin_code" label="<?= __( 'Pin Code' ) ?>"> 
            <input type="text" 
              class="lw-form-field form-control"
              name="pin_code"
              ng-required="true" 
              ng-model="addressEditCtrl.addressData.pin_code" />
        </lw-form-field>
        <!--  Pin Code  -->

         <!--  Make Primary  -->
        <lw-form-checkbox-field field-for="make_primary" label="<?= __( 'Make Primary' ) ?>" title="<?= e( __('Make Primary') ) ?>">
            <input type="checkbox" 
                class="lw-form-field js-switch"
                name="make_primary"
                ng-model="addressEditCtrl.addressData.primary" 
                ui-switch=""/>
        </lw-form-checkbox-field>
        <!--  /Make Primary  -->
        
		<div class="lw-dotted-line"></div>

        <!--  Action button  -->
        <div class="form-group lw-form-actions">
        	<!--  update button  -->
            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
            <!--  update button  -->

            <!--  cancel button  -->
            <button type="button" ng-click="addressEditCtrl.close()" class="lw-btn btn btn-default" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
            <!--  cancel button  -->
        </div>
        <!--  /Action button  -->

    </form>
	<!--  /form action  -->
</div>