{{-- [[ CartOrderCtrl.couponData ]] --}}
	
{{-- for successfully coupon message --}}
	<ol class="breadcrumb" ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true">
		<li>
			<span>
				{{-- coupon successfully message --}}
				<h4><?= __('Coupon Code ') ?>
					<span class="label label-default" ng-bind="CartOrderCtrl.couponData.couponCode"></span> 
					{{-- if coupon applied successfully --}}
					<span>
						<?= __(' applied') ?>
					</span>
					{{-- /if coupon applied successfully --}}

					{{-- coupon remove button --}}
					<button class="btn btn-danger btn-xs" ng-click="CartOrderCtrl.removeCoupon()" 
					title="<?=  __('Remove')  ?>"> <?=  __('Remove')  ?>
					</button>
					{{-- /coupon remove button --}}
				</h4>
				{{-- /coupon successfully message --}}
			</span>
		</li>
	</ol>
	{{-- for successfully coupon message --}}

	{{-- if coupon code applied successfully then show this message --}}
	<div class="alert alert-info" ng-if="CartOrderCtrl.couponStatus == 1 && CartOrderCtrl.couponMessage == true">
    	{{-- coupon title --}}
    	<div>
    		<?= __('Title: ') ?><span ng-bind="CartOrderCtrl.couponData.title"></span>
    	</div>
    	{{-- /coupon title --}}

    	{{-- coupon discription --}}
    	<div>
    		<?= __('Description: ') ?><span ng-bind="CartOrderCtrl.couponData.description"></span>
    	</div>
    	{{-- /coupon discription --}}

    	{{-- coupon discount allowed --}}
    	<div>
    		<?= __('Discount Allowed: ') ?><span ng-bind="CartOrderCtrl.couponData.formattedDiscount"></span>
    	</div>
    	{{-- /coupon discount allowed --}}
  	</div>
  	{{-- /if coupon code applied successfully then show this message --}}
	
	{{-- /if coupon code is invalid then show this message --}}
  	<div class="alert alert-danger" ng-if="CartOrderCtrl.couponStatus == 2 && CartOrderCtrl.couponMessage == true">
		{{-- coupon invalid message --}}
		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    <div>
	    	<?= __('Please check your coupon code and enter again.') ?>
	    </div>
	    {{-- coupon invalid message --}}
	</div>
	{{-- /if coupon code is invalid then show this message --}}
	
	{{-- /if coupon code is invalid then show this message --}}
  	<div class="alert alert-danger" ng-if="CartOrderCtrl.couponStatus == 9 && CartOrderCtrl.couponMessage == true">
  		<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    <div>
	    	<span ng-bind="CartOrderCtrl.validCouponAmtMessage"></span>
	    </div>
	</div>
	{{-- /if coupon code is invalid then show this message --}}	
	
<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12" ng-hide="CartOrderCtrl.couponStatus == 1">
	
	{{-- Input-group for coupon code --}}
	<div >

		<!-- Code here -->
        <lw-form-field field-for="couponCode" label="<?= __( 'Code' ) ?>">

				<div class="input-group">

					<input type="text" 
		              	class="lw-form-field form-control"
		              	name="couponCode"	  
			  		   	placeholder="<?= __('Code here.') ?>" 
			  		   	autocomplete="off"
			  		   	ng-model="CartOrderCtrl.orderData.code" />

						<span class="input-group-btn">
					  		{{-- apply button --}}
					    	<button  
					    	ng-disabled="!CartOrderCtrl.orderData.code" 
					    	class="btn btn-warning" 
					    	ng-click="CartOrderCtrl.applyCoupon(CartOrderCtrl.orderData.code, CartOrderCtrl.orderData.cartTotalPrice)" 
					    	title="<?=  __('Apply')  ?>"><?=  __('Apply')  ?>
					    	</button>
					    	{{-- /apply button --}}
					  	</span>
                </div>
            
        </lw-form-field>
        <!-- /Code here -->
		
	</div>
	{{-- /Input-group for coupon code --}}
</div>
{{-- formatted discount --}}
<div class="pull-right" ng-if="CartOrderCtrl.couponStatus == 1">
	- <span ng-bind="CartOrderCtrl.couponData.formattedDiscount"></span>
</div>
{{-- /formatted discount --}}