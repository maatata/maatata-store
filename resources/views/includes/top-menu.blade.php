<div class="lw-smart-menu-container">
	<!-- Right nav -->
	<ul class="top-horizental-menu sm sm-clean lw-sm-menu hide-till-load hidden-xs">

		@include('includes.dynamic-nevigation-menu')

		@if (!isCurrentRoute('cart.view') and !isCurrentRoute('order.summary.view'))
			<li>
	            <a ng-href ng-if="publicCtrl.loadPage == 1" 
	                ng-click="publicCtrl.openCartDialog(publicCtrl.routeStatus)" 
	                class="lw-shopping-cart-btn btn btn-default pull-right" 
	                ng-bind-html="publicCtrl.cart_string">
	                <span  ng-if="!publicCtrl.loadPage">
	                    <i class="fa fa-spinner fa-spin"></i> <?=  __('Loading ..')  ?>
	                </span>
	            </a>
	        </li>
        @endif
		
	  	@if (isAdmin() and isLoggedIn())
			<li class="<?= isActiveRoute('manage.app') ?> navbar-right lw-show-process-action">
				<a href="<?=  route('manage.app')  ?>" title="<?= __( 'Manage Store' ) ?>">
					<i class="fa fa-cogs"></i> <?=  __('Manage Store')  ?>
				</a>
			</li>
		@endif

		<!-- Menu List -->
		@if (isLoggedIn())
	      	<li class="navbar-right">
	            <a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
					<i class="fa fa-user"></i> <span ng-bind="publicCtrl.auth_info.profile.full_name"></span> 
				</a>
		        <ul>
		          <li class="<?= isActiveRoute('user.profile.update') ?>">
						<a href="<?=  route('user.profile')  ?>" title="<?=  __('Profile')  ?>"><?=  __('Profile')  ?></a>
					</li>
					<li class="<?= isActiveRoute('user.change_password') ?>">
						<a href="<?=  route('user.change_password')  ?>" title="<?=  __('Change Password')  ?>"><?=  __('Change Password')  ?></a>
					</li>
					<li class="<?= isActiveRoute('user.change_email') ?>">
					<a href="<?=  route('user.change_email')  ?>" title="<?= __('Change Email') ?>"><?= __('Change Email') ?></a>
					</li>
					<li class="<?= isActiveRoute('user.address.list') ?>">
						<a href="<?=  route('user.address.list')  ?>" title="<?= __('Addresses') ?>"><?= __('Addresses') ?></a>
					</li>
					<li class="<?= isActiveRoute('cart.order.list') ?>">
						<a href="<?=  route('cart.order.list')  ?>" title="<?= __('My Orders') ?>"><?= __('My Orders') ?></a>
					</li>
	                <li class="<?= isActiveRoute('user.logout') ?>">
						<a href="<?=  route('user.logout')  ?>" title="<?= __('Logout') ?>"><?= __('Logout') ?> <i class="fa fa-sign-out"></i></a>
					</li>
		        </ul>
	      	</li>
	  	@endif
	  	<!-- /Menu List -->

	  	<li class="navbar-right">
	   	 	@include('locale-menu')
	    </li>	    
	</ul>
</div>