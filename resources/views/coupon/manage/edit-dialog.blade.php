<div ng-controller="CouponEditController as couponEditCtrl" class="lw-dialog">

    <!-- main heading -->
    <div class="lw-section-heading-block">
        <h3 class="lw-header"><?=  __( 'Edit Coupon' )  ?></h3>
    </div>
	<!-- /main heading -->

	<!-- form section -->
	<form class="lw-form lw-ng-form" 
			name="couponEditCtrl.[[ couponEditCtrl.ngFormName ]]" 
			ng-submit="couponEditCtrl.submit()" 
			novalidate>

			<!-- Title -->
			<lw-form-field field-for="title" label="<?= __( 'Title' ) ?>"> 
				<input type="text" 
					class="lw-form-field form-control"
					name="title"
					ng-required="true" 
					autofocus
					ng-model="couponEditCtrl.couponData.title" 
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
					ng-model="couponEditCtrl.couponData.code" 
				/>
			</lw-form-field>
			<!-- /Code -->

			<!-- Start Dates -->
			<lw-form-field field-for="start" label="<?= __( 'Start Date' ) ?>"> 
				<input type="text" 
						class="lw-form-field form-control lw-readonly-control"
						name="start"
						lw-bootstrap-md-datetimepicker
						ng-required="true" 
						ng-change="couponEditCtrl.endDateUpdated(couponEditCtrl.couponData.start)"
						options="[[ couponEditCtrl.startDateConfig ]]"
						readonly
						ng-model="couponEditCtrl.couponData.start" 
					/>
			</lw-form-field>
			<!-- /Start Dates -->
			
			<!-- end Dates -->
			<lw-form-field field-for="end" label="<?= __( 'End Date' ) ?>"> 
				<input type="text" 
						class="lw-form-field form-control lw-readonly-control"
						name="end"
						lw-bootstrap-md-datetimepicker
						ng-change="couponEditCtrl.endDateUpdated(couponEditCtrl.couponData.end)"
						ng-required="true" 
						options="[[ couponEditCtrl.startDateConfig ]]"
						readonly
						ng-model="couponEditCtrl.couponData.end" 
					/>
			</lw-form-field>
			<!-- /end Dates -->

			<!-- Discount Type -->
            <lw-form-field field-for="discount_type" label="<?= __('Discount Type') ?>"> 
                <select class="lw-form-field form-control" name="discount_type" ng-options="type.id as type.name for type in couponEditCtrl.discountType" ng-model="couponEditCtrl.couponData.discount_type" ng-required="true">
	            </select>
            </lw-form-field>
	        <!-- /Discount Type -->

	        <!-- Discount Type Amount-->
	        <div ng-if="couponEditCtrl.couponData.discount_type == 1">
            <lw-form-field field-for="discount" label="<?= __('Discount') ?>"> 
                <div class="input-group">
                	<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
				  	<input type="number" 
						class="lw-form-field form-control"
						name="discount"
						min="0.1"
						ng-required="true" 
						ng-model="couponEditCtrl.couponData.discount" />
						<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
            </div>
	        <!-- /Discount Type Amount-->

	         <!-- Discount Type percentage-->
	        <div ng-if="couponEditCtrl.couponData.discount_type == 2">
            <lw-form-field field-for="discount" label="<?= __('Discount') ?>"> 
                <div class="input-group">
						  	<input type="number" 
								class="lw-form-field form-control"
								name="discount"
								min="0.1"
								max="99"
								ng-required="true" 
								ng-model="couponEditCtrl.couponData.discount" />
								<span class="input-group-addon" id="basic-addon1">
									<span>%</span>
						  		</span>
						</div>
            </lw-form-field>
           	</div>
	        <!-- /Discount Type percentage-->

	        <!-- Max Discount Amount-->
	        <div  ng-if="couponEditCtrl.couponData.discount_type == 2">
            <lw-form-field field-for="max_discount" label="<?= __('Max Discount') ?>"> 
            	<div class="input-group">
            		<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						name="max_discount"
						ng-model="couponEditCtrl.couponData.max_discount" />
						<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
            </div>
	        <!-- /Max Discount Amount-->

	        <!-- Max Discount percentage-->
	        <div ng-if="couponEditCtrl.couponData.discount_type == 1" v-label="<?= __('Max Discount') ?>">
            <lw-form-field field-for="max_discount" label="<?= __('Max Discount in % of order price') ?>"> 
            	<div class="input-group">
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						max="99"
                        ng-required="couponEditCtrl.couponData.discount_type == 1"
						name="max_discount"
						ng-model="couponEditCtrl.couponData.max_discount" />
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
            		<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.amountSymbol"></span>
	                <input type="number" 
						class="lw-form-field form-control"
						min="0.1"
						name="minimum_order_amount"
						ng-required="true"
						ng-model="couponEditCtrl.couponData.minimum_order_amount" />
						<span class="input-group-addon" id="basic-addon1" ng-bind-html="couponEditCtrl.couponData.currency"></span>
				</div>
            </lw-form-field>
	        <!-- /Minimum Order Amount -->

			<!-- Description -->
            <lw-form-field field-for="description" label="<?= __('Description') ?>"> 
                <textarea name="description" class="lw-form-field form-control"
                 cols="10" rows="3" ng-model="couponEditCtrl.couponData.description"></textarea>
            </lw-form-field>
	        <!-- /Description -->

			<!-- Status -->
            <lw-form-checkbox-field field-for="active" label="<?= __( 'Status' ) ?>" title="<?= __( 'Status' ) ?>" advance="true">
                <input type="checkbox" 
                    class="lw-form-field js-switch"
                    name="active"
                    ng-model="couponEditCtrl.couponData.active"
                    ui-switch="" />
            </lw-form-checkbox-field>
			<!-- /Status -->
			
		<div class="lw-dotted-line"></div>

			<!-- action button -->
			<div class="lw-form-actions">
				<button type="submit" class="lw-btn btn btn-primary" title="<?= __('Update') ?>"><?= __('Update') ?> <span></span></button>
				<button type="button" class="lw-btn btn btn-default" ng-click="couponEditCtrl.closeDialog()" title="<?= __('Cancel') ?>"><?= __('Cancel') ?></button>
			</div>
			<!-- /action button -->
		</form>
		<!-- /form section -->
</div>