<div class="col-lg-12 text-center lw-order-success-section">
		<i class="fa fa-check-square-o fa-5x lw-success"></i>
		<h1> @section('page-title', __('Order Success')) <?=  __('Success!')  ?></h1>
        <?php $trInfo = [
            "__fullName__" => Auth::user()->fname . ' ' . Auth::user()->lname,
            "__orderID__" => $success['order_uid']
        ]; ?>
		<h4><?=  __('Hi __fullName__', $trInfo)  ?></h4>
		<h4>
			@if (getStoreSettings('msg_on_order_submit'))
				<?= e( getStoreSettings('msg_on_order_submit') ) ?>
			@else
				<?= __('Your order has been placed successfully!!') ?>
			@endif
		</h4>
        <h5><?= __("Order ID: __orderID__", $trInfo) ?></h5>
		<h4> <a href="<?= route('my_order.details', $success['order_uid'])  ?>"><?=  __('Click here to see order details') ?></a>
		</h4>
		@if (Session::has('successMessage')) 
    		<?= Session::forget('successMessage') ?>
    	@endif
</div>