<div id="wrapper" ng-controller="DashboardController as dashboard">
    <div id="page-wrapper">
    	<!-- main heading -->
        <div class="lw-section-heading-block">
        	<h3 class="lw-section-heading">
        	<i class="fa fa-tachometer"></i> <?= __('Dashboard') ?>
        	</h3>
        </div>
        <?php /*
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= __( 'Manage Shop' ) ?>
            </div>
            <div class="panel-body lw-dashboard-icon-links">
                <a ui-sref="store_settings_edit">
                    <i class="fa fa-cog fa-5x"></i>
                    <span><?=  __('Basic Configuration')  ?></span>
                </a>
                 <a title="<?=  __('Shipping Rules')  ?>" ui-sref="shippings">
                    <i class="fa fa-plane fa-5x"></i> 
                    <span><?= __('Shipping Rules') ?></span>
                </a>
                <a title="<?=  __('Tax Rules')  ?>" ui-sref="taxes">
                    <i class="fa fa-minus fa-3x"></i><i class="fa fa-percent fa-5x"></i>
                    <span><?=  __('Tax Rules')  ?></span>
                </a>
                <a title="<?=  __('Coupons')  ?>" ui-sref="coupons.current">
                    <sub><i class="fa fa-scissors fa-3x"></i></sub><i class="fa fa-usd fa-5x"></i>
                    <span><?=  __('Coupons')  ?></span>
                </a>
            </div>
        </div>
        */ ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= __( 'Manage Products' ) ?>
            </div>
            <div class="panel-body lw-dashboard-icon-links">
                <a title="<?=  __('Brands')  ?>" ui-sref="brands">
                    <i class="fa fa-bold fa-5x"></i>
                    <span><?=  __('Brands')  ?></span>
                </a>
                <a title="<?=  __('Categories & Products')  ?>" ui-sref="categories({mCategoryID:''})">
                        <i class="fa fa-th-large fa-5x"></i>
                        <span><?=  __('Categories & Products')  ?></span>
                    </a>
            </div>
        </div>
        <?php /*
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= __( 'Reports &amp; Orders' ) ?>
            </div>
            <div class="panel-body lw-dashboard-icon-links">
                <a title="<?=  __('Orders')  ?>" ui-sref="orders.active">
                    <i class="fa fa-list fa-5x"></i>                    
                    <sup ng-show="manageCtrl.newOrderPlacedCount != 0" class="label label-warning" ng-bind="manageCtrl.newOrderPlacedCount"></sup>
                    <span><?=  __('Orders')  ?></span>
                </a>
                <a title="<?=  __('Reports')  ?>" ui-sref="reports">
                    <i class="fa fa-list-alt fa-5x" ></i> 
                    <span><?=  __('Reports')  ?></span>
                </a>
                <a title="<?=  __('Order Payments')  ?>" ui-sref="payments">
                <i class="fa fa-money fa-5x"></i> 
                    <span><?=  __('Order Payments')  ?></span>
                </a>
            </div>
        </div>
        */ ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= __("Other ") ?>
            </div>
            <div class="panel-body lw-dashboard-icon-links">
                <?php /*
                <a title="<?=  __('Pages')  ?>" ui-sref="pages({parentPageID:''})">
                    <i class="fa fa-files-o fa-5x"></i>
                    <span><?=  __('Pages')  ?></span>
                </a>
                */ ?>
                <a title="<?=  __('Users')  ?>" ui-sref="users">
                    <i class="fa fa-users fa-5x"></i>
                    <span><?=  __('Users')  ?></span>
                </a>
            </div>
        </div>
	</div>
</div>