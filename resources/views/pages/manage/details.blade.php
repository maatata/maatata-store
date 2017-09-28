<div ng-controller="PageDetailsController as pageDetailsCtrl">
	
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading" ng-bind="pageDetailsCtrl.pageDetails.title"></h3>
        <!-- /main heading -->

        <!-- button -->
	    <div class="lw-section-right-content pull-right">
	        <a title="<?=  __('Back')  ?>" ui-sref="pages" class="lw-btn btn btn-default btn-sm"><?=  __('Back')  ?></a>
	    </div>
	    <!-- /button -->
    </div>

	<div class="lw-image-width" ng-bind-html="pageDetailsCtrl.pageDetails.description"></div>	
</div>