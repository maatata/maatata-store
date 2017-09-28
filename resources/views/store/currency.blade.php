<div ng-controller="CurrencySettingsController as currencySettingsCtrl" class="col-lg-9">
	
	<!-- form action -->
	<form class="lw-form lw-ng-form" 
		name="currencySettingsCtrl.[[ currencySettingsCtrl.ngFormName ]]" 
        ng-submit="currencySettingsCtrl.submit()" 
        novalidate>

		<!-- page Loader -->
		<div class="lw-main-loader lw-show-till-loading" 
			ng-if="currencySettingsCtrl.pageStatus == false">
        	<div class="loader"><?=  __('Loading...')  ?></div>
    	</div>
		<!-- page Loader -->

		<!-- Currency -->
		<div ng-if="currencySettingsCtrl.pageStatus">
	        <div class="form-group">
		        <lw-form-selectize-field field-for="currency" label="<?= __( 'Currency' ) ?>" class="lw-selectize">
		            <selectize config='currencySettingsCtrl.currencies_select_config' class="lw-form-field" name="currency" ng-model="currencySettingsCtrl.editData.currency" options='currencySettingsCtrl.currencies_options' placeholder="<?= __( 'Select Currency' ) ?>" ng-required="true"  ng-change="currencySettingsCtrl.currencyChange(currencySettingsCtrl.editData.currency)"></selectize>
		        </lw-form-selectize-field>
	        </div>
	        <!-- /Currency -->

	        <!-- Currency Value -->
	        <lw-form-field field-for="currency_value" label="<?= __( 'Currency Code' ) ?>"> 
	            <input type="text" 
	                  class="lw-form-field form-control"
	                  name="currency_value"
	                  ng-required="true"
	                  ng-change="currencySettingsCtrl.currencyValueChange(currencySettingsCtrl.editData.currency_value)" 
	                  ng-model="currencySettingsCtrl.editData.currency_value"/>
	        </lw-form-field>
	        <!-- Currency Value -->

	        <div ng-hide="currencySettingsCtrl.is_support_paypal" class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> <?= __( 'Currency may not supported by PayPal' ) ?></div>

	        <!-- Currency Symbol -->
	        <lw-form-field field-for="currency_symbol" label="<?= __( 'Currency Symbol' ) ?>"> 
	        	<div class="input-group">
	            	<input type="text" 
	                  class="lw-form-field form-control"
	                  name="currency_symbol"
	                  ng-required="true" 
	                  ng-model="currencySettingsCtrl.editData.currency_symbol"/>
	                  <span class="input-group-addon" ng-bind-html="currencySettingsCtrl.editData.currency_symbol"></span>
	                </div>
	        </lw-form-field>
	        <!-- Currency Symbol --><br>

	        <span class="pull-right"><?= __('Refer for') ?> <a href="http://goo.gl/zRJRq" target="_blank"><?= __('ASCII Codes') ?></a></span>
		  
		    
			<!-- Update -->
		  	<div class="form-group">
	            <button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>">
	            <?= __('Update') ?> <span></span></button>
	        </div>
	        <!-- /Update -->
        </div>
	</form>
	<!-- /form action -->
</div>