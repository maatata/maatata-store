<!-- start sidebar nav --> 
<div role="navigation" class="custom-sidebar-master navbar-left col-sm-12 col-md-12 col-lg-12">
	<div class="sidebar-nav lw-sidebar-inner">
        <div class="lw-sidebar-search-container hidden-xs">
            @include('includes.search-panel')
        </div>
		
		{{-- This section dislay on mobile view --}}
		<div class="lw-smart-menu-container"> 
			<div class="panel panel-default visible-xs">
			    <div class="panel-body lw-sidebar-list-panel-body">
					<!-- Right nav -->
					<ul class="top-horizental-menu sm sm-clean sm-vertical list-group lw-sidebar-list-menu">

						<?= buildNevigationMenu($menuData['navMenuData']) ?>
						
					  	@if (isAdmin())
							<li class="<?= isActiveRoute('manage.app') ?> navbar-right">
								<a href="<?=  route('manage.app')  ?>" title="<?= __( 'Manage Store' ) ?>">
									<i class="fa fa-cogs"></i> <?=  __('Manage Store')  ?>
								</a>
							</li>
						@endif

						<!-- Menu List -->
						@if (isLoggedIn())
					      	<li>
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
										<a href="<?=  route('user.address.list')  ?>" title="<?= __('Address') ?>"><?= __('Address') ?></a>
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
					    <li>
					   	 	@include('locale-menu')
					    </li>
					</ul>
				</div>
			</div>
		</div>
		{{--/ This section dislay on mobile view --}}

		{{-- sidebar menu this block contain categories & brand  --}}
		<div class="lw-smart-menu-container">
            @if(getStoreSettings('categories_menu_placement') != 4)
		    <div class="panel panel-default <?= (!__isEmpty($menuData['sideBarCategoriesMenuData']) and (getStoreSettings('categories_menu_placement') == 2)) ? 'visible-xs' : '' ?>">
			    <div class="panel-heading">
			        <h3 class="panel-title">
			        	<?= __("Categories") ?>
			        	<button style="z-index: 0;" type="button" title="Go" class="btn btn-primary apply-selected-categories">Go</button>
			        </h3>
			    </div>
			    <div class="panel-body lw-sidebar-list-panel-body">
					<ul class="top-horizental-menu list-group lw-sidebar-list-menu">
						@include('includes.dynamic-sidebar-menu')
					</ul>
			    </div>
		    </div>
            @endif
            @if(getStoreSettings('brand_menu_placement') != 4) <!-- dont't show  brand menu-->
				@if(!__isEmpty($menuData['sibeBarBrandMenuData'])) <div class="panel panel-default <?= getStoreSettings('brand_menu_placement') == 2 ? 'visible-xs' : '' ?>">
					  	<div class="panel-heading">
					    	<h3 class="panel-title"><a href="<?=  route('fetch.brands')  ?>"><?= __("Shop by Brands") ?></a></h3>
					  	</div>
					  	<div class="panel-body">

						    <ul class="top-horizental-menu sm sm-clean sm-vertical lw-sidebar-zindex">
						    	@foreach($menuData['sibeBarBrandMenuData'] as $brand)
									<li><a href="<?=  route('product.related.by.brand', [$brand['_id'], $brand['slugName']])  ?>" title="<?=  $brand['name']  ?>" ><?=  $brand['name']  ?></a></li>
								@endforeach
							</ul> 
					  	</div>
					</div>
                 @endif
			@endif
		</div>
		{{--/sidebar menu this block contain categories & brand  --}}
	</div>
</div>