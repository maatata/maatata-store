<div ng-controller="OrderSettingsController as orderSettingsCtrl" class="col-lg-9">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="orderSettingsCtrl.[[ orderSettingsCtrl.ngFormName ]]" 
        ng-submit="orderSettingsCtrl.submit()" 
        novalidate>

		<!-- page Loader -->
	    <div class="lw-main-loader lw-show-till-loading" ng-if="orderSettingsCtrl.pageStatus == false">
	        <div class="loader"><?=  __('Loading...')  ?></div>
	    </div>
		<!-- /page Loader -->

	    <fieldset class="lw-fieldset-2">
	        <legend>
	              <?=  __('Order')  ?>
	        </legend>

	        <!-- Hide Sidebar On Order Summary Page -->
	        <lw-form-checkbox-field 
        		field-for="hide_sidebar_on_order_page" 
        		label="<?= __( 'Hide Sidebar On Order Submit Page.' ) ?>" ng-if="orderSettingsCtrl.pageStatus">
              	<input type="checkbox" 
                  class="lw-form-field js-switch"
                  ui-switch=""
                  name="hide_sidebar_on_order_page"
                  ng-model="orderSettingsCtrl.editData.hide_sidebar_on_order_page"/>
	        </lw-form-checkbox-field>
	        <!-- /Hide Sidebar On Order Summary Page -->
	  
	   </fieldset>


		<fieldset class="lw-fieldset-2">
			<legend>
				<?=  __('Payments')  ?>
			</legend>
		
			<div class='form-group'>

	            <!-- Use Paypal -->
		        <lw-form-checkbox-field field-for="use_paypal" label="<?= __( 'PayPal' ) ?>"  ng-if="orderSettingsCtrl.pageStatus">
		            <input type="checkbox" 
		                class="lw-form-field js-switch"
	                	ui-switch=""
	                	name="use_paypal"
	                	ng-model="orderSettingsCtrl.editData.use_paypal"/>
		        </lw-form-checkbox-field>
		        <!-- /Use Paypal -->
				
				<div ng-if="orderSettingsCtrl.editData.use_paypal">
		        <!-- Paypal Email -->
		        <lw-form-field field-for="paypal_email" label="<?= __( 'PayPal Email' ) ?>"> 
		            <input type="email" 
		                  class="lw-form-field form-control"
		                  name="paypal_email"
		                  ng-required="true" 
		                  ng-model="orderSettingsCtrl.editData.paypal_email"/>
		        </lw-form-field>
		        <!-- Paypal Email -->
		        </div>

				
		  	</div>
	  	
			<!-- Check Payment -->
	        <div>
	        	<!-- Check Payment checkbox -->
		        <lw-form-checkbox-field field-for="payment_check" label="<?= __( 'Check Payment' ) ?>"  ng-if="orderSettingsCtrl.pageStatus">
		            <input type="checkbox" 
		                class="lw-form-field js-switch"
	                	ui-switch=""
	                	name="payment_check"
	                	ng-model="orderSettingsCtrl.editData.payment_check"/>
		        </lw-form-checkbox-field>
		        <!-- /Check Payment checkbox -->
				
				<!-- Check Payment textarea -->
		        <div ng-if="orderSettingsCtrl.editData.payment_check == true">
			        <lw-form-field field-for="payment_check_text" label="<?= __( 'Check Payment Information' ) ?>"> 
			            <textarea name="payment_check_text" class="lw-form-field form-control" ng-required="true" cols="30" rows="10" ng-model="orderSettingsCtrl.editData.payment_check_text" lw-ck-editor></textarea>
			         </lw-form-field>
	         	</div>
	         	<!-- /Check Payment textarea -->
	        </div>
	        <!-- /Check Payment -->
			
			<!-- Bank Payment -->
	        <div>
	        	<!-- Bank Payment checkbox -->
		        <lw-form-checkbox-field field-for="payment_bank" label="<?= __( 'Bank Payment' ) ?>"  ng-if="orderSettingsCtrl.pageStatus">
		            <input type="checkbox" 
		                class="lw-form-field js-switch"
	                	ui-switch=""
	                	name="payment_bank"
	                	ng-model="orderSettingsCtrl.editData.payment_bank"/>
		        </lw-form-checkbox-field>
		        <!-- /Bank Payment checkbox -->

		        <!-- Bank Payment textarea -->
		        <div ng-if="orderSettingsCtrl.editData.payment_bank == true">
			        <lw-form-field field-for="payment_bank_text" label="<?= __( 'Bank Payment Information' ) ?>"> 
			            <textarea name="payment_bank_text" class="lw-form-field form-control" ng-required="true" cols="30" rows="10" ng-model="orderSettingsCtrl.editData.payment_bank_text" lw-ck-editor></textarea>
			         </lw-form-field>
	         	</div>
	         	<!-- /Bank Payment textarea -->
	        </div>
	        <!-- /Bank Payment -->
			
			<!-- COD Payment -->
	        <div>
	        	<!-- COD Payment checkbox -->
		        <lw-form-checkbox-field field-for="payment_cod" label="<?= __( 'COD Payment' ) ?>"  ng-if="orderSettingsCtrl.pageStatus">
		            <input type="checkbox" 
		                class="lw-form-field js-switch"
	                	ui-switch=""
	                	name="payment_cod"
	                	ng-model="orderSettingsCtrl.editData.payment_cod"/>
		        </lw-form-checkbox-field>
		        <!-- /COD Payment checkbox -->

		        <!-- COD Payment textarea -->
		        <div ng-if="orderSettingsCtrl.editData.payment_cod == true">
			        <lw-form-field field-for="payment_cod_text" label="<?= __( 'COD Payment Information' ) ?>"> 
			            <textarea name="payment_cod_text" class="lw-form-field form-control" ng-required="true" cols="30" rows="10" ng-model="orderSettingsCtrl.editData.payment_cod_text" lw-ck-editor></textarea>
			         </lw-form-field>
	         	</div>
	         	<!-- /COD Payment textarea -->
	        </div>
	       	<!-- /COD Payment -->

			<!-- Other Payment -->
	       <div>
	       		<!-- Use Submit Order by Email -->
	            <lw-form-checkbox-field field-for="payment_other" label="<?= __( 'Other' ) ?>" ng-if="orderSettingsCtrl.pageStatus">
	                <input type="checkbox" 
	                    class="lw-form-field js-switch"
	                    ui-switch=""
	                    name="payment_other"
	                    ng-model="orderSettingsCtrl.editData.payment_other"/>
	            </lw-form-checkbox-field>
	            <!-- /Use Submit Order by Email -->

	            <!-- ck editor for customer contact you after placed order -->
		        <div ng-if="orderSettingsCtrl.editData.payment_other">
			        <lw-form-field field-for="payment_other_text" label="<?= __( 'Other Order Payment Information') ?>"> 
			            <textarea name="payment_other_text" class="lw-form-field form-control" ng-required="true" cols="30" rows="10" ng-model="orderSettingsCtrl.editData.payment_other_text" lw-ck-editor></textarea>
			         </lw-form-field>
	         	</div>
         		<!-- /ck editor for customer contact you after placed order -->
	       </div>	
	       <!-- Other Payment -->
	    </fieldset>

		  	<!-- button --> 
			<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	        <!-- /button --> 
	</form>
	<!-- /form action -->
</div>