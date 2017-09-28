<!DOCTYPE html>
<html lang="<?php echo substr(CURRENT_LOCALE, 0, 2); ?>" class="lw-has-disabled-block">
<head>
    <title>
        <?= e( getStoreSettings('store_name') ) ?> : <?= __('Manage Store') ?>
    </title>
    @include('includes.head-content')
    <link href="<?= __yesset('dist/css/vendor-manage*.css') ?>" rel="stylesheet">
    <link href="<?= __yesset('dist/css/application*.css') ?>" rel="stylesheet"> 
</head> 
<body ng-app="ManageApp" ng-controller="ManageController as manageCtrl">
    {{-- Disabled loading block --}}
    <div class="lw-disabling-block">
        <div class="lw-processing-window lw-hidden">
            <div class="loader"><?=  __('Loading...')  ?></div>
            <div><?= __( 'Please wait while we are processing your request...' ) ?></div>
        </div>
    </div>
    {{-- /Disabled loading block --}}
    <noscript>
        <style>.nojs-msg { width: 50%; margin:20px auto}</style>
        <div class="custom-noscript">
            <div class="bs-callout bs-callout-danger nojs-msg">
              <h4><?= __('Oh dear... we are sorry') ?></h4>
              <em><strong><?= __('Javascript') ?></strong> <?= __('is disabled in your browser, To use this application please enable javascript &amp; reload page again.') ?></em>
            </div>
        </div>
    </noscript>
    
    <div class="lw-sidebar-overlay" data-toggle="offcanvas"></div>
    <div class="lw-smart-menu-container">
        <ul class="top-horizental-menu sm sm-clean lw-sm-menu">
            <li>
                <a class="lw-show-process-action" href="<?=  asset('/')  ?>" title="<?= __('Home') ?>"><?= __('Home') ?></a>
            </li>
            <li ui-sref-active="active">
                <a ui-sref="dashboard" title="<?= __('Dashboard') ?>"><?= __('Dashboard') ?></a>
            </li>
            <li class="navbar-right">
                <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user"></i>
                    <span ng-bind="manageCtrl.auth_info.profile.full_name"></span>
                </a>
                <ul>
                    <li ui-sref-active="active">
                        <a href ui-sref="profile" title="<?=  __('Profile')  ?>">
                            <?=  __('Profile')  ?>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a ui-sref="changePassword" title="<?=  __('Change Password')  ?>">
                            <?=  __('Change Password')  ?>
                        </a>
                    </li>
                    <li ui-sref-active="active">
                        <a ui-sref="changeEmail" title="<?= __('Change Email') ?>">
                            <?= __('Change Email') ?>
                        </a>
                    </li>
                    <li ui-sref-active="active">
						<a href="<?=  route('user.address.list')  ?>" title="<?= __('Address') ?>">		<?= __('Address') ?>
						</a>
					</li>
					<li ui-sref-active="active">
						<a href="<?=  route('cart.order.list')  ?>" title="<?= __('My Orders') ?>">		<?= __('My Orders') ?>
						</a>
					</li>
                    <li ui-sref-active="active">
                        <a href="<?=  route('user.logout')  ?>" title="<?= __('Logout') ?>">
                            <?= __('Logout') ?> 
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </li>
                </ul>
            </li>
           	<li class="navbar-right">@include('locale-menu')</li>
            <li>
                <a class="item visible-xs lw-special-item" href data-toggle="offcanvas">
                    <i class="sidebar icon"> </i>
                        <?=  __( 'Menu' )  ?>
                </a>
            </li>
        </ul>
    </div>
@if(isDemoForAdmin())
<div class="alert alert-warning lw-row">
  <center><strong><?= __('Please Note: ') ?></strong><?= __('Demonstration purposes only. No Order will be executed and Saving functionality is disabled') ?></center>
</div>
@endif
    <div>
        <!-- container -->
        <div class="container-fluid lw-page-main-container">
            <div class="row-offcanvas row-offcanvas-left hide-till-load">
                
                <div class="col-xs-8 col-sm-4 col-md-4 col-lg-3 sidebar-offcanvas">
                    <div class="lw-sidebar-menu">        
                         @include('includes.manage-sidebar')
                    </div>
                </div>
                <!--/.sidebar-offcanvas-->
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-9">
                    <div class="lw-main-component-page">
                        <div class="lw-sub-component-page ui-view-container">
                            <div class="lw-component-content master-view" ui-view></div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="lw-main-loader lw-show-till-loading">
                <div class="loader"><?=  __('Loading...')  ?></div>
            </div>
        </div>
    
    @push("vendorScripts")
        <script src="<?= __yesset('dist/ckeditor/ckeditor*.js') ?>"></script>
        <script src="<?= __yesset('dist/js/vendor-manage*.js') ?>"></script>
    @endpush
    
    @push("appScripts")
        <script src="<?= __yesset('dist/js/manage-app*.js') ?>"></script> 
    @endpush

    @include('includes.foot-content')
</body>
</html>
