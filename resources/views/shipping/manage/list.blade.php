<div ng-controller="ShippingListController as shippingListCtrl">

		<div class="lw-section-heading-block">
	        <!-- main heading -->
	        <h3 class="lw-section-heading">
				<span>
		        	<?= __( 'Manage Shipping Rules' ) ?>
		        </span>
	        </h3>
	        <!-- /main heading -->
	    </div>
		<!-- tab heading -->
		<ul class="nav nav-tabs lw-tabs" role="tablist" id="manageShippingTab">
			<li role="presentation" class="specificCountry">
				<a href="#specificCountry" role="tab" title="<?= __( 'Specific Countries' ) ?>" aria-controls="specificCountry" data-toggle="tab">
					<?=  __('Specific Countries')  ?>
				</a>
			</li>
				
			<li class="allOtherCountries">
				<a href="#allOtherCountries" role="tab" title="<?= __( 'All Other Countries' ) ?>" aria-controls="allOtherCountries" data-toggle="tab">
					<?=  __('All Other Countries')  ?>
				</a>
			</li>
		</ul>
		<br>
		<!-- /tab heading -->
		<div class="tab-content lw-tab-content">

			<div role="tabpanel" class="tab-pane fade in specificCountry" id="specificCountry">
				
			    <!-- datatable container -->
			    <div>
			    	<a class="btn btn-sm btn-default pull-right" title="<?= __( 'Add New Shipping Rule' ) ?>" ui-sref="shippings.add()"><i class="fa fa-plus"></i> <?= __( 'Add New Shipping Rule' ) ?></a><br><br>
			        <!-- datatable -->
			        <table class="table table-striped table-bordered" id="manageShippingList" cellspacing="0" width="100%">
			            <thead class="page-header">
			                <tr>
			                    <th><?=  __('Country')  ?></th>
			                    <th><?=  __('Type')  ?></th>
			                    <th><?=  __('Charges')  ?></th>
			                    <th><?=  __('Date')  ?></th>
			                    <th><?=  __('status')  ?></th>
			                    <th><?=  __('Action')  ?></th>
			                </tr>
			            </thead>
			            <tbody></tbody>
			        </table>
			        <!-- /datatable -->
			    </div>
			    <!-- /datatable container -->	
			    <div ui-view></div>
			</div>
			<div role="tabpanel" class="tab-pane fade" id="allOtherCountries">
				<!-- form action -->
				<form class="lw-form lw-ng-form col-lg-6" 
						name="shippingListCtrl.[[ shippingListCtrl.ngFormName ]]" 
						ng-submit="shippingListCtrl.submit()" 
						novalidate>
						
						<!-- Note -->
						<div class="alert alert-info alert-dismissible">
							<span>
								<strong><?= __( 'Please Note : ' ) ?> </strong>
								<?=  __('This Shipping Rule will be applicable to all other countries which are not listed in __specificCountry__ tab.', [
                                    '__specificCountry__' => '<strong>'.__('Specific Countries').'</strong>'
                                ]) ?>
							</span>
						</div>
						<!-- /Note -->

						<!-- Type -->
		        		<lw-form-field field-for="type" label="<?= __('Type / Availability') ?>"> 
			                <select class="lw-form-field form-control" 
				                name="type" ng-model="shippingListCtrl.shippingData.type" ng-options="type.id as type.name for type in shippingListCtrl.shippingType" ng-required="true">
				            </select>
		            	</lw-form-field>
		        		<!-- /Type -->

		        		<!-- Charges -->
						<lw-form-field field-for="charges" label="<?= __( 'Charges' ) ?>" ng-if="shippingListCtrl.shippingData.type != 3 && shippingListCtrl.shippingData.type != 4"> 
							<div class="input-group">
							  	<span class="input-group-addon" id="basic-addon1" ng-if="shippingListCtrl.shippingData.type == 1">[[shippingListCtrl.currencySymbol]]</span>
							  	<input type="number" 
									class="lw-form-field form-control"
									name="charges"
									ng-required="true"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.charges" 
								/>
							  	<span class="input-group-addon" id="basic-addon2" ng-if="shippingListCtrl.shippingData.type == 1">[[shippingListCtrl.currency]]</span>
							  	<span class="input-group-addon" id="basic-addon2" ng-if="shippingListCtrl.shippingData.type == 2">%</span>
							</div>
						</lw-form-field>
						<!-- /Charges -->

						<!-- Free After Amount -->
						<lw-form-field field-for="free_after_amount" label="<?= __( 'Free Shipping if Order Amount More than' ) ?>" ng-if="shippingListCtrl.shippingData.type == 1"> 
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1" >[[shippingListCtrl.currencySymbol]]</span>
								<input type="number" 
									class="lw-form-field form-control"
									name="free_after_amount"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.free_after_amount" 
								/>
								<span class="input-group-addon">[[shippingListCtrl.currency]]</span>
							</div>
						</lw-form-field>
						<!-- /Free After Amount -->

						<!-- Maximum Shipping Amount -->
						<lw-form-field field-for="amount_cap" label="<?= __( 'Maximum Shipping Amount' ) ?>" ng-if="shippingListCtrl.shippingData.type == 2"> 
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1" >[[shippingListCtrl.currencySymbol]]</span>
								<input type="number" 
									class="lw-form-field form-control"
									name="amount_cap"
									min="0.1"
									ng-model="shippingListCtrl.shippingData.amount_cap" 
								/>
								<span class="input-group-addon">[[shippingListCtrl.currency]]</span>
							</div>
						</lw-form-field>
						<!-- /Maximum Shipping Amount -->
						<!-- notes -->
						<lw-form-field field-for="notes" label="<?= __( 'Notes' ) ?>"> 
							<textarea name="notes" class="lw-form-field form-control"
			                 cols="10" rows="3" ng-model="shippingListCtrl.shippingData.notes"></textarea>
						</lw-form-field>
						<!-- /notes -->
						
						<!-- action -->
			            <div class="lw-form-actions">
							<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
						</div>
						<!-- /action -->

				</form>
				<!-- form action -->
			</div>
		</div>

</div>

<!-- place on date column _template -->
<script type="text/template" id="creationDateColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.creation_date %></span>
   
</script>
<!-- /place on date column _template -->

<!-- type column _template -->
<script type="text/template" id="typeColumnTemplate">

   <span class="custom-page-in-menu"><%-__tData.type %></span>
   
</script>
<!-- /type column _template -->

<!-- country column _template -->
<script type="text/template" id="countryColumnTemplate">

   <span class="custom-page-in-menu"><a href ng-click="shippingListCtrl.detailDialog('<%- __tData._id %>')" title="<%-__tData.country %>"><%-__tData.name %></a></span>
   
</script>
<!-- /country column _template -->


<!--  list row status column  _template -->
<script type="text/template" id="statusColumnTemplate">
 	<% if (__tData.status === 1) { %> 
        <span title="<?= __( 'Active' ) ?>"><i class="fa fa-eye"></i></span>
   <% } else { %>
    <span title="<?= __( 'Inactive' ) ?>"><i class="fa fa-eye-slash"></i></span>
   <% } %>
</script>
<!--  list row status column  _template -->

<!-- list row action column  _template -->
<script type="text/template" id="columnActionTemplate">
 	<a href ui-sref="shippings.edit({shippingID:<%- __tData._id %>})" class="btn btn-default btn-xs" title="<?= __( 'Edit' ) ?>">
 		<i class="fa fa-pencil-square-o"></i> <?= __( 'Edit' ) ?>
 	</a>
 	<a href="" ng-click="shippingListCtrl.delete('<%-__tData._id %>','<%-__tData.country %>')" class="btn btn-danger btn-xs" title="<?= __( 'Delete' ) ?>">
 		<i class="fa fa-trash-o fa-lg"></i> <?= __( 'Delete' ) ?>
 	</a>
</script>
<!-- list row action column  _template -->