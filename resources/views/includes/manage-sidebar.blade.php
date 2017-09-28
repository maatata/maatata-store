<div ng-show="manageCtrl.auth_info.authorized == true && manageCtrl.auth_info.designation == 1" role="navigation" class="custom-sidebar-master navbar-left col-sm-12 col-md-12 col-lg-12">
    <div class="well sidebar-nav lw-sidebar-inner">
    	<div class="panel panel-default">
        <div class="panel-heading lw-links-container">
            <ul class="nav custom-sidebar">
                    <li>
                <a ui-sref="dashboard" ui-sref-active="active-nav" class="lw-item-link"><h4><i class="fa fa-tachometer"></i> <?= __("Dashboard") ?></h4></a>
                </li>
            </ul>
            </div>
			<div class="panel-heading">
		    	<h3 class="panel-title"><i class="fa fa-cogs"></i> <?= __("Manage Shop") ?></h3>
		    </div>
    
			<div class="panel-body lw-links-container">
				<ul class="nav custom-sidebar">
                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Basic Configuration')  ?>" ui-sref="store_settings_edit">
                        <i class="fa fa-cog"></i> <?=  __('Basic Configuration')  ?>
                    </a>
                    </li>
				  	<?php /*
                    <li>
                		<a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Shipping Rules')  ?>" ui-sref="shippings">
                            <i class="fa fa-plane"></i> <?=  __('Shipping Rules')  ?>
                        </a>
		            </li>
		            <li>
		                <a ui-sref-active="active-nav" class="lw-item-link" title="<?=  __('Tax Rules')  ?>" ui-sref="taxes">
                            <i class="fa fa-percent"></i> <?=  __('Tax Rules')  ?>
                        </a>
		            </li>
		            <li>
		                <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Coupons')  ?>" ui-sref="coupons.current">
                            <sub><i class="fa fa-scissors"></i></sub><i class="fa fa-usd"></i> <?=  __('Coupons')  ?>
                        </a>
		            </li>
                    */ ?>
        		</ul>
			</div>
            <div class="panel-heading">
                <h3 class="panel-title"><?= __("Manage Products ") ?></h3>
            </div>

            <div class="panel-body lw-links-container">
            <ul class="nav custom-sidebar">                
                <li>
                    <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Brands')  ?>" ui-sref="brands">
                        <i class="fa fa-bold"></i> <?=  __('Brands')  ?>
                    </a>
                </li>
                <li>
                    <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Categories & Products')  ?>" ui-sref="categories({mCategoryID:''})">
                        <i class="fa fa-th-large"></i> <?=  __('Categories & Products')  ?>
                    </a>
                </li>
            </ul>
            </div>
            <?php /*
            <div class="panel-heading">
                <h3 class="panel-title"><?= __("Reports &amp; Orders ") ?></h3>
            </div>

            <div class="panel-body lw-links-container">
            <ul class="nav custom-sidebar">

                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Orders')  ?>" ui-sref="orders.active">
                            <i class="fa fa-list"></i> <?=  __('Orders')  ?>
                            
                            <span ng-show="manageCtrl.newOrderPlacedCount != 0" class="label label-warning" ng-bind="manageCtrl.newOrderPlacedCount"></span>
                        </a>
                    </li>
                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Reports')  ?>" ui-sref="reports"><i class="fa fa-list-alt" ></i> 
                            <?=  __('Reports')  ?>
                        </a>
                    </li>
                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Order Payments')  ?>" ui-sref="payments"><i class="fa fa-money" ></i> 
                            <?=  __('Order Payments')  ?>
                        </a>
                    </li>
            </ul>
            </div>
            */ ?>
            <div class="panel-heading">
                <h3 class="panel-title"> <?= __("Other ") ?></h3>
            </div>
            <div class="panel-body lw-links-container">
                <ul class="nav custom-sidebar">
                    <?php /*
                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Pages')  ?>" ui-sref="pages({parentPageID:''})">
                            <i class="fa fa-files-o"></i> <?=  __('Pages')  ?>
                        </a>
                    </li>
                    */ ?>
                    <li>
                        <a class="lw-item-link" ui-sref-active="active-nav" title="<?=  __('Users')  ?>" ui-sref="users">
                          <i class="fa fa-users"></i> <?=  __('Users')  ?>
                        </a>
                    </li>
                </ul>
            </div>
    	</div>

    </div>
</div>