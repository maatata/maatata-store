<div  class="col-lg-12" ng-controller="EditStoreSettingsController as settingsCtrl">
    <div class="lw-section-heading-block">
        <!-- main heading -->
        <h3 class="lw-section-heading"><?= __( 'Basic Configuration' ) ?></h3>
        <!-- /main heading -->
    </div>

    <div class="panel-group lw-accordion" id="accordion" role="tablist" aria-multiselectable="true">	
    	<!-- General Setting -->
		<div class="panel panel-default">
    		<div class="panel-heading" role="tab">
    			<h4 class="panel-title">
			      	<a ng-class="{ 'collapsed': !settingsCtrl.generalPanelStatus }" role="button" ng-click="settingsCtrl.getPage(1)" title="<?= __('General') ?>" data-toggle="collapse" data-parent="#accordion" data-target="#general" aria-expanded="false" aria-controls="general">
			          	<div class="lw-collapsed">
                       		<?=  __('General')  ?>
                        	<i class="pull-right fa fa-caret-down lw-on-expand"></i>
                            <i class="pull-right fa fa-caret-right lw-on-collapsed"></i>
                        </div>
			        </a>
			    </h4>
    		</div>

    		<div id="general" ng-class="settingsCtrl.generalPanelClass" role="tabpanel" aria-labelledby="headingThree" ng-if="settingsCtrl.generalPanelStatus">
    			<div class="panel-body">
                	@include('store.general')
                </div>
            </div>
    	</div>
		<!-- /General Setting -->
    </div>
    
</div>