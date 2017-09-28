<div ng-controller="CouponAddController as couponAddCtrl" class="lw-dialog">
    
    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Add Coupon' )  ?></h3>
    </div>
	<!-- /main heading -->
	
	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="couponAddCtrl.[[ couponAddCtrl.ngFormName ]]" 
			ng-submit="couponAddCtrl.submit()" 
			novalidate>

			<!-- Title -->
			<lw-form-field field-for="title" label="<?= __( 'Title' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="title"
					ng-required="true" 
					autofocus
					ng-model="couponAddCtrl.couponData.title" 
				/>
			</lw-form-field>
			<!-- /Title -->
			
			<!-- Code -->
			<lw-form-field field-for="code" label="<?= __( 'Code' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="code"
					ng-required="true"
					min="3"
					max="10"
					ng-model="couponAddCtrl.couponData.code" 
				/>
			</lw-form-field>
			<!-- /Code -->

			<!-- Start Dates -->
			<lw-form-field field-for="start" label="<?= __( 'Start Date' ) ?>"> 
				<input type="text" 
						class="lw-form-field form-control lw-readonly-control"
						name="start"
						id="start"
						lw-bootstrap-md-datetimepicker
						ng-required="true" 
						ng-change="couponAddCtrl.endDateUpdated(couponAddCtrl.couponData.start)"
						options="[[ couponAddCtrl.startDateConfig ]]"
						readonly
						ng-model="couponAddCtrl.couponData.start" 
					/>
			</lw-form-field>
			<!-- /Start Dates -->

			<!-- end Dates -->
			<lw-form-field field-for="end" label="<?= __( 'End Date' ) ?>"> 
				<input type="text" 
						class="lw-form-field form-control lw-readonly-control"
						name="end"
						id="end"
						lw-bootstrap-md-datetimepicker
						ng-change="couponAddCtrl.endDateUpdated(couponAddCtrl.couponData.end)"
						options="[[ couponAddCtrl.endDateConfig ]]"
						ng-required="true" 
						readonly
						ng-model="couponAddCtrl.couponData.end" 
					/>
			</lw-form-field>
			<!-- /end Dates -->

			<!-- Discount Type -->
            <lw-form-field field-for="discount_type" label="<?= __('Discount Type') ?>"> 
                <select class="lw-form-field form-control" 
	                name="discount_type" ng-model="couponAddCtrl.couponData.discount_type" ng-options="type.id as type.name for type in couponAddCtrl.discountType" ng-required="true">
	            </select>
            </lw-form-field>
	        <!-- /Discount Type -->

	        <!-- Discount Type Amount-->
	        <div ng-if="couponAddCtrl.couponData.discount_type == 1">	
            <lw-form-field field-for="discount" label="<?= __('Discount') ?>"> 
                <div class="input-group">
                	<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="discount"
						min="0.1"
						ng-required="true" 
						ng-model="couponAddCtrl.couponData.discount" />
						<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
            </div>
	        <!-- /Discount Type Amount-->

	        <!-- Discount Type Percentage-->
	        <div ng-if="couponAddCtrl.couponData.discount_type == 2">
            <lw-form-field field-for="discount" label="<?= __('Discount') ?>"> 
                <div class="input-group">
						  	<input type="number" 
								class="lw-form-field form-control"
								name="discount"
								min="0.1"
								max="99"
								ng-required="true" 
								ng-model="couponAddCtrl.couponData.discount" />
								<span class="input-group-addon" id="basic-addon1">
									<span>%</span>
						  		</span>
						</div>
            </lw-form-field>
            </div>
	        <!-- /Discount Type Percentage-->


	        <!-- Max Discount amount-->
	        <div ng-if="couponAddCtrl.couponData.discount_type == 2">
            <lw-form-field field-for="max_discount" label="<?= __('Max Discount') ?>"> 
	            <div class="input-group">
	            	<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						name="max_discount"
						ng-model="couponAddCtrl.couponData.max_discount" />
						<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
            </div>
	        <!-- /Max Discount amount-->

	        <!-- Max Discount percentage-->
	        <div ng-if="couponAddCtrl.couponData.discount_type == 1" v-label="<?= __('Max Discount') ?>">
            <lw-form-field field-for="max_discount" label="<?= __('Max Discount in % Of Order Price') ?>"> 
	            <div class="input-group">
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						max="99"
                        ng-required="true"
						name="max_discount"
						ng-model="couponAddCtrl.couponData.max_discount" />
						<span class="input-group-addon" id="basic-addon1">
								<span>%</span>
						</span>
				</div>
            </lw-form-field>
            </div>
	        <!-- /Max Discount percentage-->

	        <!-- Minimum Order Amount -->
            <lw-form-field field-for="minimum_order_amount" label="<?= __('Minimum Order Amount') ?>"> 
	            <div class="input-group">
	            	<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.amountSymbol"></span>
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						name="minimum_order_amount"
						ng-required="true"
						ng-model="couponAddCtrl.couponData.minimum_order_amount" />
					<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponAddCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
	        <!-- /Minimum Order Amount -->

			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="couponAddCtrl.couponData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="couponAddCtrl.couponData.active"
                    ui-switch="" />
            </lw-form-checkbox-field>
            <!-- /Status -->

		    <div class="lw-dotted-line"></div>	
		    		
			<!-- action button -->
			<div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Add') ?>"><?= __('Add') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="couponAddCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action button -->
		</form>
		<!-- /form section -->

</div>